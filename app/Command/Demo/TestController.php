<?php

declare(strict_types=1);

namespace App\Command\Demo;

use SBOMinator\Parser\ComposerParser;
use Minicli\Command\CommandController;

class TestController extends CommandController
{
    public function handle(): void
    {
        $this->info('This is a test controller.');
        $composerParser = new ComposerParser();
        $composerLock = file_get_contents('composer.json');

        try {
            $composerParser->loadFromString($composerLock);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return;
        }

    }
}
