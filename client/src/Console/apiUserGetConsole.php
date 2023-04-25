<?php

namespace App\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

#[AsCommand(
    name: 'api:user:get',
    description: 'get user by email',
)]
class apiUserGetConsole extends Command
{
    use apiCommon;

    private string $url = '/user/get';

    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $io->ask(
            'Enter email',
            null,
            function (?string $input) {
                Assert::email($input, 'Email is invalid');
                return $input;
            }
        );

//        $password = $io->askHidden(
//            'password',
//            function (?string $input) {
//                Assert::notEmpty($input, 'Password cannot be empty');
//
//                return $input;
//            }
//        );

        $this->makeRequestAndOut($output, [
            'email' => $email
        ]);

        return Command::SUCCESS;
    }


}