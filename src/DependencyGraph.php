<?php

declare(strict_types=1);

namespace SBOMinator;

class DependencyGraph
{
    private Dependency $root;
    private string $outputFile;
    private int $imageWidth;
    private int $imageHeight;
    private int $verticalSpacing = 80;
    private int $charWidth = 8; // Approximate width per character
    private int $baseSpacing = 50; // Minimum horizontal spacing

    public function __construct(Dependency $root, string $outputFile)
    {
        $this->root = $root;
        $this->outputFile = $outputFile;
        $this->calculateImageSize();
    }

    private function calculateImageSize(): void
    {
        $maxDepth = $this->calculateDepth($this->root);
        $maxWidth = $this->calculateMaxWidth($this->root);

        $this->imageWidth = max(1920, $maxWidth);
        $this->imageHeight = max(1080, $maxDepth * $this->verticalSpacing + 100);
    }

    private function calculateDepth(Dependency $dependency, int $level = 1): int
    {
        $maxDepth = $level;
        foreach ($dependency->getDependencies() as $dep) {
            $maxDepth = max($maxDepth, $this->calculateDepth($dep, $level + 1));
        }
        return $maxDepth;
    }

    private function calculateMaxWidth(Dependency $dependency): int
    {
        $nodeWidth = $this->getNodeWidth($dependency);
        $childWidths = array_map(fn($dep) => $this->calculateMaxWidth($dep), $dependency->getDependencies());

        return max($nodeWidth, array_sum($childWidths) + count($childWidths) * $this->baseSpacing);
    }

    private function getNodeWidth(Dependency $dependency): int
    {
        $textLength = strlen($dependency->getName() . " (" . $dependency->getVersion() . ")");
        return max($this->baseSpacing, $textLength * $this->charWidth);
    }

    public function generateGraph(): void
    {
        $image = imagecreatetruecolor($this->imageWidth, $this->imageHeight);
        $backgroundColor = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $backgroundColor);

        $black = imagecolorallocate($image, 0, 0, 0);
        $red = imagecolorallocate($image, 255, 0, 0);

        $positions = [];
        $this->generateImageGraph($image, $this->root, $this->imageWidth / 2, 50, $black, $red, $positions);

        imagepng($image, $this->outputFile);
        imagedestroy($image);
    }

    private function generateImageGraph($image, Dependency $dependency, int $x, int $y, $textColor, $lineColor, array &$positions): void
    {
        $fontSize = 3;
        $boxHeight = 30;
        $nodeWidth = $this->getNodeWidth($dependency);

        imagestring($image, $fontSize, $x - ($nodeWidth / 2), $y, $dependency->getName() . " ({$dependency->getVersion()})", $textColor);

        $positions[$dependency->getName()] = ['x' => $x, 'y' => $y];

        $children = $dependency->getDependencies();
        if (empty($children)) {
            return;
        }

        $totalWidth = array_sum(array_map(fn($dep) => $this->getNodeWidth($dep), $children)) + (count($children) - 1) * $this->baseSpacing;
        $childXStart = $x - ($totalWidth / 2);
        $childY = $y + $this->verticalSpacing;

        foreach ($children as $dep) {
            $childNodeWidth = $this->getNodeWidth($dep);
            $childX = $childXStart + ($childNodeWidth / 2);
            imageline($image, $x, $y + 10, $childX, $childY, $lineColor);
            $this->generateImageGraph($image, $dep, $childX, $childY, $textColor, $lineColor, $positions);
            $childXStart += $childNodeWidth + $this->baseSpacing;
        }
    }
}
