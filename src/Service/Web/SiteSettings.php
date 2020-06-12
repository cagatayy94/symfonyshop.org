<?php
namespace App\Service\Web;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Admin\AdminAccount;
use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class SiteSettings
{
    use ServiceTrait;

    public function getFooterData()
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                *
            FROM
                site_settings ss
            WHERE
                ss.is_deleted = false
                ";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetch();
    }

    public function getAgreementString($columnName)
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                $columnName
            FROM
                agreements_strings
            LIMIT 1
                ";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchColumn();
    }

    public function getBankAccounts()
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                *
            FROM
                bank_accounts
            ORDER BY
                name";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function getFaq()
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                question,
                answer
            FROM
                faq
            ORDER BY
                id";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function getBanner()
    {
        $connection = $this->connection;

        $sql = "
            SELECT
                id,
                pic
            FROM
                banner
            ORDER BY
                number_of_show
            LIMIT 1";

        $statement = $connection->prepare($sql);

        $statement->execute();

        $banner = $statement->fetch();

        if ($banner) {
            $statement = $connection->prepare('
                UPDATE 
                    banner
                SET
                    number_of_show = number_of_show + 1
                WHERE 
                    id = :id

            ');

            $statement->bindValue(':id', $banner['id']);
            $statement->execute();

            return $banner['pic'];

        }else{
            return [];
        }
    }
}
