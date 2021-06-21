<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Driver\Connection;
use Faker\Factory as FakerFactory;

class CreateCommentsCommand extends Command
{
    protected static $defaultName = 'app:create-comment';

    /**
     * @var Connection
     */
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        parent::__construct(); 
    }

    protected function configure()
    {
        $this
            ->addArgument('quantity_of_each_product', InputArgument::REQUIRED, 'The quantity of each product comment')
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
            'Creating Comments',
            '============',
        ]);

        $productIds = $this->getProductIds();
        $userIds = $this->getUserIds();

        $faker = FakerFactory::create('tr_TR');

        foreach ($productIds as $productId){
            $args = $input->getArguments();
            $quantityOfEachProduct = intval($args['quantity_of_each_product']);


            for ($i=0;$i< $quantityOfEachProduct; $i++){

                $userIdKey = array_rand($userIds);

                $comment = [
                    'product_id' => $productId,
                    'user_id' => $userIds[$userIdKey],
                    'ip_address' => $faker->ipv4,
                    'comment' => $faker->sentence(6, true),
                    'rate' => Rand(0,5)
                ];

                $sql = "
                INSERT INTO product_comment
                    (product_id, user_id, ip_address, comment, rate)
                VALUES
                    (:product_id, :user_id, :ip_address, :comment, :rate)";

                $statement = $this->connection->prepare($sql);

                $statement->bindValue('product_id', $comment['product_id']);
                $statement->bindValue('user_id',  $comment['user_id']);
                $statement->bindValue('ip_address',  $comment['ip_address']);
                $statement->bindValue('comment',  $comment['comment']);
                $statement->bindValue('rate',  $comment['rate']);

                $statement->execute();

            }
        }

        $output->writeln([
            '============',
            "Done.",
            '',
        ]);

        return 0;
    }

    protected function getUserIds()
    {
        //get user ids
        $statement = $this->connection->prepare('
            SELECT 
                id
            from 
                user_account
            ');

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    protected function getProductIds()
    {
        //get categories
        $statement = $this->connection->prepare('
            SELECT id FROM product ORDER BY product.name
        ');

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);

    }
}
