<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\ServicesLoader;
use Silex\Application;

class CollectPardnaPaymentsCommand extends Command
{

  private $app;

  public function __construct(\Silex\Application $app) {
    $this->app = $app;
    parent::__construct();
  }

    protected function configure()
    {
      $this
      ->setName('pardna:collect:payments')
      ->setDescription('Collects pardna parments')
      ->setHelp("This program allows you to collect pardna payments...")
      // ->addArgument('id', InputArgument::REQUIRED, 'Pardna Id')
      ->addArgument('id', InputArgument::OPTIONAL, 'Enter Id');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $groupService = $this->app['pardna.group.service'];
      $groups = $groupService->fetchAllRunningPardnas(100);
      // print_r($groups);
      $output->writeln(array(
          '<info>Collection Pardna Parments</>',
          '<info>' . count($groups) . ' Groups Found</>',
          '',
      ));

      foreach ($groups as $key => $group) {
          // $groupService->collectPayments($group);
      }

    }
}
