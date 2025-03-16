<?php

declare(strict_types=1);

namespace SBOMinator\Lib;

class Dependency
{
    private string $name;
    private string $version;
    private array $dependencies;
    private ?string $origin;

    public function __construct(string $name, string $version, string $origin, array $dependencies = [])
    {
        $this->name = $name;
        $this->origin = $origin;
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

    public function getOrigin(): string
    {
        return $this->origin;
    }
}
