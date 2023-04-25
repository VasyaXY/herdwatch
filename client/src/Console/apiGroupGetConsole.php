<?php

namespace App\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

#[AsCommand(
    name: 'api:group:get',
    description: 'get group by name',
)]
class apiGroupGetConsole extends Command
{
    use apiCommon;

    private string $url = '/group/get';

    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $io->ask(
            'Enter name',
            null,
            function (?string $input) {
                Assert::stringNotEmpty($input, 'mame is empty');
                return $input;
            }
        );

        $this->makeRequestAndOut($output, [
            'name' => $name
        ]);

        return Command::SUCCESS;
    }


}