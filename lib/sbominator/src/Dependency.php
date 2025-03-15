<?php

declare(strict_types=1);

namespace lib\sbominator\src;

class Dependency
{
    private string $name;
    private string $version;
    private array $dependencies;

    public function __construct(string $name, string $version, array $dependencies = [])
    {
        $this->name = $name;
        $this->version = $version;
        $this->dependencies = $dependencies;
    }

    public function getName(): string
    {
        return $this->name;
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
