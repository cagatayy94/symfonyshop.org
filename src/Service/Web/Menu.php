<?php
namespace App\Service\Web;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Admin\AdminAccount;
use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class Menu
{
    use ServiceTrait;

    public function getAll()
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                id,
                slug,
                name
            FROM
                menu
            WHERE
                is_deleted = FALSE";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function getFromSlug($slug)
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                id,
                slug,
                name
            FROM
                menu
            WHERE
                is_deleted = FALSE
            AND
                slug = :slug";

        $statement = $connection->prepare($sql);

        $statement->bindValue('slug', $slug);

        $statement->execute();

        return $statement->fetch();
    }

    public function getMenuCategory($menuId)
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                c.id,
                c.slug,
                c.name
            FROM
                category c
            LEFT JOIN
                category_menu cm ON c.id = cm.category_id AND cm.menu_id = :menu_id
            WHERE
                c.is_deleted = FALSE
            AND
                menu_id IS NOT NULL";

        $statement = $connection->prepare($sql);

        $statement->bindValue('menu_id', $menuId);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function getAllCategory()
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                c.id,
                c.slug,
                c.name
            FROM
                category c
            WHERE
                c.is_deleted = false";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }
}
