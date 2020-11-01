<?php
namespace App\Service\Web;

use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class Order
{
    use ServiceTrait;

    public function createBankTransferOrder($user, $ipAddress)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Order',
            'activity' => 'createBankTransferOrder',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {
            if (!$user) {
                throw new \InvalidArgumentException('Kullanıcı bulunamadı');
            }

            $cartDetail = $connection->executeQuery('
                SELECT
                    c.user_account_id,
                    c.cart_no order_id,
                    c.product_id product_id,
                    p.name product_name,
                    p.price product_price,
                    c.quantity product_quantity,
                    cc.name cargo_company,
                    p.cargo_price cargo_price,
                    p.price*c.quantity::float(2) as total,
                    (
                        SELECT
                            path
                        FROM
                            product_photo pp
                        WHERE
                            pp.is_deleted = false
                        AND
                            pp.product_id = p.id
                        LIMIT
                            1
                    ) product_pic,
                    p.variant_title variant_title,
                    pv.name variant_selection,
                    (
                        SELECT
                            ROW_TO_JSON(a) as billing_address
                        FROM
                             (
                                 SELECT
                                    address_name,
                                    full_name,
                                    address,
                                    county,
                                    city,
                                    mobile
                                 FROM
                                    address
                                 WHERE
                                    id = c.billing_address_id
                             ) a
                    ) billing_address_detail,
                    (
                        SELECT
                            ROW_TO_JSON(a) as shipping_address
                        FROM
                             (
                                 SELECT
                                    address_name,
                                    full_name,
                                    address,
                                    county,
                                    city,
                                    mobile
                                 FROM
                                    address
                                 WHERE
                                    id = c.shipping_address_id
                             ) a
                    ) shipping_address_detail
                FROM
                    cart c
                LEFT JOIN
                    product p ON p.id = c.product_id
                LEFT JOIN
                    cargo_company cc ON cc.id = c.cargo_company_id
                LEFT JOIN
                    product_variant pv ON c.variant_id = pv.id
                LEFT JOIN
                    address billing_address ON c.billing_address_id = billing_address.id
                LEFT JOIN
                    address shipping_address ON c.shipping_address_id = shipping_address.id
                WHERE
                    c.user_account_id = :user_account_id
                AND
                    cc.name IS NOT NULL
                AND
                    billing_address IS NOT NULL
                AND
                    shipping_address IS NOT NULL

                    ', [
                        'user_account_id' => $user->getId()
                    ]
                )->fetchAll();

            if (!$cartDetail) {
                throw new \InvalidArgumentException('Sipariş detayınız bulunamadı');   
            }

            $sql = "INSERT INTO 
                        orders (user_account_id, order_id, product_id, product_name, product_price, product_quantity, cargo_company, cargo_price, product_pic, variant_title, variant_selection, shipping_address_detail, billing_address_detail, payment_selection, order_ip, order_total_amount)
                    VALUES";

            $totalCargoPrice = 0;
            $totalOrderAmount = 0;
            $uniqueProduct = [];
            foreach ($cartDetail as $key => $value) {
                $sql .= sprintf("( %d, %d, %d, '%s', %f, %d, '%s', %f, '%s', '%s', '%s', '%s', '%s', '%s', '%s', %s)", 
                                    $value['user_account_id'],
                                    $value['order_id'],
                                    $value['product_id'],
                                    $value['product_name'],
                                    $value['product_price'],
                                    $value['product_quantity'],
                                    $value['cargo_company'],
                                    $value['cargo_price'],
                                    $value['product_pic'],
                                    $value['variant_title'],
                                    $value['variant_selection'],
                                    $value['shipping_address_detail'],
                                    $value['billing_address_detail'],
                                    'bank_transfer',
                                    $ipAddress,
                                    ':order_total_amount'
                                );

                if ($key != array_key_last($cartDetail)){
                    $sql .= ",";
                }

                if (!in_array($value['product_id'], $uniqueProduct)) {
                    $totalCargoPrice += $value['cargo_price'];
                    $uniqueProduct[] = $value['product_id'];
                }

                $totalOrderAmount += $value['product_price'] * $value['product_quantity'];

            }

            $grandTotalOrderAmount = $totalOrderAmount + $totalCargoPrice;

            $statement = $connection->prepare($sql);

            $statement->bindValue(':order_total_amount', $grandTotalOrderAmount);

            $statement->execute();

            //truncate the cart 
            $connection->executeQuery('
                DELETE FROM
                    cart
                WHERE
                    user_account_id = :user_account_id', 
                    [
                        'user_account_id' => $user->getId()
                    ]
            );

            //send notification mail
            
            


            $this->logger->info('Finalized bank transfer order', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not finalized bank transfer order', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not finalized bank transfer order', $logFullDetails);
            throw new \Exception($exception->getMessage());            
        }
    }
}
