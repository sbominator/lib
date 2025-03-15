<?php

declare(strict_types=1);

namespace App\Command\Demo;

use App\Helper\DirectoryHelper;
use SBOMinator\Dependency;
use SBOMinator\Parser\ComposerParser;
use Minicli\Command\CommandController;
use SBOMinator\Parser\NpmParser;

class TestController extends CommandController
{

    public function handle(): void
    {
        $this->handleNpm();
    }

    public function handleComposer(): void
    {
        $this->info('Composer.');
        $composerParser = new ComposerParser();

        // Function to print dependency tree
        function printDependencyTree(Dependency $dependency, string $prefix = ""): void
        {
            echo $prefix . "- " . $dependency->getName() . " (" . $dependency->getVersion() . ")" . PHP_EOL;
            foreach ($dependency->getDependencies() as $dep) {
                printDependencyTree($dep, $prefix . "  ");
            }
        }

        /* Recursively check this directory and all subdirectories and all subdirectories of subdirectories with a maxdepth of 10 for composer.lock files */
        $files = [];
        $dir = getcwd();
        $maxdepth = 10;
        $it = new \RecursiveDirectoryIterator($dir);
        $it = new \RecursiveIteratorIterator($it);
        $it->setMaxDepth($maxdepth);
        foreach ($it as $file) {
            if ($file->getFilename() === 'composer.lock') {
                $files[] = [$file->getPathname(), $file->getPath()];
            }
        }

        foreach($files as $file) {
            $composerLock = file_get_contents($file[0]);
            try {
                $composerParser->loadFromString($composerLock);
                $dependencies = $composerParser->parseDependencies();

                // Output parsed dependencies as a tree
                foreach ($dependencies as $dependency) {
                    printDependencyTree($dependency);
                }

            } catch (\Exception $e) {
                $this->error($e->getMessage());
                return;
            }
        }
    }

    public function handleNpm(): void
    {
        $this->info('NPM.');
        $npmParser = new NpmParser();

        // Function to print dependency tree
        function printDependencyTree(Dependency $dependency, string $prefix = ""): void
        {
            echo $prefix . "- " . $dependency->getName() . " (" . $dependency->getVersion() . ")" . PHP_EOL;
            foreach ($dependency->getDependencies() as $dep) {
                printDependencyTree($dep, $prefix . "  ");
            }
        }

        $files = DirectoryHelper::scanDirectoryForFilename(getcwd(), 'package-lock.json', 10);

        foreach($files as $file) {
            $packageLock = file_get_contents($file);
            try {
                $npmParser->loadFromString($packageLock);
                $dependencies = $npmParser->parseDependencies();

                // Output parsed dependencies as a tree
                foreach ($dependencies as $dependency) {
                    printDependencyTree($dependency);
                }

            } catch (\Exception $e) {
                $this->error($e->getMessage());
                return;
            }
        }
    }
}
