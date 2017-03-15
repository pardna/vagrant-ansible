<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CollectPardnaPaymentsCommand extends Command
{
    protected function configure()
    {
      $this
      ->setName('pardna:collect:payments')
      ->setDescription('Collects pardna parments')
      ->setHelp("This program allows you to collect pardna payments...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}
