<?php
namespace App\Service\Web;

use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class Order
{
    use ServiceTrait;

    public function createOrder($userId, $ipAddress, $type, $rawResult = null)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Order',
            'activity' => 'createOrder',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        try {
            if (!$userId) {
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
                        'user_account_id' => $userId
                    ]
                )->fetchAll();

            if (!$cartDetail) {
                throw new \InvalidArgumentException('Sipariş detayınız bulunamadı');   
            }

            $sql = "INSERT INTO 
                        orders (user_account_id, order_id, product_id, product_name, product_price, product_quantity, cargo_company, cargo_price, product_pic, variant_title, variant_selection, shipping_address_detail, billing_address_detail, payment_selection, order_ip, order_total_amount, raw_result)
                    VALUES";

            $totalCargoPrice = 0;
            $totalOrderAmount = 0;
            $uniqueProduct = [];
            foreach ($cartDetail as $key => $value) {
                $sql .= sprintf("( %d, %d, %d, '%s', %f, %d, '%s', %f, '%s', '%s', '%s', '%s', '%s', '%s', '%s', %s, %s)", 
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
                                    $type,
                                    $ipAddress,
                                    ':order_total_amount',
                                    ':raw_result'
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
            $statement->bindValue(':raw_result', $rawResult);

            $statement->execute();

            //truncate the cart 
            $connection->executeQuery('
                DELETE FROM
                    cart
                WHERE
                    user_account_id = :user_account_id', 
                    [
                        'user_account_id' => $userId
                    ]
            );

            //send notification mail
            
            


            $this->logger->info('Finalized order', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not finalized order', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not finalized order', $logFullDetails);
            throw new \Exception($exception->getMessage());            
        }
    }

    public function getCartDetailForIyzico($user, $ipAddress)
    {
        $connection = $this->connection;

        $cartDetail = $connection->executeQuery('
            SELECT
                c.user_account_id,
                c.cart_no order_id,
                c.product_id product_id,
                p.name product_name,
                p.price product_price,
                c.quantity product_quantity,
                p.cargo_price cargo_price,
                p.price*c.quantity::float(2) as total,
                ua.email,
                ua.name,
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
            LEFT JOIN
                user_account ua ON c.user_account_id = ua.id
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

        $totalCargoPrice = 0;
        $totalOrderAmount = 0;
        $uniqueProduct = [];
        $billingAddress = json_decode($cartDetail[0]['billing_address_detail'], true);
        $shippingAddress = json_decode($cartDetail[0]['shipping_address_detail'], true);

        $billingAddressNameArray = $this->nameParser($billingAddress['full_name']);
        $billingAddress['name'] = $billingAddressNameArray['name'];
        $billingAddress['surname'] = $billingAddressNameArray['surname'];
        unset($billingAddress['full_name']);

        $shippingAddressNameArray = $this->nameParser($shippingAddress['full_name']);
        $shippingAddress['name'] = $shippingAddressNameArray['name'];
        $shippingAddress['surname'] = $shippingAddressNameArray['surname'];
        unset($shippingAddress['full_name']);

        $nameArray = $this->nameParser($cartDetail[0]['name']);

        $name = $nameArray['name'];
        $surname = $nameArray['surname'];
        $email = $cartDetail[0]['email'];
        $userAccountId = $cartDetail[0]['user_account_id'];
        $orderId = $cartDetail[0]['order_id'];

        foreach ($cartDetail as $key => $value) {
            unset($cartDetail[$key]['billing_address_detail']);
            unset($cartDetail[$key]['shipping_address_detail']);
            unset($cartDetail[$key]['email']);
            unset($cartDetail[$key]['name']);
            unset($cartDetail[$key]['user_account_id']);
            unset($cartDetail[$key]['order_id']);

            if (!in_array($value['product_id'], $uniqueProduct)) {
                $totalCargoPrice += $value['cargo_price'];
                $uniqueProduct[] = $value['product_id'];
            }

            $totalOrderAmount += $value['total'];
        }

        $grandTotalOrderAmount = $totalOrderAmount + $totalCargoPrice;

        $result['cart_items'] = $cartDetail;
        $result['billing_address'] = $billingAddress;
        $result['shipping_address'] = $shippingAddress;
        $result['buyer'] = [
            'id' => $userAccountId,
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'city' => $billingAddress['city'],
            'country' => 'Turkey',
            'address' => $billingAddress['address'],
            'ip' => $ipAddress,
        ];
        $result['grand_total_order_amount'] = $grandTotalOrderAmount;
        $result['total_order_amount'] = $totalOrderAmount;
        $result['order_id'] = $orderId;
            
        return $result;
    }

    public function getOrderDetail($user, $orderId)
    {
        $connection = $this->connection;

        $orderDetail = $connection->executeQuery('
            SELECT
                product_name,
                product_price,
                product_quantity,
                order_total_amount,
                cargo_company,
                product_pic,
                variant_title,
                variant_selection,
                shipping_address_detail,
                billing_address_detail,
                payment_selection,
                is_approved,
                is_shipped,
                cargo_send_code,
                created_at
            FROM
                orders
            WHERE
                order_id = :order_id
            AND
                user_account_id = :user_account_id
                ', [
                    'user_account_id' => $user->getId(),
                    'order_id' => $orderId
                ]
            )->fetchAll();
        return $orderDetail;
    }
}
