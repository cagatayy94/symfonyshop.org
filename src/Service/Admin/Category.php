<?php
namespace App\Service\Admin;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Admin\AdminAccount;
use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class Category
{
    use ServiceTrait;
    use AdminServiceTrait;

    public function getAll()
    {
        $this->authorize('settings_general');
        $connection = $this->connection;

        $sql ="
            SELECT
                id,
                name,
                slug
            FROM
                category c
            WHERE
                c.is_deleted = false
            ORDER BY 
                c.name";

        $statement = $connection->prepare($sql);

        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * Create category
     *
     * @param string $name
     *
     * @throws \Exception
     */
    public function create($name)
    {
        $this->authorize('category_create');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Category',
            'activity' => 'create',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $name = trim($name);

        try {
            if (empty($name)) {
                throw new \InvalidArgumentException('Kategori ismi belirtilmemiş');
            }

            $slug = $this->createSlugFromString($name);
            
            $slugCount = $connection->executeQuery('
                    SELECT
                        COUNT(*)
                    FROM
                        category c
                    WHERE
                        c.slug = :slug
                    AND
                        c.is_deleted = FALSE
                ', [
                    'slug' => $slug,
                ]
            )->fetchColumn();

            if ($slugCount > 0) {
                throw new \InvalidArgumentException('Belirttiğiniz kategori farklı isimle eklenmiş');
            }

            $statement = $connection->prepare('
                INSERT INTO category
                    (name, slug)
                VALUES
                    (:name, :slug)
            ');

            $statement->bindValue(':name', $name);
            $statement->bindValue(':slug', $slug);
            $statement->execute();

            $categoryId = $connection->lastInsertId();

            $logFullDetails['activityId'] = $categoryId;
            $this->logger->info('Created the category', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not create category', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not create category', $logFullDetails);

            throw $exception;
        }
    }
}
