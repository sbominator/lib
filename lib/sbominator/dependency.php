<?php

declare(strict_types=1);

namespace SBOMinator;

class Dependency
{
    private string $version;
    private array $dependencies;

    public function __construct(string $version, array $dependencies = [])
    {
        $this->version = $version;
        $this->dependencies = $dependencies;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }
}
