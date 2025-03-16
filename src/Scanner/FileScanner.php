<?php
namespace SBOMinator\Scanner;

use SBOMinator\Dependency;
use SBOMinator\Detector\FileType\ComposerLockFileTypeDetector;
use SBOMinator\Detector\FileType\CycloneDXSBOMFileTypeDetector;
use SBOMinator\Detector\FileType\FileTypeDetectorInterface;
use SBOMinator\Detector\FileType\PackageLockFileTypeDetector;
use SBOMinator\Detector\FileType\SPDXBOMFileTypeDetector;
use SBOMinator\Enum\FileType;

/**
 * Main directory scanner class.
 *
 * This class recursively scans a given directory (up to a configurable max depth)
 * for files with extensions in the allowed list and then uses registered detectors
 * to figure out the file type, it then uses the correct parser to parse the file.
 */
class FileScanner {
    protected int $maxDepth;
    protected array $extensions;
    /** @var FileTypeDetectorInterface[] */
    protected array $detectors = [];

    /**
     * Constructor.
     *
     * @param int   $maxDepth   Maximum recursion depth (default 10).
     * @param array $extensions Array of allowed file extensions (default ['json', 'lock']).
     */
    public function __construct(int $maxDepth = 10, array $extensions = ['json', 'lock']) {
        $this->maxDepth = $maxDepth;
        // Normalize extensions to lower case
        $this->extensions = array_map('strtolower', $extensions);

        // Initialize default detectors.
        $this->detectors[] = new ComposerLockFileTypeDetector();
        $this->detectors[] = new PackageLockFileTypeDetector();
        $this->detectors[] = new CycloneDXSBOMFileTypeDetector();
        $this->detectors[] = new SPDXBOMFileTypeDetector();
    }

    /**
     * Allows adding a custom detector.
     *
     * @param FileTypeDetectorInterface $detector
     */
    public function addDetector(FileTypeDetectorInterface $detector): void {
        $this->detectors[] = $detector;
    }

    /**
     * Scan the directory and its subdirectories for dependencies and combine them.
     *
     * @param string $path The directory path to scan.
     *
     * @return array An array of results, each with 'file' and 'type' keys.
     */
    public function scanForDependencies(string $path): array {
        $results = [];
        $this->recursiveScan($path, 0, $results);

        foreach ($results as &$result) {
            $fileType = $result['type'];
            $parserName = $fileType->getParser();
            $parser = new $parserName();
            $parser->loadFromString(file_get_contents($result['file']));
            $result['dependencies'] = $parser->parseDependencies();
        }

        return self::combineDependencies($results);
    }

    /**
     * Recursively scan the directory.
     *
     * @param string $path         The directory path.
     * @param int    $currentDepth The current recursion depth.
     * @param array  $results      Reference to the results array.
     */
    protected function recursiveScan(string $path, int $currentDepth, array &$results): void {
        if ($currentDepth > $this->maxDepth) {
            return;
        }

        if (!is_dir($path)) {
            return;
        }

        $files = scandir($path);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $fullPath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
            if (is_dir($fullPath)) {
                $this->recursiveScan($fullPath, $currentDepth + 1, $results);
            } else {
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($extension, $this->extensions, true)) {
                    $detectedType = $this->detectFileType($fullPath);
                    if ($detectedType !== null) {
                        $results[] = [
                            'file' => $fullPath,
                            'type' => $detectedType,
                        ];
                    }
                }
            }
        }
    }

    /**
     * Read the file, decode JSON and use detectors to identify file type.
     *
     * @param string $filePath The path of the file.
     *
     * @return FileType|null The detected type or null if not recognized.
     */
    protected function detectFileType(string $filePath): ?FileType {
        $content = file_get_contents($filePath);
        if ($content === false) {
            return null;
        }

        $jsonData = json_decode($content, true);
        if ($jsonData === null) {
            return null;
        }

        foreach ($this->detectors as $detector) {
            $result = $detector->detect($jsonData, $filePath);
            if ($result !== null) {
                return $result;
            }
        }

        return null;
    }

    public static function combineDependencies(array $results): array
    {
        $combined = [];
        // Iterate over each file entry
        foreach ($results as $result) {
            if (isset($result['dependencies']) && is_array($result['dependencies'])) {
                // For each top-level dependency, collect recursively.
                foreach ($result['dependencies'] as $dependency) {
                    self::collectDependencies($dependency, $combined);
                }
            }
        }
        // Return the unique dependencies as a re-indexed array
        return array_values($combined);
    }

    /**
     * Recursively collects dependencies into the $combined array.
     *
     * @param Dependency $dependency The dependency to collect.
     * @param array  &$combined The array of unique dependencies collected so far.
     */
    private static function collectDependencies(Dependency $dependency, array &$combined): void
    {
        $key = $dependency->getName();
        // Only add if not already added
        if (!isset($combined[$key])) {
            $combined[$key] = $dependency;
        }
        // Process nested dependencies recursively
        foreach ($dependency->getDependencies() as $child) {
            self::collectDependencies($child, $combined);
        }
    }

}
