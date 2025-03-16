<?php

namespace SBOMinator\Lib\Detector\FileType;

use SBOMinator\Lib\Enum\FileType;

/**
 * Interface for file type detectors.
 * Each detector must implement a detect() method that checks the JSON data
 * and returns a file type string if the file is recognized, or null otherwise.
 */
interface FileTypeDetectorInterface {
    /**
     * Attempt to detect the type of the JSON file.
     *
     * @param array  $jsonData Parsed JSON data.
     * @param string $filePath The path of the file being examined.
     *
     * @return FileType|null A string describing the type if detected, or null.
     */
    public function detect(array $jsonData, string $filePath): ?FileType;
}
