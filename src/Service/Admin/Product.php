<?php
namespace App\Service\Admin;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Admin\AdminAccount;
use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;
use App\Sdk\Upload;

class Product
{
    use ServiceTrait;
    use AdminServiceTrait;

    /**
     * Create new product
     *
     * @param string $productName
     * @param float $productPrice
     * @param float $cargoPrice
     * @param int[] $category
     * @param string[] $variantTitle
     * @param string[] $variantStock
     * @param file $files
     *
     * @throws \Exception
     */
    public function create($productName, $productPrice, $cargoPrice, $description, $categoryId, $variantTitle, $variantName, $variantStock, $tax, $files)
    {
        $this->authorize('product_create');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Product',
            'activity' => 'create',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $productName = trim($productName);
        $productPrice = floatval($productPrice);
        $cargoPrice = floatval($cargoPrice);
        $tax = intval($tax);

        try {
            if (!$productName) {
                throw new \InvalidArgumentException('Ürün ismi belirtilmemiş');
            }

            if (!$productPrice) {
                throw new \InvalidArgumentException('Ürün fiyatı belirtilmemiş');
            }

            if (!$cargoPrice) {
                throw new \InvalidArgumentException('Kargo fiyatı belirtilmemiş');
            }

            if (!$categoryId) {
                throw new \InvalidArgumentException('En az bir kategori seçiniz');
            }

            if (!$variantTitle) {
                throw new \InvalidArgumentException('Varyant başlığı belirtilmemiş');
            }

            if (!$tax) {
                throw new \InvalidArgumentException('Vergi Oranı belirtilmemiş');
            }

            foreach ($variantStock as $key => $value) {
                $variantStock[$key] = intval($value);
                if (!$variantStock[$key]) {
                    throw new \InvalidArgumentException('Stok adedi belirtilmemiş');
                }

                if ($value && !$variantName[$key]) {
                    throw new \InvalidArgumentException('Varyant ismi belirtilmemiş');
                }
            }

            if (!$description) {
                throw new \InvalidArgumentException('Açıklama belirtiniz');
            }

            if (!$files) {
                throw new \InvalidArgumentException('En az 1 fotoğraf yüklemelisiniz');
            }

            $connection->beginTransaction();

            try {

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
                $statement->bindValue(':cargo_price', $cargoPrice);
                $statement->execute();

                $productId = $statement->fetchColumn();

                //add product category
                $sql = "
                    INSERT INTO
                        product_category
                            (product_id, category_id) 
                    VALUES
                        (:product_id, ". intval($categoryId[0]) .")";

                foreach ($categoryId as $key => $value) {

                    if ($key == 0) {
                        continue;
                    }

                    $sql.=", (". intval($productId) .", ". intval($value) .")";
                }

                $statement = $connection->prepare($sql);

                $statement->bindValue(':product_id', $productId);
                $statement->execute();

                //add variant
                foreach ($variantStock as $key => $value) {

                    $sql = "
                        INSERT INTO
                            product_variant
                                (product_id, name, stock) 
                        VALUES
                            (:product_id, '". $variantName[0] ."', ". intval($variantStock[0]) .")";

                        if ($key == 0) {
                            continue;
                        }

                    $sql.=", (:product_id, '". $variantName[$key] ."', ". $value .")";
                }

                $statement = $connection->prepare($sql);

                $statement->bindValue(':product_id', $productId);
                $statement->execute();

                //add photo
                $sql = "
                    INSERT INTO
                        product_photo
                            (product_id, path) 
                    VALUES ";

                foreach ($files as $key => $value) {
                    $filename = md5(time()).rand(1, 10000);
                    //Save Bank Logo
                    $foo = new upload($value,"tr-TR");
                    if ($foo->uploaded) {
                        // save uploaded image with a new name,
                        $foo->file_new_name_body    = $filename;
                        $foo->file_overwrite        = true;
                        $foo->image_resize          = true;
                        $foo->allowed               = array("image/*");
                        $foo->image_convert         ='png' ;
                        $foo->image_resize          = true;
                        $foo->image_ratio_fill      = true;
                        $foo->image_y               = 30;
                        $foo->process($_SERVER["DOCUMENT_ROOT"].'/web/img/product');
                        if ($foo->processed) {
                            $foo->clean();
                        } else {
                            throw new \InvalidArgumentException($foo->error);
                        }
                    }

                    $comma = $key != 0 ? ',' : '';

                    $sql .=  $comma."(:product_id, '/web/img/product/". $filename . ".png')";
                }

                $statement = $connection->prepare($sql);

                $statement->bindValue(':product_id', $productId);
                $statement->execute();

                $connection->commit();
                
            } catch (Exception $e) {
                $connection->rollBack();
                throw $exception;
            }

            $adminId = $connection->lastInsertId();

            $logFullDetails['activityId'] = $adminId;
            $this->logger->info('Created the admin account', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not create admin account', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not create admin account', $logFullDetails);

            throw $exception;
        }
    }
}
