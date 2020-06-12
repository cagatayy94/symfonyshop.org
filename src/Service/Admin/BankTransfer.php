<?php
namespace App\Service\Admin;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Admin\AdminAccount;
use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class BankTransfer
{
    use ServiceTrait;
    use AdminServiceTrait;

    public function getAll($currentPage, $pageCount, $perPage, $name = null, $email = null, $mobile = null, $bankId = null, $isDeleted = false, $isApproved = false, $startDate = null, $endDate = null)
    {
        $this->authorize('bank_transfer_list');

        $name = $this->formatStringParameter($name);
        $email = $this->formatStringParameter($email);
        $mobile = $this->formatMobileNumber($mobile);
        $bankId = $this->formatIntParameter($bankId);
        $isDeleted = $this->formatBoolParameter($isDeleted);
        $isApproved = $this->formatBoolParameter($isApproved);
        $startDate = $this->formatDateParameter($startDate, true);
        $endDate = $this->formatDateParameter($endDate, false);

        $connection = $this->connection;

        //values sql
        $recordsSql = "
            SELECT
                o.id,
                o.name,
                o.email,
                o.mobile,
                ba.name bank_name,
                o.message,
                o.is_deleted,
                o.is_approved,
                o.created_at
            FROM
                order_notice o
            LEFT JOIN
                bank_accounts ba ON ba.id = o.bank_id
            WHERE
                1 = 1";

        if ($name) {
            $recordsSql .= " AND LOWER(o.name) LIKE LOWER(:name) ";
        }

        if ($email) {
            $recordsSql .= " AND LOWER(o.email) LIKE LOWER(:email) ";
        }

        if ($mobile) {
            $recordsSql .= " AND LOWER(o.mobile) LIKE LOWER(:mobile) ";
        }

        if (!is_null($bankId)) {
            $recordsSql .= " AND o.bank_id = :bankId ";
        }

        if (!is_null($isDeleted)) {
            if($isDeleted){
                $recordsSql .= " AND o.is_deleted = TRUE ";
            }else{
                $recordsSql .= " AND o.is_deleted = FALSE ";
            }
        }

        if (!is_null($isApproved)) {
            if($isApproved){
                $recordsSql .= " AND o.is_approved = TRUE ";
            }else{
                $recordsSql .= " AND o.is_approved = FALSE ";
            }
        }

        if ($startDate) {
            $recordsSql .= " AND o.created_at > :startDate";
        }

        if ($endDate) {
            $recordsSql .= " AND o.created_at < :endDate";
        }

        // limit sql
        $recordsSql .= '
            ORDER BY
                o.created_at
            LIMIT
                '. $perPage . '
            OFFSET
                ' . $perPage * ($currentPage - 1) . '
        ';

        $statement = $connection->prepare($recordsSql);

        if ($name) {
            $statement->bindValue(':name', '%'.$name.'%');
        }

        if ($email) {
            $statement->bindValue(':email', '%'.$email.'%');
        }

        if ($mobile) {
            $statement->bindValue(':mobile', '%'.$mobile.'%');
        }                

        if ($bankId) {
            $statement->bindValue(':bankId', $bankId);
        }

        if ($startDate) {
            $statement->bindValue(':startDate', $startDate->format("Y-m-d H:i:s"));
        }

        if ($endDate) {
            $statement->bindValue(':endDate', $endDate->format("Y-m-d H:i:s"));
        }
        
        $statement->execute();

        $records = $statement->fetchAll();

        //total sql
        $totalSql = "
            SELECT
                COUNT(o.id)
            FROM
                order_notice o
            LEFT JOIN
                bank_accounts ba ON ba.id = o.bank_id
            WHERE
                1 = 1";

        if ($name) {
            $totalSql .= " AND LOWER(o.name) LIKE LOWER(:name) ";
        }

        if ($email) {
            $totalSql .= " AND LOWER(o.email) LIKE LOWER(:email) ";
        }

        if ($mobile) {
            $totalSql .= " AND LOWER(o.mobile) LIKE LOWER(:mobile) ";
        }

        if (!is_null($bankId)) {
            $totalSql .= " AND o.bank_id = :bankId ";
        }

        if (!is_null($isDeleted)) {
            if($isDeleted){
                $totalSql .= " AND o.is_deleted = TRUE ";
            }else{
                $totalSql .= " AND o.is_deleted = FALSE ";
            }
        }

        if (!is_null($isApproved)) {
            if($isApproved){
                $totalSql .= " AND o.is_approved = TRUE ";
            }else{
                $totalSql .= " AND o.is_approved = FALSE ";
            }
        }

        if ($startDate) {
            $totalSql .= " AND o.created_at > :startDate";
        }

        if ($endDate) {
            $totalSql .= " AND o.created_at < :endDate";
        }

        $statement = $connection->prepare($totalSql);

        if ($name) {
            $statement->bindValue(':name', '%'.$name.'%');
        }

        if ($email) {
            $statement->bindValue(':email', '%'.$email.'%');
        }

        if ($mobile) {
            $statement->bindValue(':mobile', '%'.$mobile.'%');
        }                

        if ($bankId) {
            $statement->bindValue(':bankId', $bankId);
        }

        if ($startDate) {
            $statement->bindValue(':startDate', $startDate->format("Y-m-d H:i:s"));
        }

        if ($endDate) {
            $statement->bindValue(':endDate', $endDate->format("Y-m-d H:i:s"));
        }
        
        $statement->execute();

        $total = $statement->fetchColumn();

        return [
            'total' => $total,
            'records' => $records,
        ];
    }

    /**
     * Delete money order
     *
     * @param int $id
     * @throws \Exception
     */
    public function deleteMoneyOrder($id)
    {
        $this->authorize('bank_transfer_delete');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'BankTransfer',
            'activity' => 'deleteMoneyOrder',
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
                    order_notice
                SET
                    is_deleted = TRUE
                WHERE
                    id = :id';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':id', $id);

            $statement->execute();

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Deleted the money order', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not delete the money order', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not delete the money order', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Undelete money order
     *
     * @param int $id
     * @throws \Exception
     */
    public function undeleteMoneyOrder($id)
    {
        $this->authorize('bank_transfer_undelete');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'BankTransfer',
            'activity' => 'undeleteMoneyOrder',
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
                    order_notice
                SET
                    is_deleted = FALSE
                WHERE
                    id = :id';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':id', $id);

            $statement->execute();

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Undeleted the money order', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not undelete the money order', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not undelete the money order', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Update money order
     *
     * @param int $id
     * @throws \Exception
     */
    public function updateMoneyOrder($id, $type)
    {
        $this->authorize('bank_transfer_update');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'BankTransfer',
            'activity' => 'approveMoneyOrder',
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
                    order_notice
                SET';

            if ($type == 'approve') {

               $sql .= ' is_approved = TRUE';

            }else if($type == 'unapprove'){

               $sql .= ' is_approved = FALSE';

            }else{
                throw new \InvalidArgumentException('Bir sorun oluştu');
            }

            $sql.=' 
                WHERE
                    id = :id';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':id', $id);

            $statement->execute();

            $logFullDetails['activityId'] = $id;

            $this->logger->info('Updated the money order', $logFullDetails);

        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not updated the money order', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not updated the money order', $logFullDetails);

            throw $exception;
        }
    }
}
