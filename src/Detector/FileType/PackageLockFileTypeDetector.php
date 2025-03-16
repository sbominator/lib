<?php

namespace SBOMinator\Lib\Detector\FileType;

use SBOMinator\Lib\Enum\FileType;

/**
 * Detector for package lock files (e.g., npm's package-lock.json).
 * These files usually contain a "lockfileVersion" key.
 */
class PackageLockFileTypeDetector implements FileTypeDetectorInterface {
    public function detect(array $jsonData, string $filePath): ?FileType
    {
        if (isset($jsonData['lockfileVersion'])) {
            return FileType::NODE_PACKAGE_LOCK_FILE;
        }
        return null;
    }
}
