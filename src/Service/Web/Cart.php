<?php
namespace App\Service\Web;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Admin\AdminAccount;
use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class Cart
{
    use ServiceTrait;

    public function addCart($productId, $variantId, $userId)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Cart',
            'activity' => 'addCart',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $productId = (int) $productId;
        $userId = (int) $userId;
        $variantId = (int) $variantId;

        try {
            if (!$productId) {
                throw new \InvalidArgumentException('Ürün bulunamadı');
            }

            if (!$userId) {
                throw new \InvalidArgumentException('Kullanıcı bulunamadı');
            }

            if (!$variantId) {
                throw new \InvalidArgumentException('Variant seçiniz');
            }

            $sql = "
                SELECT 
                    id
                FROM
                    cart
                WHERE
                    product_id = :product_id
                AND
                    user_account_id = :user_account_id
                AND
                    variant_id = :variant_id";

            $statement = $connection->prepare($sql);

            $statement->bindValue('product_id', $productId);
            $statement->bindValue('user_account_id', $userId);
            $statement->bindValue('variant_id', $variantId);

            $statement->execute();

            $itemExist = $statement->fetch();

            if ($itemExist) {
                $sql = "
                    UPDATE 
                        cart
                    SET
                        quantity = quantity + 1
                    WHERE
                        product_id = :product_id
                    AND
                        user_account_id = :user_account_id
                    AND
                        variant_id = :variant_id";

                $statement = $connection->prepare($sql);

                $statement->bindValue('product_id', $productId);
                $statement->bindValue('user_account_id', $userId);
                $statement->bindValue('variant_id', $variantId);

                $statement->execute();
                

            }else{
                $sql = "
                    INSERT INTO cart
                        (product_id, user_account_id, variant_id)
                    VALUES
                        (:product_id, :user_account_id, :variant_id)
                    RETURNING id";

                $statement = $connection->prepare($sql);

                $statement->bindValue('product_id', $productId);
                $statement->bindValue('user_account_id', $userId);
                $statement->bindValue('variant_id', $variantId);

                $statement->execute();

                $cartNo = $statement->fetchColumn();

                $sql = "
                    UPDATE 
                        cart
                    SET
                        cart_no = :cart_no
                    WHERE
                        user_account_id = :user_account_id";

                $statement = $connection->prepare($sql);

                $statement->bindValue('cart_no', $cartNo);
                $statement->bindValue('user_account_id', $userId);

                $statement->execute();
            }

            $this->logger->info('Added cart', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not added cart', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not added cart', $logFullDetails);
            throw new \Exception("Bir sorun oluştu");            
        }
    }

    public function getCart($productId)
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                c.id,
                c.quantity quantity,

                p.id product_id,
                p.name product_name,
                p.variant_title variant_title,
                p.price product_price,
                p.cargo_price cargo_price,

                pv.name variant_name,

                p.price*c.quantity::float(2) as total,

                (
                    SELECT
                        path
                    FROM
                        product_photo
                    WHERE
                        product_id = c.product_id
                    LIMIT 1
                ) path
            FROM
                cart c
            LEFT JOIN
                product p ON p.id = c.product_id
            LEFT JOIN
                product_variant pv on c.variant_id = pv.id
            WHERE
                user_account_id = :user_account_id
            GROUP BY
                c.id, p.id, p.name, c.quantity, p.cargo_price, p.variant_title, p.price, pv.name";

        $statement = $connection->prepare($sql);
        $statement->bindValue('user_account_id', $productId);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function getCartTotalAndQuantity($productId)
    {
        $connection = $this->connection;

        $sql = "
            WITH
                 Cart AS
                     (
                        SELECT
                            c.id,
                            c.quantity quantity,

                            p.name product_name,
                            p.variant_title variant_title,
                            p.price product_price,

                            pv.name variant_name,

                            p.price*c.quantity::float(2) as total,

                            (
                                SELECT
                                    path
                                FROM
                                    product_photo
                                WHERE
                                    product_id = c.product_id
                                LIMIT 1
                            ) path
                        FROM
                            cart c
                        LEFT JOIN
                            product p ON p.id = c.product_id
                        LEFT JOIN
                            product_variant pv on c.variant_id = pv.id
                        WHERE
                            user_account_id = :user_account_id
                        GROUP BY
                            c.id, p.name, c.quantity, p.variant_title, p.price, pv.name
                    )
            SELECT
                sum(total)::float(2),
                count(id)::int
            FROM
                Cart";

        $statement = $connection->prepare($sql);
        $statement->bindValue('user_account_id', $productId);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function remove($userId, $cartId)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Cart',
            'activity' => 'remove',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $userId = (int) $userId;
        $cartId = (int) $cartId;

        try {
            if (!$userId) {
                throw new \InvalidArgumentException('Kullanıcı bulunamadı');
            }

            if (!$cartId) {
                throw new \InvalidArgumentException('Sepet bulunamadı');
            }

            $sql = "
                DELETE FROM 
                    Cart
                WHERE 
                    id = :id 
                AND
                    user_account_id = :user_account_id";

            $statement = $connection->prepare($sql);

            $statement->bindValue('id', $cartId);
            $statement->bindValue('user_account_id', $userId);

            $statement->execute();

            $this->logger->info('Removed from cart', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not removed from cart', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not removed from cart', $logFullDetails);
            throw new \Exception("Bir sorun oluştu");            
        }
    }

    public function updateQuantity($userId, $cartId, $type)
    {
        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Cart',
            'activity' => 'updateQuantity',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $types = ['minus', 'plus'];

        $connection = $this->connection;

        $userId = (int) $userId;
        $cartId = (int) $cartId;

        try {
            if (!$userId) {
                throw new \InvalidArgumentException('Kullanıcı bulunamadı');
            }

            if (!$cartId) {
                throw new \InvalidArgumentException('Sepet bulunamadı');
            }

            if (!$type || $type == "" || !in_array($type, $types)) {
                throw new \InvalidArgumentException('Aksiyon tipi bulunamadı');
            }

            if ($type == 'plus') {
                //get quantity and stock
                $sql = "
                    SELECT
                        c.quantity,
                        pv.stock
                    FROM
                        cart c
                    LEFT JOIN
                        product_variant pv on c.variant_id = pv.id
                    WHERE
                        c.id = :id
                    AND
                        c.user_account_id = :user_account_id";

                $statement = $connection->prepare($sql);

                $statement->bindValue('id', $cartId);
                $statement->bindValue('user_account_id', $userId);
                $statement->execute();

                $quantityAndStock = $statement->fetch();

                if ($quantityAndStock['quantity']+1 > $quantityAndStock['stock']) {
                    throw new \InvalidArgumentException('Yeteri kadar stok bulunmuyor adet artırılamadı.');
                }

                $sql = "
                    UPDATE 
                        Cart
                    SET 
                        quantity = quantity + 1
                    WHERE 
                        id = :id 
                    AND
                        user_account_id = :user_account_id";

                $statement = $connection->prepare($sql);

                $statement->bindValue('id', $cartId);
                $statement->bindValue('user_account_id', $userId);

                $statement->execute();

                return;
            }

            if ($type == 'minus') {
                //get quantity
                $sql = "
                    SELECT
                        quantity
                    FROM
                        cart
                    WHERE
                        id = :id
                    AND
                        user_account_id = :user_account_id";

                $statement = $connection->prepare($sql);

                $statement->bindValue('id', $cartId);
                $statement->bindValue('user_account_id', $userId);
                $statement->execute();

                $quantity = $statement->fetchColumn();

                if ($quantity > 1) {
                    $sql = "
                        UPDATE 
                            Cart
                        SET 
                            quantity = quantity - 1
                        WHERE 
                            id = :id 
                        AND
                            user_account_id = :user_account_id";

                    $statement = $connection->prepare($sql);

                    $statement->bindValue('id', $cartId);
                    $statement->bindValue('user_account_id', $userId);

                    $statement->execute();
                }else{
                    $this->remove($userId, $cartId);
                }
                
                return;
            }

            $this->logger->info('Updated quantity cart element', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not updated quantity cart element', $logFullDetails);
            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();
            $this->logger->error('Could not updated quantity cart element', $logFullDetails);
            throw new \Exception($exception->getMessage());            
        }
    }
}
