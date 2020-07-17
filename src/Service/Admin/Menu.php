<?php
namespace App\Service\Admin;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Sdk\ServiceTrait;
use App\Sdk\AdminServiceTrait;

class Menu
{
    use ServiceTrait;
    use AdminServiceTrait;

    /**
     * Get All Menu
     *
     * @throws \Exception
     */
    public function getAll()
    {
        $this->authorize('menu_list');

        $connection = $this->connection;

        //values sql
        $recordsSql = "
            SELECT
                id,
                slug,
                name
            FROM
                menu m
            WHERE
                is_deleted = FALSE
            ORDER BY
                slug";

        $statement = $connection->prepare($recordsSql);
        
        $statement->execute();

        $menus = $statement->fetchAll();

        foreach ($menus as $key => $value) {
            //get menu categories
            $categories = $connection->executeQuery('
                SELECT
                    c.id,
                    c.slug,
                    c.name,
                    c.slug
                FROM
                    menu m
                LEFT JOIN
                    category_menu cm ON cm.menu_id = m.id AND m.is_deleted = false
                LEFT JOIN
                    category c ON cm.category_id = c.id AND c.is_deleted = false
                WHERE
                    m.id = :id

                ', [
                    ':id' => $value['id'],
                ]
            )->fetchAll();

            $menus[$key]['category'] = $categories;
        }

        return $menus;
    }

    /**
     * Create menu
     *
     * @param string $name
     *
     * @throws \Exception
     */
    public function create($name)
    {
        $this->authorize('menu_create');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Menu',
            'activity' => 'create',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $name = trim($name);

        try {
            if (empty($name)) {
                throw new \InvalidArgumentException('Menü ismi belirtilmemiş');
            }

            $slug = $this->createSlugFromString($name);

            $menuCount = $connection->executeQuery('
                    SELECT
                        COUNT(*)
                    FROM
                        menu m
                    WHERE
                        m.is_deleted = FALSE'
            )->fetchColumn();

            if ($menuCount > 4) {
                throw new \InvalidArgumentException('En fazla 5 adet menu ekleyebilirsiniz.');
            }
            
            $slugCount = $connection->executeQuery('
                    SELECT
                        COUNT(*)
                    FROM
                        menu m
                    WHERE
                        m.slug = :slug
                    AND
                        m.is_deleted = FALSE
                ', [
                    'slug' => $slug,
                ]
            )->fetchColumn();

            if ($slugCount > 0) {
                throw new \InvalidArgumentException('Belirttiğiniz menu farklı isimle eklenmiş');
            }

            $statement = $connection->prepare('
                INSERT INTO menu
                    (name, slug)
                VALUES
                    (:name, :slug)
            ');

            $statement->bindValue(':name', $name);
            $statement->bindValue(':slug', $slug);
            $statement->execute();

            $menuId = $connection->lastInsertId();

            $logFullDetails['activityId'] = $menuId;
            $this->logger->info('Created the menu', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not create menu', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not create menu', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Get Menu Detail
     *
     * @param int $id
     * @throws \Exception
     */
    public function detail($id)
    {
        $this->authorize('menu_detail');

        $connection = $this->connection;

        $sql ="
            SELECT
                m.name,
                m.slug
            FROM
                menu m
            LEFT JOIN
                category_menu cm ON cm.menu_id = m.id
            WHERE
                m.is_deleted = FALSE
            AND
                m.id = :id";

        $statement = $connection->prepare($sql);

        $statement->bindValue(':id', $id);

        $statement->execute();

        $result = $statement->fetch();

        if (!$result) {
            throw new \InvalidArgumentException('Menü bulunamadı');
        }

        //get menu categories
        $categories = $connection->executeQuery('
            SELECT
                c.id,
                c.slug,
                c.name,
                c.slug
            FROM
                menu m
            LEFT JOIN
                category_menu cm ON cm.menu_id = m.id
            LEFT JOIN
                category c ON cm.category_id = c.id AND c.is_deleted = false
            WHERE
                m.id = :id

            ', [
                ':id' => $id,
            ]
        )->fetchAll();

        $menuCategories = [];
        foreach ($categories as $category) {
            $menuCategories[$category['id']] = [
                'slug' => $category['slug'],
                'name' => $category['name']
            ];
        }

        $result['category'] = $menuCategories;

        return $result;
    }

    /**
     * Delete the menu
     *
     * @param int $id
     * @throws \Exception
     */
    public function delete($id)
    {
        $this->authorize('menu_delete');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Menu',
            'activity' => 'delete',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $id = intval($id);

        try {
            if (!$id) {
                throw new \InvalidArgumentException('Menü Id belirtilmemiş');
            }

            $countCategory = $connection->executeQuery('
                SELECT
                    count(c.id)
                FROM
                    menu m
                LEFT JOIN
                    category_menu cm ON cm.menu_id = m.id
                LEFT JOIN
                    category c ON cm.category_id = c.id AND c.is_deleted = false
                WHERE
                    m.id = :id

                ', [
                    ':id' => $id,
                ]
            )->fetchColumn();

            if ($countCategory) {
                throw new \InvalidArgumentException('Menüde ekli kategori varken menüyü silemezsiniz');
            }

            $sql = '
                UPDATE 
                    menu
                SET 
                    is_deleted = TRUE
                WHERE
                    id = :id';

            $statement = $connection->prepare($sql);

            $statement->bindValue(':id', $id);

            $statement->execute();

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Deleted the menu', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not deleted the menu', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not deleted the menu', $logFullDetails);

            throw $exception;
        }
    }

    /**
     * Update menu 
     *
     * @param string $id
     * @param string $name
     * @param string $categories
     * @throws \Exception
     */
    public function update($id, $name, $categories)
    {
        $this->authorize('menu_update');

        $logDetails = $this->getArguments(__FUNCTION__, func_get_args());

        $logFullDetails = [
            'entity' => 'Menu',
            'activity' => 'update',
            'activityId' => 0,
            'details' => $logDetails
        ];

        $connection = $this->connection;

        $id = intval($id);
        $name = trim($name);

        try {
            if (!$id) {
                throw new \InvalidArgumentException('Id belirtilmemiş');
            }
            if (empty($name)) {
                throw new \InvalidArgumentException('Ad belirtilmemiş');
            }

            try {
                $connection->beginTransaction();


                $sql = '
                    UPDATE 
                        menu
                    SET 
                        name = :name
                    WHERE
                        id = :id';

                $statement = $connection->prepare($sql);

                $statement->bindValue(':id', $id);
                $statement->bindValue(':name', $name);

                $statement->execute();

                $statement = '';

                $statement = $connection->prepare('
                    DELETE FROM
                        category_menu
                    WHERE
                        menu_id = :menu_id
                ');

                $statement->bindValue(':menu_id', $id);
                $statement->execute();

                if ($categories) {
                    foreach ($categories as $value) {
                        $statement = '';

                        $statement = $connection->prepare('
                            INSERT INTO 
                                category_menu ( category_id, menu_id )
                            VALUES
                                (:category_id, :menu_id)
                        ');

                        $statement->bindValue(':menu_id', $id);
                        $statement->bindValue(':category_id', $value);
                        $statement->execute();
                    }
                }

                $connection->commit();
                
            } catch (Exception $e) {
                $connection->rollBack();
                throw $exception;
            }

            $logFullDetails['activityId'] = $id;
            $this->logger->info('Updated the menu', $logFullDetails);
        } catch (\InvalidArgumentException $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not update menu', $logFullDetails);

            throw $exception;
        } catch (\Exception $exception) {
            $logFullDetails['details']['exception'] = $exception->getMessage();

            $this->logger->error('Could not update menu', $logFullDetails);

            throw $exception;
        }
    }
}
