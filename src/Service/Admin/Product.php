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
     * @param string $description
     * @param int[] $categoryId
     * @param string $variantTitle
     * @param string[] $variantName
     * @param string[] $variantStock
     * @param int $tax
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

        $productName    = $this->formatStringParameter($productName);
        $productPrice   = $this->formatFloatParameter($productPrice);
        $cargoPrice     = $this->formatFloatParameter($cargoPrice);
        $tax            = $this->formatIntParameter($tax);

        try {
            if (!$productName) {
                throw new \InvalidArgumentException('Ürün ismi belirtilmemiş');
            }

            if (!$productPrice) {
                throw new \InvalidArgumentException('Ürün fiyatı belirtilmemiş');
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
                $statement->bindValue(':cargo_price', $cargoPrice ? $cargoPrice : 0);
                $statement->execute();

                $productId = $statement->fetchColumn();

                //add product category
                $sql = "
                    INSERT INTO
                        product_category
                            (product_id, category_id) 
                    VALUES ";

                foreach ($categoryId as $key => $value) {

                    $comma = $key != 0 ? ',' : '';

                    $sql .= $comma."(:product_id, ". intval($value) .")";
                }

                $statement = $connection->prepare($sql);

                $statement->bindValue(':product_id', $productId);
                $statement->execute();

                //add variant
                $sql = "
                    INSERT INTO
                        product_variant
                            (product_id, name, stock) 
                    VALUES";

                foreach ($variantStock as $key => $value) {

                    $comma = $key != 0 ? ',' : '';

                    $sql .= $comma."(:product_id, '". $variantName[$key] ."', ". $variantStock[$key] .")";
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
                        $foo->image_x               = 1170;
                        $foo->image_y               = 1170;
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

            $logFullDetails['productId'] = $productId;
            $this->logger->info('Created the new product', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not create the new product', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not create the new product', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Get All Products
     *
     * @param int $currentPage
     * @param int $pageCount
     * @param int $perPage
     * @param bool $isDeleted
     * @param string $name
     * @param int $productId
     * @param date $createdAtStart
     * @param date $createdAtEnd
     *
     * @throws \Exception
     */
    public function getAll($currentPage, $pageCount, $perPage, $isDeleted = false, $productName = null, $productId = null, $createdAtStart = null, $createdAtEnd = null)
    {
        $this->authorize('product_list');

        $isDeleted      = $this->formatBoolParameter($isDeleted);
        $productName    = $this->formatStringParameter($productName);
        $productId      = $this->formatIntParameter($productId);
        $createdAtStart = $this->formatDateParameter($createdAtStart, true);
        $createdAtEnd   = $this->formatDateParameter($createdAtEnd, false);

        $connection = $this->connection;

        //values sql
        $recordsSql = "
            SELECT
                id,
                name,
                price,
                tax,
                description,
                variant_title,
                cargo_price,
                view,
                created_at
            FROM
                product p
            WHERE
                1 = 1";

        if (!is_null($isDeleted)) {
            if($isDeleted){
                $recordsSql .= " AND p.is_deleted = TRUE ";
            }else{
                $recordsSql .= " AND p.is_deleted = FALSE ";
            }
        }

        if ($productName) {
            $recordsSql .= " AND LOWER(p.name) LIKE LOWER(:name) ";
        }

        if ($productId) {
            $recordsSql .= " AND p.id = :id ";
        }

        if ($createdAtStart) {
            $recordsSql .= " AND p.created_at > :createdAtStart";
        }

        if ($createdAtEnd) {
            $recordsSql .= " AND p.created_at < :createdAtEnd";
        }

        // limit sql
        $recordsSql .= '
            ORDER BY
                p.created_at
            LIMIT
                '. $perPage . '
            OFFSET
                ' . $perPage * ($currentPage - 1) . '
        ';

        $statement = $connection->prepare($recordsSql);

        if ($productName) {
            $statement->bindValue(':name', '%'.$productName.'%');
        }           

        if ($productId) {
            $statement->bindValue(':id', $productId);
        }

        if ($createdAtStart) {
            $statement->bindValue(':createdAtStart', $createdAtStart->format("Y-m-d H:i:s"));
        }

        if ($createdAtEnd) {
            $statement->bindValue(':createdAtEnd', $createdAtEnd->format("Y-m-d H:i:s"));
        }
        
        $statement->execute();

        $records = $statement->fetchAll();

        //total sql
        $totalSql = "
            SELECT
                COUNT(p.id)
            FROM
                product p
            WHERE
                1 = 1";

        if (!is_null($isDeleted)) {
            if($isDeleted){
                $totalSql .= " AND p.is_deleted = TRUE ";
            }else{
                $totalSql .= " AND p.is_deleted = FALSE ";
            }
        }

        if ($productName) {
            $totalSql .= " AND LOWER(p.name) LIKE LOWER(:name) ";
        }

        if ($productId) {
            $totalSql .= " AND p.id = :id ";
        }

        if ($createdAtStart) {
            $totalSql .= " AND p.created_at > :createdAtStart";
        }

        if ($createdAtEnd) {
            $totalSql .= " AND p.created_at < :createdAtEnd";
        }

        $statement = $connection->prepare($totalSql);


        if ($productName) {
            $statement->bindValue(':name', '%'.$productName.'%');
        }           

        if ($productId) {
            $statement->bindValue(':id', $productId);
        }

        if ($createdAtStart) {
            $statement->bindValue(':createdAtStart', $createdAtStart->format("Y-m-d H:i:s"));
        }

        if ($createdAtEnd) {
            $statement->bindValue(':createdAtEnd', $createdAtEnd->format("Y-m-d H:i:s"));
        }

        $statement->execute();

        $total = $statement->fetchColumn();

        return [
            'total' => $total,
            'records' => $records,
        ];
    }

    /**
     * Delete product
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function deleteProduct($id)
    {
        $this->authorize('product_delete');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Product',
            'activity' => 'deleteProduct',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $id = intval($id);

        try {
            if (!$id) {
                throw new \InvalidArgumentException('Id belirtilmemiş');
            }

            $sql = '
                UPDATE 
                    product
                SET
                    is_deleted = TRUE
                WHERE
                    id = :id';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':id', $id);

            $statement->execute();

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Deleted the product', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not delete the product', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not delete the product', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Undelete product
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function undeleteProduct($id)
    {
        $this->authorize('product_undelete');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Product',
            'activity' => 'undeleteProduct',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $id = intval($id);

        try {
            if (!$id) {
                throw new \InvalidArgumentException('Id belirtilmemiş');
            }

            $sql = '
                UPDATE 
                    product
                SET
                    is_deleted = FALSE
                WHERE
                    id = :id';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':id', $id);

            $statement->execute();

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Undeleted the product', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not undelete the product', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not undelete the product', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Get Product By Id
     *
     * @param int $id identifier of product
     *
     * @throws \Exception
     */
    public function getProduct($id)
    {
        $this->authorize('product_detail');

        $id  = $this->formatIntParameter($id);

        $connection = $this->connection;

        if (!$id) {
            throw new \Exception("Ürün kodu belirtilmemiş");
            
        }

        //values sql
        $sql = "
            SELECT
                p.name AS name,
                p.price price,
                p.cargo_price cargo_price,
                p.tax tax,
                p.description description,
                p.variant_title variant_title,
                p.view AS view,
                p.created_at created_at
            FROM
                product p
            WHERE
                p.is_deleted = false
            AND
                p.id = :id
        ";

        $statement = $connection->prepare($sql);        

        if ($id) {
            $statement->bindValue(':id', $id);
        }
        
        $statement->execute();

        $product = $statement->fetch();

        if (!$product) {
            throw new \Exception("Ürün bulunamadı");
            
        }

        //fetch variants
        $sql = "
            SELECT
                pv.id variant_id,
                pv.name variant_name,
                pv.stock variant_stock
            FROM
                product_variant pv
            WHERE
                product_id = :product_id
        ";

        $statement = $connection->prepare($sql);        

        if ($id) {
            $statement->bindValue(':product_id', $id);
        }
        
        $statement->execute();

        $product['variant'] = $statement->fetchAll();

        //fetch category
        $sql = "
            SELECT
                c.id category_id,
                c.slug category_slug,
                c.name category_name
            FROM
                category c
            LEFT JOIN
                product_category pc on c.id = pc.category_id
            WHERE
                c.is_deleted = false
            AND
                product_id = :product_id
        ";

        $statement = $connection->prepare($sql);        

        if ($id) {
            $statement->bindValue(':product_id', $id);
        }
        
        $statement->execute();

        $productCategory = $statement->fetchAll();

        $categories = [];
        foreach ($productCategory as $key => $value) {
            $categories[$value['category_id']] = [
                'category_slug' => $value['category_slug'],
                'category_name' => $value['category_name'],
            ];
        }

        $product['category'] = $categories;

        //fetch photo
        $sql = "
            SELECT
                ph.id,
                ph.path
            FROM
                product_photo ph
            WHERE
                ph.is_deleted = false
            AND
                ph.product_id = :product_id

        ";

        $statement = $connection->prepare($sql);        

        if ($id) {
            $statement->bindValue(':product_id', $id);
        }
        
        $statement->execute();

        $product['photo'] = $statement->fetchAll();

        return $product;
    }

    /**
     * Update the product
     *
     * @param int $id
     * @param string $productName
     * @param float $productPrice
     * @param float $cargoPrice
     * @param string $description
     * @param int[] $categoryId
     * @param string $variantTitle
     * @param string[] $variantName
     * @param string[] $variantStock
     * @param int $tax
     * @param file $files
     *
     * @throws \Exception
     */
    public function update($id, $productName, $productPrice, $cargoPrice, $description, $categoryId, $variantTitle, $variantName, $variantStock, $tax, $files)
    {
        $this->authorize('product_update');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Product',
            'activity' => 'update',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $productName    = $this->formatStringParameter($productName);
        $productPrice   = $this->formatFloatParameter($productPrice);
        $cargoPrice     = $this->formatFloatParameter($cargoPrice);
        $tax            = $this->formatIntParameter($tax);
        $id             = $this->formatIntParameter($id);

        try {

            if (!$id) {
                throw new \InvalidArgumentException('Ürün kodu belirtilmemiş');
            }

            if (!$productName) {
                throw new \InvalidArgumentException('Ürün ismi belirtilmemiş');
            }

            if (!$productPrice) {
                throw new \InvalidArgumentException('Ürün fiyatı belirtilmemiş');
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

            $connection->beginTransaction();

            try {
                //update product
                $statement = $connection->prepare('
                    UPDATE
                        product
                    SET
                        name = :name,
                        price = :price,
                        tax = :tax,
                        description = :description,
                        variant_title = :variant_title,
                        cargo_price = :cargo_price
                    WHERE
                        id = :id
                ');

                $statement->bindValue(':id', $id);
                $statement->bindValue(':name', $productName);
                $statement->bindValue(':price', $productPrice);
                $statement->bindValue(':tax', $tax);
                $statement->bindValue(':description', $description);
                $statement->bindValue(':variant_title', $variantTitle);
                $statement->bindValue(':cargo_price', $cargoPrice);
                $statement->execute();

                //update product category
                $connection->executeQuery('
                    DELETE
                    FROM
                        product_category
                    WHERE
                        product_id = :product_id
                        ', [
                            'product_id' => $id,
                        ]
                    );


                $sql = "
                    INSERT INTO
                        product_category
                            (product_id, category_id) 
                    VALUES ";

                foreach ($categoryId as $key => $value) {

                    $comma = $key != 0 ? ',' : ''; //it helps for dynamic sql

                    $sql .= $comma."(:product_id, ". intval($value) .")";
                }

                $statement = $connection->prepare($sql);

                $statement->bindValue(':product_id', $id);
                $statement->execute();

                //update variant
                $connection->executeQuery('
                    DELETE
                    FROM
                        product_variant
                    WHERE
                        product_id = :product_id
                        ', [
                            'product_id' => $id,
                        ]
                );

                $sql = "
                    INSERT INTO
                        product_variant
                            (product_id, name, stock) 
                    VALUES";

                foreach ($variantStock as $key => $value) {

                    $comma = $key != 0 ? ',' : '';

                    $sql .= $comma."(:product_id, '". $variantName[$key] ."', ". $variantStock[$key] .")";
                }

                $statement = $connection->prepare($sql);

                $statement->bindValue(':product_id', $id);
                $statement->execute();

                //add photo
                $isProductHasAnyPhoto = $connection->executeQuery('
                    SELECT
                        count(id)
                    FROM
                        product_photo
                    WHERE
                        product_id = :product_id
                    AND
                        is_deleted = FALSE
                    ', 
                    [
                        'product_id' => $id
                    ]
                )->fetchColumn();

                if (!$isProductHasAnyPhoto && !$files) {
                    throw new \InvalidArgumentException('En az 1 fotoğraf yüklemelisiniz');
                }

                if ($files) {
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
                            $foo->image_x               = 1170;
                            $foo->image_y               = 1170;
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

                    $statement->bindValue(':product_id', $id);
                    $statement->execute();
                }

                $connection->commit();
                
            } catch (Exception $e) {
                $connection->rollBack();
                throw $exception;
            }

            $logFullDetails['productId'] = $id;
            $this->logger->info('Updated the product', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not updated the product', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not update the product', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Delete product image
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function deleteProductImage($id)
    {
        $this->authorize('product_img_delete');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Product',
            'activity' => 'deleteProductImage',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $id = intval($id);

        try {
            if (!$id) {
                throw new \InvalidArgumentException('Id belirtilmemiş');
            }

            $sql = '
                UPDATE 
                    product_photo
                SET
                    is_deleted = TRUE
                WHERE
                    id = :id';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':id', $id);

            $statement->execute();

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Deleted the product image', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not delete the product image', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not delete the product image', $logFullDetails);

            throw $exception;
        }
    }
}
