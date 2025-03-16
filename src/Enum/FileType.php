<?php

namespace SBOMinator\Enum;

use SBOMinator\Parser\BaseParser;
use SBOMinator\Parser\ComposerParser;
use SBOMinator\Parser\CycloneDXParser;
use SBOMinator\Parser\NpmParser;
use SBOMinator\Parser\SpdxParser;

enum FileType: string {
    case COMPOSER_LOCK_FILE = 'COMPOSER_LOCK_FILE';
    case NODE_PACKAGE_LOCK_FILE = 'NODE_PACKAGE_LOCK_FILE';
    case CYCLONEDX_SBOM_FILE = 'CYCLONEDX_SBOM_FILE';
    case SPDX_SBOM_FILE = 'SPDX_SBOM_FILE';

    // get parser
    public function getParser(): string {
        return match ($this) {
            self::COMPOSER_LOCK_FILE => ComposerParser::class,
            self::NODE_PACKAGE_LOCK_FILE => NpmParser::class,
            self::CYCLONEDX_SBOM_FILE => CycloneDXParser::class,
            self::SPDX_SBOM_FILE => SpdxParser::class,
        };
    }
}
