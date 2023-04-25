<?php

namespace App\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'api:stat:groups',
    description: 'list groups',
)]
class apiStatGroupsConsole extends Command
{
    use apiCommon;

    private string $url = '/stat/groups';

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