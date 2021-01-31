<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Driver\Connection;
use App\Entity\Web\UserAccount;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory as FakerFactory;

class CreateUsersCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var EncoderFactoryInterface
     */
    protected $passwordEncoder;

    public function __construct(Connection $connection, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->connection = $connection;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct(); 
    }

    protected function configure()
    {
        $this
        ->addArgument('quantity', InputArgument::REQUIRED, 'How many dummy user do you want to create?')
        ->setHelp('This command allows you to set default values for necessary project will run correctly.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            '============',
            '============',
            '============',
            '============',
            'Creating Users',
            '============',
        ]);

        $quantity = $input->getArgument('quantity');

        //add product
        $faker = FakerFactory::create('tr_TR');

        for ($i=1; $i <= $quantity; $i++) {
            $userEntity = new UserAccount();
            $encodedPassword = $this->passwordEncoder->encodePassword($userEntity, '123123a');

            $statement = $this->connection->prepare('
                INSERT INTO user_account
                    (name, email, mobile, password, activation_code, ip_address, is_email_approved)
                VALUES
                    (:name, :email, :mobile, :password, :activation_code, :ip_address, :is_email_approved)
            ');

            $statement->bindValue(':name', $faker->name);
            $statement->bindValue(':email', $faker->safeEmail);
            $statement->bindValue(':mobile', null);
            $statement->bindValue(':password', $encodedPassword);
            $statement->bindValue(':activation_code', $faker->uuid);
            $statement->bindValue(':ip_address', '127.168.1.1');
            $statement->bindValue(':is_email_approved', true);
            $statement->execute();
        }

        $output->writeln([
            '============',
            "Done.",
            '',
        ]);

        return 0;
    }
}
