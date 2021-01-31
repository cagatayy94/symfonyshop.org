<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Driver\Connection;
use Faker\Factory as FakerFactory;

class CreateProductsCommand extends Command
{
    protected static $defaultName = 'app:create-product';
    protected $mydir = 'public/web/img/product';

    /**
     * @var Connection
     */
    protected $connection;

    protected $variants = [
        "Renk" => [
            "Kırmızı" => 10,
            "Mavi" => 10,
            "Yeşil" => 10,
            "Beyaz" => 10,
            "Siyah" => 10,
            "Kahverengi" => 10
        ],
        "Beden" => [
            "xSmall" => 3,
            "Small" => 4,
            "Mediun" => 12,
            "Large" => 9,
            "xLarge" => 19,
            "xxLarge" => 9,
        ],
        "Numara" => [
            "35" => 1,
            "36" => 8,
            "37" => 11,
            "38" => 17,
            "39" => 1,
            "40" => 12,
            "41" => 3,
        ]
    ];

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        parent::__construct(); 
    }

    protected function configure()
    {
        $this
        ->addArgument('quantity', InputArgument::REQUIRED, 'How many dummy product do you want to create?')
        ->setHelp('This command allows you to create dummy products.');
    }

    protected function getProductImgFileNameArray()
    {
        $myFiles = scandir($this->mydir);

        array_shift($myFiles);
        array_shift($myFiles);

        $imgs = [];
        $i = 0;

        foreach ($myFiles as $key => $value) {
            if($key%4 == 0){
                $i++;
            }
            $imgs[$i][] = $value;
        }

        return $imgs;
    }

    protected function getCategories()
    {
        //get categories
        $statement = $this->connection->prepare('
            SELECT
                id,
                slug,
                name
            FROM
                category
            WHERE
                is_deleted = false
        ');

        $statement->execute();

        return $statement->fetchAll();

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            '============',
            '============',
            '============',
            '============',
            'Creating Products',
            '============',
        ]);

        $productImgs = $this->getProductImgFileNameArray();
        $categories = $this->getCategories();
        $quantity = $input->getArgument('quantity');

        //add product
        $faker = FakerFactory::create('tr_TR');

        for ($i=1; $i <= $quantity; $i++) {
            $productName = ucfirst($faker->word).' '.ucfirst($faker->word);
            $productPrice = $faker->randomFloat(2, 0, 3000);
            $tax = $i%2 ? 8 : 18;
            $description = $faker->paragraph();
            $variantTitle = array_rand($this->variants, 1);
            $cargoPrice = $faker->randomFloat(2, 7.90, 9.9);

            $connection = $this->connection;
            
            //add product
            $statement = $connection->prepare('
                INSERT INTO product
                    (name, price, tax, description, variant_title, cargo_price)
                VALUES
                    (:name, :price, :tax, :description, :variant_title, :cargo_price)
                RETURNING id
            ');

            $statement->bindValue(':name', $productName);
            $statement->bindValue(':price', $productPrice);
            $statement->bindValue(':tax', $tax);
            $statement->bindValue(':description', $description);
            $statement->bindValue(':variant_title', $variantTitle);
            $statement->bindValue(':cargo_price', $cargoPrice ? $cargoPrice : 0);
            $statement->execute();

            $productId = $statement->fetchColumn();

            $output->writeln([
                'Added Product',
                '============',
                ''
            ]);

            // Assign categories on product
            $categoryIds = array_rand($categories, 7);

            $sql = "
                INSERT INTO
                    product_category
                        (product_id, category_id) 
                VALUES ";

            foreach ($categoryIds as $key => $value) {

                $comma = $key != 0 ? ',' : '';

                $sql .= $comma."(:product_id, ". intval($value) .")";
            }

            $statement = $connection->prepare($sql);

            $statement->bindValue(':product_id', $productId);
            $statement->execute();

            $output->writeln([
                'Added Categories on Product',
                '============',
                ''
            ]);

            // Assign variants on product
            $variants = $this->variants[$variantTitle];
            //add variant
            $sql = "
                INSERT INTO
                    product_variant
                        (product_id, name, stock) 
                VALUES";

            $j = 0;
            foreach ($variants as $key => $value) {

                $comma = $j != 0 ? ',' : '';

                $sql .= $comma."(:product_id, '". $key ."', ". $value .")";

                $j++;
            }

            $statement = $connection->prepare($sql);

            $statement->bindValue(':product_id', $productId);
            $statement->execute();

            $output->writeln([
                'Added Variants on Product',
                '============',
                ''
            ]);

            //add photo
            $sql = "
                INSERT INTO
                    product_photo
                        (product_id, path) 
                VALUES ";

            for ($k=0; $k<4; $k++) { 
                $filename = $productImgs[$i%count($productImgs)+1];

                $comma = $k != 0 ? ',' : '';

                $sql .=  $comma."(:product_id, '/web/img/product/". $filename[$k] . "')";
            }

            $statement = $connection->prepare($sql);

            $statement->bindValue(':product_id', $productId);
            $statement->execute();

            $output->writeln([
                "Created product successfuly on the below.",
                "Name: $productName",
                "Price: $productPrice",
                "Tax: $tax",
                "Desc: $description",
                "Variant Title: $variantTitle",
                "Cargo Price: $cargoPrice",
            ]);
        }

        $output->writeln([
            '============',
            "Done.",
            '',
        ]);

        return 0;
    }
}
