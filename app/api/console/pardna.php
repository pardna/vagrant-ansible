<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Command\CollectPardnaPaymentsCommand;
use App\Command\TransferPardnaPaymentCommand;
use App\Command\PardnaSetupCommand;

$application = new Application();

// ... register commands
$application->add(new CollectPardnaPaymentsCommand());
$application->add(new PardnaSetupCommand());
$application->add(new TransferPardnaPaymentCommand());
$application->run();
