<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class StartCommand extends Command
{
    protected static $defaultName = 'app:init-project';

    protected function configure()
    {
        $this
        ->addArgument('admin_email', InputArgument::REQUIRED, 'The email of the Fully Outhorized Admin.')
        ->setDescription('Inits the project, Creates default Admin User And Creates tables and inserts necessary values')
        ->setHelp('This command allows you to set default values for necessary project will run correctly.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        // outputs a message followed by a "\n"
        $output->writeln('Whoa!');

        // outputs a message without adding a "\n" at the end of the line
        $output->write('You are about to ');
        $output->write('create a user.');

        return 0;
    }
}
