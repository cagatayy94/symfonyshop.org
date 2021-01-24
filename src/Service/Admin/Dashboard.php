<?php
namespace App\Service\Admin;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class Dashboard
{
    use ServiceTrait;
    use AdminServiceTrait;

    public function getAll()
    {
        $this->authorize('settings_general');

        $connection = $this->connection;

        $sql = "
            SELECT
                *
            FROM
                settings ss
            WHERE
                ss.is_deleted = false
                ";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetch();
    }
}
