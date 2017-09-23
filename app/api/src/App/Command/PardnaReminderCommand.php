<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PardnaReminderCommand extends Command
{
  private $app;

  public function __construct(\Silex\Application $app) {
    $this->app = $app;
    parent::__construct();
  }

    protected function configure()
    {
      $this
      ->setName('send:pardna:reminder')
      ->setDescription('Email pardna setup ')
      ->setHelp("This program allows you to send pardna email reminders");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
