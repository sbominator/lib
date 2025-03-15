<?php

namespace lib\sbominator\src\Parser;

use lib\sbominator\src\Dependency;

class ComposerParser extends BaseParser
{
    private string $contentHash;
    private array $packages;
    public function __construct() {}

    public function loadFromFile(string $filePath): self
    {
        $json = json_decode(file_get_contents('composer.json'), true);

        if(!$json || empty($json) || empty($json['content-hash']) || empty($json['packages'])) {
            throw new \Exception('Invalid composer.json file');
        }

        $this->contentHash = $json['content-hash'];
        $this->packages = $json['packages'];

        return $this;
    }

    public function loadFromString(string $fileContent): self
    {
        $json = json_decode($fileContent, true);

        return $this;
    }

    private function parseJson($json): void
    {
        if(!$json || empty($json) || empty($json['content-hash']) || empty($json['packages'])) {
            throw new \Exception('Invalid composer.json file');
        }

        $this->contentHash = $json['content-hash'];
        $this->packages = $json['packages'];

        if(!empty($json['packages-dev'])) {
            $this->packages = array_merge($this->packages, $json['packages-dev']);
        }

    }

    public function getContentHash(): string
    {
        return $this->contentHash;
    }

    public function getPackages(): array
    {
        return $this->packages;
    }

    public function parseDependencies($package) {
        $dependencies = [];
        foreach($this->packages as $package) {
            $dependency['name'] = $package['name'];
            $dependency['version'] = $package['version'];
            $dependency['dependencies'] = [];
        }
        return $dependencies;
    }
}
