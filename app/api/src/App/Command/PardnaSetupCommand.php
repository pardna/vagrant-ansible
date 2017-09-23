<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PardnaSetupCommand extends Command
{
  private $app;

  public function __construct(\Silex\Application $app) {
    $this->app = $app;
    parent::__construct();
  }
  
    protected function configure()
    {
      $this
      ->setName('pardna:setup')
      ->setDescription('Setup pardna')
      ->setHelp("This program allows you to setup pardna payments...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
