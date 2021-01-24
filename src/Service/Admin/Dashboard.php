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

    public function getData()
    {
        $this->authorize('site_dashboard_show');

        $connection = $this->connection;

        $orderNoticeCountSql = "
            SELECT
                count(id)
            FROM
                order_notice
            WHERE
                is_deleted = false
            AND
                is_approved = false
                ";

        $statement = $connection->prepare($orderNoticeCountSql);

        $statement->execute();

        $result['orderNoticeCount'] = $statement->fetchColumn();

        $ordersCountSql = "
            SELECT
                count(id)
            FROM
                orders
            WHERE
                is_approved = false
                ";

        $statement = $connection->prepare($ordersCountSql);

        $statement->execute();

        $result['ordersCount'] = $statement->fetchColumn();

        $userCountSql = "
            SELECT
                count(id)
            FROM
                user_account
            WHERE
                is_deleted = false
            AND
                is_email_approved = false
    
                ";

        $statement = $connection->prepare($userCountSql);

        $statement->execute();

        $result['userCount'] = $statement->fetchColumn();

        return $result;
    }
}
