<?php

namespace App\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

#[AsCommand(
    name: 'api:user:group:add',
    description: 'add group to user',
)]
class apiUserGroupAddConsole extends Command
{
    use apiCommon;

    private string $url = '/user/group/add';

    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $io->ask(
            'Enter user email for add group',
            null,
            function (?string $input) {
                Assert::email($input, 'Email is invalid');
                return $input;
            }
        );

        $group = $io->ask(
            'Enter group name',
            null,
            function (?string $input) {
                Assert::stringNotEmpty($input, 'group name is empty');
                return $input;
            }
        );

        $this->makeRequestAndOut($output, [
            'email' => $email,
            'group' => $group
        ]);

        return Command::SUCCESS;
    }


}