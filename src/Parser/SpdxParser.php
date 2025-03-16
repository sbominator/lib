<?php

declare(strict_types=1);

namespace SBOMinator\Lib\Parser;

use SBOMinator\Dependency;
use SBOMinator\Enum\FileType;

class SpdxParser extends BaseParser
{
    protected FileType $originFileType = FileType::SPDX_SBOM_FILE;
    /**
     * Dependency graph mapping a package's SPDXID to an array of dependency SPDXIDs.
     *
     * @var array<string, array<string>>
     */
    protected array $depGraph = [];

    /**
     * Parses an SPDX SBOM JSON file.
     *
     * Expects the JSON to have:
     * - a "packages" array (each package must have an "SPDXID", a "name", and optionally a "versionInfo")
     * - a "relationships" array containing objects with:
     *     - "spdxElementId" (the source package)
     *     - "relationshipType" (we only care about "DEPENDS_ON")
     *     - "relatedSpdxElement" (the dependency package)
     *
     * @param array $json
     * @throws \Exception if the JSON structure is invalid.
     */
    protected function parseJson(array $json): void
    {
        if (empty($json) || !isset($json['packages'])) {
            throw new \Exception('Invalid SPDX file');
        }

        // Build packages dictionary keyed by SPDXID.
        $this->packages = [];
        foreach ($json['packages'] as $pkg) {
            if (isset($pkg['SPDXID'])) {
                $this->packages[$pkg['SPDXID']] = $pkg;
            }
        }

        // Build dependency graph from relationships.
        $this->depGraph = [];
        if (isset($json['relationships']) && is_array($json['relationships'])) {
            foreach ($json['relationships'] as $rel) {
                if (
                    isset($rel['relationshipType']) &&
                    $rel['relationshipType'] === 'DEPENDS_ON' &&
                    isset($rel['spdxElementId'], $rel['relatedSpdxElement'])
                ) {
                    $source = $rel['spdxElementId'];
                    $target = $rel['relatedSpdxElement'];
                    if (!isset($this->depGraph[$source])) {
                        $this->depGraph[$source] = [];
                    }
                    $this->depGraph[$source][] = $target;
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
        // Use the "versionInfo" field if available.
        return $package['versionInfo'] ?? '';
    }

    protected function getDependencies(array $package): array
    {
        $id = $package['SPDXID'] ?? null;
        if ($id && isset($this->depGraph[$id])) {
            return $this->depGraph[$id];
        }
        return [];
    }

    protected function resolveDependencyIdentifier(string $parentIdentifier, string $depName): string
    {
        // In SPDX, dependencies are referenced directly by their SPDXID.
        return $depName;
    }

    protected function getTopLevelIdentifiers(): array
    {
        $allIdentifiers = array_keys($this->packages);
        $referenced = [];
        foreach ($this->depGraph as $src => $targets) {
            foreach ($targets as $target) {
                $referenced[$target] = true;
            }
        }
        $top = [];
        foreach ($allIdentifiers as $id) {
            if (!isset($referenced[$id])) {
                $top[] = $id;
            }
        }
        if (empty($top)) {
            $top = $allIdentifiers;
        }
        return $top;
    }

    /**
     * Overrides the BaseParser::buildDependencyTree to use the actual package "name"
     * instead of its SPDXID when constructing the Dependency object.
     *
     * @param string $identifier The SPDXID.
     * @param array $visited List of identifiers visited in the current branch.
     * @return Dependency|null
     */
    protected function buildDependencyTree(string $identifier, array $visited = []): ?Dependency
    {
        // Use the parent's method to build the tree (which caches based on SPDXID).
        $dep = parent::buildDependencyTree($identifier, $visited);
        if ($dep !== null) {
            // Retrieve the package data to get the actual name.
            $pkg = $this->findPackageByIdentifier($identifier);
            $displayName = $pkg['name'] ?? $identifier;
            // Return a new Dependency using the actual package name.
            return new Dependency($displayName, $dep->getVersion(), $dep->getOrigin(), $dep->getDependencies());
        }
        return null;
    }
}
