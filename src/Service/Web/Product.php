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

    public function getAll()
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                *
            FROM
                product p
            LEFT JOIN
                product_photo pp ON pp.id = (
                                                SELECT
                                                    id
                                                FROM
                                                    product_photo pp
                                                WHERE
                                                    pp.is_deleted = FALSE AND pp.product_id =p.id
                                                LIMIT 1
                                            )
            WHERE
                p.is_deleted = FALSE

        ";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
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
