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

  private $output;

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
      $this->output = $output;
      $groupService = $this->app['pardna.group.service'];
      $paymentSetupService = $this->app['payments.setup.service'];
      $notificationService = $this->app['notification.service'];

      $date = date("Y-m-d", strtotime('+40 days'));


      $scheduledPayments = $groupService->getDueScheduledPayments($date, 100);
      $failedScheduledPayments = $groupService->getFailedDueScheduledPayments($date, 100);

      $output->writeln(array(
        '<info> Date : ' . $date . '</info>'
      ));
      // print_r($groups);
      $output->writeln(array(
          '<info>Collect Scheduled Pardna Payments</>',
          '<info>' . count($scheduledPayments) . ' Payments Found</>',
          '',
      ));
      $this->collectPayments($groupService, $paymentSetupService, $notificationService, $scheduledPayments);


      $output->writeln(array(
          '<info>Collect
           Failed Scheduled Pardna Payments</>',
          '<info>' . count($failedScheduledPayments) . ' Payments Found</>',
          '',
      ));
      $this->collectPayments($groupService, $paymentSetupService, $notificationService, $failedScheduledPayments);

    }

    protected function collectPayments($groupService, $paymentSetupService, $notificationService, $scheduledPayments) {

      echo "collecting payments \n";
      foreach ($scheduledPayments as $key => $payment) {
        try {
          echo "collecting payment \n";

          $member = $groupService->getMemberByMemberId($payment['pardna_group_member_id']);
          $group = $groupService->findById($member['group_id']);

          // Not all will pay monthly amount because of interest payments
          $group['amount'] = $payment['amount'];

          $paymentResult = $paymentSetupService->createPayment($group, $member, $payment["scheduled_date"]);
          $groupService->updateSuccessScheduledPayment(
            $payment['id'],
            $paymentResult->getId(),
            serialize($paymentResult),
            $payment['attempts'] + 1
          );

          $message = array(
            "message" => "Payment Request Successful",
            "target_type" => "SCHEDULEPARDNAPAYMENT",
            "target_id" => $payment['id']
          );

          $notificationService->log($message);
          // print_r(serialize($paymentResult));
          // PM0005Y532ZFHQ
        } catch(Exception $e) {
          $groupService->updateFailedScheduledPayment($payment['id'], $payment['attempts'] + 1);
          $message = array(
            "message" => "Payment Request Failed",
            "target_type" => "SCHEDULEPARDNAPAYMENT",
            "target_id" => $payment['id']
          );

          $notificationService->log($message);
        }
      }

    }
}
