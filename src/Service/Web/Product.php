<?php
namespace App\Service\Web;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Admin\AdminAccount;
use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class Product
{
    use ServiceTrait;

    /**
     * Get All Products
     *
     * @param int $currentPage
     * @param int $pageCount
     * @param int $perPage
     * @param string $name
     * @param int $productId
     * @param date $createdAtStart
     * @param date $createdAtEnd
     *
     * @throws \Exception
     */
    public function getAll($currentPage, $pageCount, $perPage, $order, $menuId = null, $categoryId = null, $search = null, $maxPrice = null, $minPrice = null)
    {
        $connection = $this->connection;

        //values sql
        $recordsSql = "
            SELECT
                p.id,
                p.name,
                p.price,
                json_agg(pp.path) photo,
                json_agg(pc.category_id)
            FROM
                product p
            LEFT JOIN
                product_category pc on p.id = pc.product_id
            LEFT JOIN
                product_photo pp on p.id = pp.product_id
            LEFT JOIN
                category_menu cm on pc.category_id = cm.category_id
            WHERE
                p.is_deleted = false";

        if ($categoryId) {
            $recordsSql.="
                AND
                    pc.category_id = :category_id";
        }

        if ($menuId) {
            $recordsSql.="
                AND
                    cm.menu_id = :menu_id";
        }

        if ($search) {
            $recordsSql.="
                AND
                    LOWER(p.name) LIKE LOWER(:search) 
                    OR
                    LOWER(p.description) LIKE (:search)";
        }

        if ($maxPrice) {
            $recordsSql.="
                AND
                    p.price <= :max_price";
        }

        if ($minPrice) {
            $recordsSql.="
                AND
                    p.price >= :min_price";
        }

        $recordsSql.="
            GROUP BY
                p.id";

        if ($order == 'price') {
            $recordsSql .= "
                ORDER BY
                    p.price";
        }elseif($order == 'price-desc'){
            $recordsSql .= "
                ORDER BY
                    p.price DESC ";
        }else{
            $recordsSql .= "
                ORDER BY
                    p.created_at DESC";
        }

        // limit sql
        $recordsSql .= '
            LIMIT
                '. $perPage . '
            OFFSET
                ' . $perPage * ($currentPage - 1) . '
        ';

        $statement = $connection->prepare($recordsSql);

        if ($categoryId) {
            $statement->bindValue('category_id', $categoryId);
        }

        if ($menuId) {
            $statement->bindValue('menu_id', $menuId);
        }

        if ($search) {
            $statement->bindValue('search', '%'.$search.'%');
        }

        if ($maxPrice) {
            $statement->bindValue('max_price', intval($maxPrice));
        }

        if ($minPrice) {
            $statement->bindValue('min_price', intval($minPrice));
        }
        
        $statement->execute();

        $records = $statement->fetchAll();

        //total sql
        $totalSql = "
            SELECT
                count(p.id)
            FROM
                product p
            LEFT JOIN
                product_category pc on p.id = pc.product_id
            LEFT JOIN
                product_photo pp on p.id = pp.product_id
            LEFT JOIN
                category_menu cm on pc.category_id = cm.category_id
            WHERE
                p.is_deleted = false";

        if ($categoryId) {
            $totalSql.="
                AND
                    pc.category_id = :category_id";
        }

        if ($menuId) {
            $totalSql.="
                AND
                    cm.menu_id = :menu_id";
        }

        if ($search) {
            $totalSql.="
                AND
                    LOWER(p.name) LIKE LOWER(:search) 
                    OR
                    LOWER(p.description) LIKE (:search)";
        }

        if ($maxPrice) {
            $totalSql.="
                AND
                    p.price <= :max_price";
        }

        if ($minPrice) {
            $totalSql.="
                AND
                    p.price >= :min_price";
        }

        $totalSql.="
            GROUP BY
                p.id "; 

        $statement = $connection->prepare($totalSql);

        if ($categoryId) {
            $statement->bindValue('category_id', $categoryId);
        }

        if ($menuId) {
            $statement->bindValue('menu_id', $menuId);
        }

        if ($search) {
            $statement->bindValue('search', '%'.$search.'%');
        }

        if ($maxPrice) {
            $statement->bindValue('max_price', intval($maxPrice));
        }

        if ($minPrice) {
            $statement->bindValue('min_price', intval($minPrice));
        }
        
        $statement->execute();

        $products = [];
        foreach ($records as $key => $value) {
            $products[$key] = $value;
            $products[$key]['slug'] = $this->createSlugFromStringForProductName($value['name']);
        }

        $total = $statement->fetchAll();
        
        return [
            'total' => count($total),
            'records' => $products,
        ];
    }

    public function getMaxPrice()
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                max(price)
            FROM
                product
            WHERE
                is_deleted = false";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchColumn();
    }

    public function getDetail($id)
    {
        $connection = $this->connection;

        $sql = "
            UPDATE
                product p
            SET
                view = view+1
            WHERE
                id = :product_id;";

        $statement = $connection->prepare($sql);

        $statement->bindValue('product_id', $id);

        $statement->execute();

        $sql = "
            SELECT
                p.name,
                price,
                created_at,
                tax,
                description,
                variant_title,
                cargo_price,
                view,
                json_agg(pv.name) variant_name,
                json_agg(pv.stock) variant_stock,
                json_agg(pv.id) variant_id,
                (
                    SELECT
                        ROUND(AVG(pc.rate))
                    FROM
                        product_comment pc
                    WHERE
                        pc.product_id = :product_id
                ) rate,
                (
                    SELECT
                        count(id)
                    FROM
                        product_comment
                    WHERE
                        product_id = :product_id
                ) comment_count
            FROM
                product p
            LEFT JOIN
                product_variant pv on p.id = pv.product_id
            WHERE
                p.is_deleted = false
            AND
                p.id = :product_id
            GROUP BY
                p.id;";

        $statement = $connection->prepare($sql);

        $statement->bindValue('product_id', $id);

        $statement->execute();

        $product = $statement->fetch();

        if (!$product) {
            return false;
        }

        //get product photos
        $sql = "
            SELECT
                pp.path
            FROM
                product p
            LEFT JOIN
                product_photo pp on p.id = pp.product_id
            WHERE
                p.is_deleted = false
            AND
                pp.is_deleted = false
            AND
                p.id = :product_id";

        $statement = $connection->prepare($sql);

        $statement->bindValue('product_id', $id);

        $statement->execute();

        $product['photos'] = $statement->fetchAll(\PDO::FETCH_COLUMN);

        return $product;
    }

    public function addFavorite($productId, $userId)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Product',
            'activity' => 'addFavorite',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $productId = (int) $productId;
        $userId = (int) $userId;

        try {
            if (!$productId) {
                throw new \InvalidArgumentException('Ürün bulunamadı');
            }

            if (!$userId) {
                throw new \InvalidArgumentException('Kullanıcı bulunamadı');
            }

            $sql = "
                SELECT 
                    id
                FROM
                    user_account_favorite
                WHERE
                    product_id = :product_id
                AND
                    user_account_id = :user_account_id";

            $statement = $connection->prepare($sql);

            $statement->bindValue('product_id', $productId);
            $statement->bindValue('user_account_id', $userId);

            $statement->execute();

            $favoriteExist = $statement->fetch();

            if ($favoriteExist) {
                throw new \InvalidArgumentException("Ürün zaten favorilere ekli durumda");
            }

            $sql = "
                INSERT INTO user_account_favorite
                    (product_id, user_account_id)
                VALUES
                    (:product_id, :user_account_id)";

            $statement = $connection->prepare($sql);

            $statement->bindValue('product_id', $productId);
            $statement->bindValue('user_account_id', $userId);

            $statement->execute();

            $this->logger->info('Added favorite', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not added favorite', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not added favorite', $logFullDetails);
            throw new \Exception("Şu an bu talebinizi gerçekleştiremiyoruz lütfen daha sonra tekrar deneyiniz.");            
        }
    }

    public function getProductComments($productId, $offset = 0)
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                pc.id,
                pc.created_at,
                pc.comment,
                pc.rate,
                ua.name,
                ua.id,
                (SELECT
                    count(o.id) > 0
                FROM
                    orders o
                WHERE
                    user_account_id = ua.id
                AND
                    product_id = :product_id
                ) buyed
            FROM
                product_comment pc
            LEFT JOIN
                user_account ua ON pc.user_id = ua.id
            WHERE
                pc.product_id = :product_id
            ORDER BY
                pc.id
            LIMIT
                10
            OFFSET
                :offset";

        $statement = $connection->prepare($sql);
        $statement->bindValue('product_id', $productId);
        $statement->bindValue('offset', $offset);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function addComment($productId, $userId, $rate, $ipAddress, $comment = null)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Product',
            'activity' => 'addComment',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $productId = (int) $productId;
        $userId = (int) $userId;
        $rate = (int) $rate;

        try {
            if (!$productId) {
                throw new \InvalidArgumentException('Ürün bulunamadı');
            }

            if (!$userId) {
                throw new \InvalidArgumentException('Kullanıcı bulunamadı');
            }

            if (!$rate) {
                throw new \InvalidArgumentException('Puan bulunamadı');
            }

            $sql = "
                SELECT 
                    id
                FROM
                    product_comment
                WHERE
                    product_id = :product_id
                AND
                    user_id = :user_id";

            $statement = $connection->prepare($sql);

            $statement->bindValue('product_id', $productId);
            $statement->bindValue('user_id', $userId);

            $statement->execute();

            $commentExist = $statement->fetch();

            if ($commentExist) {
                throw new \InvalidArgumentException("Aynı ürüne iki kere yorum yapamazsınız, yorumunuzu silip tekrar yorum yapabilirsiniz");
            }

            $sql = "
                INSERT INTO product_comment
                    (product_id, user_id, ip_address, comment, rate)
                VALUES
                    (:product_id, :user_id, :ip_address, :comment, :rate)";

            $statement = $connection->prepare($sql);

            $statement->bindValue('product_id', $productId);
            $statement->bindValue('user_id', $userId);
            $statement->bindValue('ip_address', $ipAddress);
            $statement->bindValue('comment', $comment);
            $statement->bindValue('rate', $rate);

            $statement->execute();

            $data = $connection->executeQuery(
                '
                    SELECT
                        count(o.id) > 0 as buyed,
                        (
                            SELECT
                                name
                            FROM
                                user_account
                            WHERE
                                id=:user_id
                        )
                    FROM
                        orders o
                    LEFT JOIN
                        user_account ua ON ua.id = o.user_account_id
                    WHERE
                        o.user_account_id = :user_id
                    AND
                        o.product_id = :product_id
                ', [
                    'product_id' => $productId , 
                    'user_id' => $userId
                    ])->fetch();

            $this->logger->info('Added comment', $logFullDetails);

            return $data;
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not added comment', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not added comment', $logFullDetails);
            throw new \Exception("Şu an bu talebinizi gerçekleştiremiyoruz lütfen daha sonra tekrar deneyiniz.");            
        }
    }
}
