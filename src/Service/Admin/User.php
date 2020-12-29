<?php
namespace App\Service\Admin;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Web\UserAccount;
use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class User
{
    use ServiceTrait;
    use AdminServiceTrait;

    /**
     * Get All Users
     *
     * @param int $currentPage
     * @param int $perPage
     * @param date $createdAtStart
     * @param date $createdAtEnd
     * @param string $name
     *
     * @return mixed[]
     *
     * @throws \Exception
     */
    public function getAll($currentPage, $perPage, $email = null)
    {
        $this->authorize('user_list');

        $email = $this->formatStringParameter($email);

        $connection = $this->connection;

        //values sql
        $recordsSql = "
            SELECT
                COUNT(*) OVER() as total_count,
                email,
                is_deleted,
                mobile,
                name,
                is_email_approved,
                is_mobile_approved,
                is_unsubscribe,
                created_at
            FROM
                user_account ua
            WHERE
                1 = 1";

        if ($email) {
            $recordsSql .= " AND LOWER(ua.email) LIKE LOWER(:name) ";
        }

        // limit sql
        $recordsSql .= '
            ORDER BY
                ua.name
            LIMIT
                '. $perPage . '
            OFFSET
                ' . $perPage * ($currentPage - 1) . '
        ';

        $statement = $connection->prepare($recordsSql);

        if ($email) {
            $statement->bindValue(':email', '%'.$email.'%');
        }  
        
        $statement->execute();

        $records = $statement->fetchAll();
        $total = isset($records[0]) ? $records[0]['total_count'] : 0;

        return [
            'total' => $total,
            'records' => $records,
        ];
    }
}
