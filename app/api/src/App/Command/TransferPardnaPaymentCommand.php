<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class TransferPardnaPaymentCommand extends Command
{
    protected function configure()
    {
      $this
      ->setName('pardna:transfer:payment')
      ->setDescription('Transfer pardna parments')
      ->setHelp("This program allows you to transfer pardna payments...")
      // ->addArgument('id', InputArgument::REQUIRED, 'Pardna Id')
      ->addArgument('id', InputArgument::OPTIONAL, 'Enter Id');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $output->writeln(array(
          '<info>Testing console output</>',
          '<info>==========================</>',
          '',
      ));
    }
}
