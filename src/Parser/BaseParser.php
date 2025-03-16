<?php

declare(strict_types=1);

namespace SBOMinator\Parser;

use SBOMinator\Dependency;
use SBOMinator\Enum\FileType;

abstract class BaseParser
{
    /**
     * The parsed package data.
     * For SPDX: keyed by SPDXID.
     */
    protected array $packages = [];

    /**
     * Global cache to avoid duplicate dependency trees.
     */
    protected array $globalBuilt = [];

    protected bool $noDevPackages = false;

    protected FileType $originFileType;

    /**
     * Loads JSON from file and parses it.
     */
    public function loadFromFile(string $filePath): self
    {
        $json = json_decode(file_get_contents($filePath), true);
        $this->parseJson($json);
        return $this;
    }

    /**
     * Loads JSON from a string and parses it.
     */
    public function loadFromString(string $fileContent): self
    {
        $json = json_decode($fileContent, true);
        $this->parseJson($json);
        return $this;
    }

    /**
     * Format-specific JSON parsing.
     *
     * @param array $json The decoded JSON.
     */
    abstract protected function parseJson(array $json): void;

    /**
     * Given an identifier, returns the corresponding package data.
     */
    abstract protected function findPackageByIdentifier(string $identifier): ?array;

    /**
     * Returns the version string from a package.
     */
    abstract protected function getVersion(array $package): string;

    /**
     * Returns an array of dependency identifiers for the given package.
     */
    abstract protected function getDependencies(array $package): array;

    /**
     * Given a parent package identifier and a dependency name,
     * returns the identifier used to find that dependency.
     */
    abstract protected function resolveDependencyIdentifier(string $parentIdentifier, string $depName): string;

    /**
     * Returns an array of top-level package identifiers.
     */
    abstract protected function getTopLevelIdentifiers(): array;

    /**
     * Recursively builds a dependency tree for the package identified by $identifier.
     * Uses a global cache and a local visited list to prevent cycles.
     *
     * @param string $identifier The package identifier.
     * @param array $visited List of identifiers visited in the current branch.
     * @return Dependency|null The built dependency tree or null if already built.
     */
    protected function buildDependencyTree(string $identifier, array $visited = []): ?Dependency
    {
        if (in_array($identifier, $visited, true)) {
            return null;
        }
        if (isset($this->globalBuilt[$identifier])) {
            return null;
        }
        $package = $this->findPackageByIdentifier($identifier);
        if (!$package) {
            return null;
        }
        $visited[] = $identifier;
        $subDeps = [];
        foreach ($this->getDependencies($package) as $depName) {
            $childIdentifier = $this->resolveDependencyIdentifier($identifier, $depName);
            $subTree = $this->buildDependencyTree($childIdentifier, $visited);
            if ($subTree !== null) {
                $subDeps[] = $subTree;
            }
        }
        $dependency = new Dependency($identifier, $this->getVersion($package), $this->originFileType->value, $subDeps);
        $this->globalBuilt[$identifier] = $dependency;
        return $dependency;
    }

    /**
     * Returns the deduplicated dependency trees as an array of Dependency objects.
     *
     * @return Dependency[] Array of top-level dependency trees.
     */
    public function parseDependencies(): array
    {
        $this->globalBuilt = [];
        $dependencies = [];
        foreach ($this->getTopLevelIdentifiers() as $identifier) {
            $dep = $this->buildDependencyTree($identifier);
            if ($dep !== null) {
                $dependencies[] = $dep;
            }
        }
        return $dependencies;
    }

    public function withoutDevPackages(): self {
        $this->noDevPackages = true;
        return $this;
    }
}
