<?php

namespace App\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

#[AsCommand(
    name: 'api:user:update',
    description: 'update user info',
)]
class apiUserUpdateConsole extends Command
{
    use apiCommon;

    private string $url = '/user/update';

    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $io->ask(
            'Enter user email for update',
            null,
            function (?string $input) {
                Assert::email($input, 'Email is invalid');
                return $input;
            }
        );

        $newEmail = $io->ask(
            'Enter NEW user email',
            $email,
            function (?string $input) {
                Assert::email($input, 'Email is invalid');
                return $input;
            }
        );

        $newName = $io->ask(
            'Enter NEW name',
            null,
            function (?string $input) {
                Assert::stringNotEmpty($input, 'name is empty');
                return $input;
            }
        );

        $this->makeRequestAndOut($output, [
            'email' => $email,
            'new' => [
                'name' => $newName,
                'email' => $newEmail
            ]
        ]);

        return Command::SUCCESS;
    }


}