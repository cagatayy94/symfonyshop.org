<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\Admin\Account as AdminAccountService;

class StartCommand extends Command
{
    protected static $defaultName = 'app:init-project';

    /**
     * @var AdminAccountService
     */
    private $adminAccountService;

    public function __construct(AdminAccountService $adminAccountService)
    {
        $this->adminAccountService = $adminAccountService;

        parent::__construct(); 
    }

    protected function configure()
    {
        $this
        ->addArgument('admin_email', InputArgument::REQUIRED, 'The email of the Fully Outhorized Admin.')
        ->addArgument('admin_password', InputArgument::REQUIRED, 'The password of the Fully Outhorized Admin.')
        ->setDescription('Inits the project, Creates default Admin User And Creates tables and inserts necessary values')
        ->setHelp('This command allows you to set default values for necessary project will run correctly.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Executing Commands',
            '============',
            '',
        ]);

        $args = $input->getArguments();
        
        try {
            $this->adminAccountService->initTheProject($args['admin_email'], $args['admin_password']);
        } catch (\Exception $e) {
            throw $e;
        }

        $output->writeln('Done.');

        return 0;
    }
}
