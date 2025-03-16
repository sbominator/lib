<?php

declare(strict_types=1);

namespace SBOMinator\Parser;

class ComposerParser extends BaseParser
{
    /**
     * Expects a composer.lock JSON structure with keys "content-hash", "packages" and optionally "packages-dev".
     * Converts the packages into an associative array keyed by package name.
     */
    protected function parseJson(array $json): void
    {
        if (empty($json) || empty($json['content-hash']) || !isset($json['packages'])) {
            throw new \Exception('Invalid composer.lock file');
        }

        $allPackages = $json['packages'];
        if (!empty($json['packages-dev'])) {
            $allPackages = array_merge($allPackages, $json['packages-dev']);
        }

        // Convert to associative array keyed by package name.
        $this->packages = [];
        foreach ($allPackages as $pkg) {
            $this->packages[$pkg['name']] = $pkg;
        }
    }

    protected function findPackageByIdentifier(string $identifier): ?array
    {
        return $this->packages[$identifier] ?? null;
    }

    protected function getVersion(array $package): string
    {
        return $package['version'];
    }

    protected function getDependencies(array $package): array
    {
        $deps = [];
        foreach (['require', 'require-dev'] as $key) {
            if (!empty($package[$key])) {
                foreach ($package[$key] as $depName => $version) {
                    $deps[] = $depName;
                }
            }
        }
        return $deps;
    }

    protected function resolveDependencyIdentifier(string $parentIdentifier, string $depName): string
    {
        // In composer.lock, the identifier is simply the package name.
        return $depName;
    }

    protected function getTopLevelIdentifiers(): array
    {
        // Identify packages that are not referenced as dependencies in any package.
        $referenced = [];
        foreach ($this->packages as $pkg) {
            foreach (['require', 'require-dev'] as $key) {
                if (!empty($pkg[$key])) {
                    foreach ($pkg[$key] as $depName => $version) {
                        $referenced[$depName] = true;
                    }
                }
            }
        }
        $top = [];
        foreach ($this->packages as $name => $pkg) {
            if (!isset($referenced[$name])) {
                $top[] = $name;
            }
        }
        // Fallback: if none qualifies, return all package names.
        if (empty($top)) {
            $top = array_keys($this->packages);
        }
        return $top;
    }
}
