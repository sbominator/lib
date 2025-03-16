<?php

namespace SBOMinator\Detector\FileType;

use SBOMinator\Enum\FileType;

class ComposerLockFileTypeDetector implements FileTypeDetectorInterface {
    public function detect(array $jsonData, string $filePath): ?FileType
    {
        if (isset($jsonData['packages']) && isset($jsonData['content-hash'])) {
            return FileType::COMPOSER_LOCK_FILE;
        }
        return null;
    }
}
