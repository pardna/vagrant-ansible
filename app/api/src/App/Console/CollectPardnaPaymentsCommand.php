<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\ServicesLoader;

class CollectPardnaPaymentsCommand extends Command
{
    private $servicesLoader;

    public function setServicesLoader(App\ServicesLoader $servicesLoader) {
      $this->servicesLoader = $servicesLoader;
    }

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
