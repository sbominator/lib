<?php

namespace SBOMinator\Lib\Detector\FileType;

use SBOMinator\Lib\Enum\FileType;

/**
 * Detector for CycloneDX SBOM files.
 * These JSON files normally include a "bomFormat" key set to "CycloneDX".
 */
class CycloneDXSBOMFileTypeDetector implements FileTypeDetectorInterface {
    public function detect(array $jsonData, string $filePath): ?FileType
    {
        if (isset($jsonData['bomFormat']) && $jsonData['bomFormat'] === 'CycloneDX') {
            return FileType::CYCLONEDX_SBOM_FILE;
        }
        return null;
    }
}
