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
     * Get All Orders
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
                o.order_total_amount,
                o.is_approved,
                o.is_shipped
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
                o.order_id, ua.name, o.created_at, o.order_total_amount, is_approved, is_shipped
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

    /**
     * Get Order Detail
     *
     * @param int $orderId
     *
     * @return mixed[]
     *
     * @throws \Exception
     */
    public function getOrderDetail($orderId)
    {
        $this->authorize('order_detail');

        $orderId = $this->formatIntParameter($orderId);

        if (!$orderId) {
            throw new \Exception("Sipariş bulunamadı");
        }

        $connection = $this->connection;

        $order = $connection->executeQuery('
            SELECT
                o.product_id,
                o.product_name,
                o.product_quantity,
                o.cargo_price,
                o.product_price,
                o.product_pic,
                o.order_total_amount,
                o.variant_title,
                o.variant_selection,
                o.shipping_address_detail,
                o.billing_address_detail,
                o.payment_selection,
                o.created_at,
                o.order_ip,
                o.is_approved,
                o.is_shipped,
                o.cargo_send_code,
                o.raw_result,
                o.user_account_id
            FROM
                orders o
            WHERE
                o.order_id = :order_id
        ',[
            'order_id' => $orderId
        ])->fetchAll();

        if (!$order) {
            throw new \Exception("Sipariş bulunamadı");
        }

        $userAccount = $connection->executeQuery('
            SELECT
                ua.id id,
                ua.name as name,
                ua.email email,
                ua.mobile mobile,
                ua.is_mobile_approved is_mobile_approved
            FROM
                user_account ua
            WHERE 
                ua.id = :user_account_id
        ',[
            'user_account_id' => $order[0]['user_account_id']
        ])->fetch();

        return [
            'userAccount' => $userAccount,
            'order' => $order,
        ];
    }

     /**
     * Approve The Order
     *
     * @param int $orderId
     *
     * @throws \Exception
     */
    public function approveTheOrder($orderId)
    {
        $this->authorize('approve_the_order');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Order',
            'activity' => 'approveTheOrder',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $orderId = intval($orderId);

        try {
            if (!$orderId) {
                throw new \InvalidArgumentException('Id belirtilmemiş');
            }

            $sql = '
                UPDATE 
                    orders
                SET
                    is_approved = TRUE
                WHERE
                    order_id = :order_id';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':order_id', $orderId);

            $statement->execute();

            $logFullDetails['activityId'] = $orderId;
            $this->logger->info('Approved the order', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not approved the order', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not approved the order', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Ship The Order
     *
     * @param int $orderId
     *
     * @throws \Exception
     */
    public function shipTheOrder($orderId, $cargoSendCode)
    {
        $this->authorize('ship_the_order');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Order',
            'activity' => 'shipTheOrder',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $orderId = intval($orderId);

        try {
            if (!$orderId) {
                throw new \InvalidArgumentException('Id belirtilmemiş');
            }

            $sql = '
                UPDATE 
                    orders
                SET
                    is_shipped = TRUE,
                    cargo_send_code = :cargo_send_code
                WHERE
                    order_id = :order_id';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':order_id', $orderId);
            $statement->bindValue(':cargo_send_code', $cargoSendCode);

            $statement->execute();

            $logFullDetails['activityId'] = $orderId;
            $this->logger->info('Shipped the order', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not shipped the order', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not shipped the order', $logFullDetails);

            throw $exception;
        }
    }
}
