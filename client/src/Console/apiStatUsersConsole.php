<?php

namespace App\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'api:stat:users',
    description: 'list of users',
)]
class apiStatUsersConsole extends Command
{
    use apiCommon;

    private string $url = '/stat/users';

    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->makeRequestAndOut($output);

        return Command::SUCCESS;
    }


}