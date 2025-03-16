<?php

declare(strict_types=1);

namespace SBOMinator\Parser;

use SBOMinator\Dependency;

class CycloneDXParser extends BaseParser
{
    /**
     * Dependency graph mapping component identifier ("bom-ref") to an array of dependency identifiers.
     *
     * @var array<string, array<string>>
     */
    protected array $depGraph = [];

    /**
     * Parses a CycloneDX SBOM.
     *
     * Expects a JSON structure with:
     * - "bomFormat" equal to "CycloneDX"
     * - A "components" array where each component has a "bom-ref" and optionally "version"
     * - Optionally, a "dependencies" array mapping a "ref" to its "dependsOn" array.
     *
     * @param array $json
     */
    protected function parseJson(array $json): void
    {
        if (empty($json) || !isset($json['bomFormat']) || $json['bomFormat'] !== 'CycloneDX' || !isset($json['components'])) {
            throw new \Exception('Invalid CycloneDX SBOM file');
        }

        // Build packages dictionary keyed by bom-ref.
        $this->packages = [];
        foreach ($json['components'] as $component) {
            if (isset($component['bom-ref'])) {
                $this->packages[$component['bom-ref']] = $component;
            }
        }

        // If a dependency graph is provided, store it.
        if (isset($json['dependencies']) && is_array($json['dependencies'])) {
            $this->depGraph = [];
            foreach ($json['dependencies'] as $depObj) {
                if (isset($depObj['ref'])) {
                    $this->depGraph[$depObj['ref']] = $depObj['dependsOn'] ?? [];
                }
            }
        }
    }

    protected function findPackageByIdentifier(string $identifier): ?array
    {
        return $this->packages[$identifier] ?? null;
    }

    protected function getVersion(array $package): string
    {
        return $package['version'] ?? '';
    }

    protected function getDependencies(array $package): array
    {
        $identifier = $package['bom-ref'] ?? null;
        if ($identifier && isset($this->depGraph[$identifier]) && is_array($this->depGraph[$identifier])) {
            return $this->depGraph[$identifier];
        }
        return [];
    }

    protected function resolveDependencyIdentifier(string $parentIdentifier, string $depName): string
    {
        // In CycloneDX, dependency identifiers are used as provided.
        return $depName;
    }

    protected function getTopLevelIdentifiers(): array
    {
        $allIdentifiers = array_keys($this->packages);
        $referenced = [];
        foreach ($this->depGraph as $ref => $deps) {
            foreach ($deps as $d) {
                $referenced[$d] = true;
            }
        }
        $top = [];
        foreach ($allIdentifiers as $id) {
            if (!isset($referenced[$id])) {
                $top[] = $id;
            }
        }
        // Fallback: if none qualifies, return all.
        if (empty($top)) {
            $top = $allIdentifiers;
        }
        return $top;
    }

}
