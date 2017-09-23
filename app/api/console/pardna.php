<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Command\CollectPardnaPaymentsCommand;
use App\Command\TransferPardnaPaymentCommand;
use App\Command\PardnaReminderCommand;
use App\Command\PardnaSetupCommand;
use App\ServicesLoader;



$application = new Application();

$app = new Silex\Application();
require __DIR__ . '/../resources/config/dev.php';
require __DIR__ . '/../src/app.php';


//load services
$servicesLoader = new App\ServicesLoader($app);
$servicesLoader->bindServicesIntoContainer();



// ... register commands
$application->add(new CollectPardnaPaymentsCommand($app));
$application->add(new PardnaSetupCommand($app));
$application->add(new TransferPardnaPaymentCommand($app));
$application->add(new PardnaReminderCommand($app));
$application->run();
