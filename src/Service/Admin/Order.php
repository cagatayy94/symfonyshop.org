<?php
namespace App\Service\Admin;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class Order
{
    use ServiceTrait;
    use AdminServiceTrait;

    /**
     * Get All Products
     *
     * @param int $currentPage
     * @param int $pageCount
     * @param int $perPage
     * @param date $createdAtStart
     * @param date $createdAtEnd
     * @param string $name
     * @param int $orderId
     *
     * @return mixed[]
     *
     * @throws \Exception
     */
    public function getAll($currentPage, $pageCount, $perPage, $createdAtStart = null, $createdAtEnd = null, $name = null, $orderId = null)
    {
        $this->authorize('order_list');

        $name           = $this->formatStringParameter($name);
        $orderId        = $this->formatIntParameter($orderId);
        $createdAtStart = $this->formatDateParameter($createdAtStart, true);
        $createdAtEnd   = $this->formatDateParameter($createdAtEnd, false);

        $connection = $this->connection;

        //values sql
        $recordsSql = "
            SELECT
                COUNT(*) OVER() AS total_count,
                o.order_id,
                ua.name,
                o.created_at,
                o.order_total_amount
            FROM
                orders o
            LEFT JOIN
                user_account ua ON o.user_account_id = ua.id
            WHERE 
                1 = 1";

        if ($createdAtStart) {
            $recordsSql .= " AND o.created_at > :createdAtStart";
        }

        if ($createdAtEnd) {
            $recordsSql .= " AND o.created_at < :createdAtEnd";
        }

        if ($name) {
            $recordsSql .= " AND LOWER(ua.name) LIKE LOWER(:name) ";
        }

        if ($orderId) {
            $recordsSql .= " AND o.order_id = :id ";
        }

        // limit sql
        $recordsSql .= '
            GROUP BY
                o.order_id, ua.name, o.created_at, o.order_total_amount
            ORDER BY
                o.order_id DESC
            LIMIT
                '. $perPage . '
            OFFSET
                ' . $perPage * ($currentPage - 1) . '
        ';

        $statement = $connection->prepare($recordsSql);         

        if ($orderId) {
            $statement->bindValue(':id', $orderId);
        }

        if ($createdAtStart) {
            $statement->bindValue(':createdAtStart', $createdAtStart->format("Y-m-d H:i:s"));
        }

        if ($createdAtEnd) {
            $statement->bindValue(':createdAtEnd', $createdAtEnd->format("Y-m-d H:i:s"));
        }

        if ($name) {
            $statement->bindValue(':name', '%'.$name.'%');
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
