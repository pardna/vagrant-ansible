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
      $groupService = $this->app['pardna.group.service'];
      $list = $groupService->getConfirmedPardnasToSetup(100);
      $output->writeln(array(
          '<info>Schedule Confirmed Parda Payments</>',
          '<info>' . count($list) . ' Confirmed Pardnas Found</>',
          '',
      ));
      foreach ($list as $key => $confirmed) {
        $groupService->beginTransaction();
        try {


          $this->setupPayments($groupService, $confirmed["id"]);
          $groupService->confirmPardnaPaymentSetup($confirmed["id"]);
          $groupService->commit();

        } catch (\Exception $e) {
          $groupService->rollBack(); // 2 => 1, transaction marked for rollback only
        }

      }
    }

    public function setupPayments($groupService, $id) {
      $list = $groupService->getPardnaPaymentSetupList($id);
      // for each pay date setup debit for each member
      foreach($list AS $key => $value) {
        $this->setupPayment($groupService, $list, $value["pay_date"]);
      }
    }

    public function setupPayment($groupService, $payouts, $scheduledDate) {
        foreach($payouts AS $key => $payout) {
          $data = array(
            "amount" => $payout["amount"],
            "pardna_group_member_id" => $payout["member_id"],
            "scheduled_date" => $scheduledDate,
            "status" => "SCHEDULED"
          );
          echo "adding ...\n";
          // print_r($data);
          $groupService->addSchedulePardnaPayment($data);
        }
    }
}
