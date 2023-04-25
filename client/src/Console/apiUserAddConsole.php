<?php

namespace App\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

#[AsCommand(
    name: 'api:user:add',
    description: 'create a new user',
)]
class apiUserAddConsole extends Command
{
    use apiCommon;

    private string $url = '/user/add';

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
                Assert::stringNotEmpty($input, 'Name is empty');
                return $input;
            }
        );

        $email = $io->ask(
            'Enter email',
            null,
            function (?string $input) {
                Assert::email($input, 'Email is invalid');
                return $input;
            }
        );

        $this->makeRequestAndOut($output, [
            'name' => $name,
            'email' => $email
        ]);

        return Command::SUCCESS;
    }


}