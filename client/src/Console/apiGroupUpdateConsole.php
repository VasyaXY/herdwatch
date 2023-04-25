<?php

namespace App\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

#[AsCommand(
    name: 'api:group:update',
    description: 'update group',
)]
class apiGroupUpdateConsole extends Command
{
    use apiCommon;

    private string $url = '/group/update';

    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $io->ask(
            'Enter name group for update',
            null,
            function (?string $input) {
                Assert::stringNotEmpty($input, 'mame is empty');
                return $input;
            }
        );

        $newName = $io->ask(
            'Enter a new name for group',
            null,
            function (?string $input) {
                Assert::stringNotEmpty($input, 'mame is empty');
                return $input;
            }
        );

        $this->makeRequestAndOut($output, [
            'name' => $name,
            'new' => [
                'name' => $newName
            ]
        ]);

        return Command::SUCCESS;
    }


}