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
                    p.created_at";
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
        
        $statement->execute();

        $total = $statement->fetchAll();

        return [
            'total' => count($total),
            'records' => $records,
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
}
