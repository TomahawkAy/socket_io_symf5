<?php

namespace App\Command;

use App\Socket\SocketServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SocketRunCommand extends Command
{
    protected static $defaultName = 'socket:run';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while (1){
            $data = 0;
            $socket = SocketServer::getSocketInstance();
            $holder = false;
            $socket->on('connection', static function ($socket) use ($output,$holder) {
                global $holder;
                $holder = true;
                $output->writeln('connection have been made !');
                $socket->on('button_clicked', static function ($data) use ($socket,$output) {
                    // here when an event is happened you will be emitting an event to the clients .
                    $socket->broadcast->emit('add_notification', array(
                        'username' => 'whatever data',
                        'message' => $data
                    ));
                });
                $socket->on('notification_readed', static function ($data) use ($socket) {
                    // here you will be looking for the notification id through data and deleting it's content when needed :)
                });
                $socket->on('disconnected',static function($socket){
                    return Command::SUCCESS;
                });
            });
            if ($holder)
            {
                break;
            }
        }

        return 1;
    }
}
