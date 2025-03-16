<?php

namespace SBOMinator\Lib\Detector\FileType;

use SBOMinator\Lib\Enum\FileType;

/**
 * Detector for SPDX SBOM files.
 * SPDX files usually include an "spdxVersion" key.
 */
class SPDXBOMFileTypeDetector implements FileTypeDetectorInterface {
    public function detect(array $jsonData, string $filePath): ?FileType
    {
        if (isset($jsonData['spdxVersion'])) {
            return FileType::SPDX_SBOM_FILE;
        }
        return null;
    }
}
