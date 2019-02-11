<?php

namespace Api\Service;

use Api\Entity\TokensEntity;
use Api\Entity\UsersEntity;
use Api\Exception\ApiException;
use Api\Helper\DataHelper;
use Api\Helper\ValidateHelper;
use Exception;

class UserManager extends AbstractManager
{
    protected const ENTITY_NAME = 'users';
    protected const ENTITY_CLASS_NAME = UsersEntity::class;

    /**
     * @param int   $page     page
     * @param int   $pageSize pageSize
     * @param array $filter   filter
     *
     * @return array
     */
    public function fetchAll(int $page = 1, int $pageSize = 25, array $filter = []): array
    {
        $data = \array_map(
            function ($record) {
                return json_decode(json_encode($record), true);
            },
            $response = parent::fetchAll($page, $pageSize, $filter)
        );

        return [self::ENTITY_NAME => $data];
    }

    /**
     * @param int $id id
     *
     * @return array
     */
    public function fetch(int $id): array
    {
        $data = parent::fetch($id);
        return [self::ENTITY_NAME => json_decode(json_encode($data), true)];
    }

    /**
     * @param array $data data
     *
     * @return int
     */
    public function create(array $data)
    {
        $data = $this->dataHelper->verifyData($data, ValidateHelper::RULES_USER);
        $data = $this->dataHelper->sanitizeData($data);

        $user = new UsersEntity();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword(DataHelper::hashPassword($data['password']));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user->getId();
    }

    /**
     * @param int   $id   id
     * @param array $data data
     *
     * @return int
     *
     * @throws Exception
     */
    public function update(int $id, array $data)
    {
        $existUser = parent::fetch($id);

        if (true === \is_null($existUser)) {
            throw new ApiException('Record not exist');
        }

        $data = $this->dataHelper->verifyData($data, ValidateHelper::RULES_USER);
        $data = $this->dataHelper->sanitizeData($data);

        $existUser->setName($data['name']);
        $existUser->setEmail($data['email']);
        $existUser->setPassword(DataHelper::hashPassword($data['password']));

        $this->entityManager->persist($existUser);
        $this->entityManager->flush();
        return $existUser->getId();
    }

    /**
     * @param int $id
     *
     * @return bool
     *
     * @throws ApiException
     */
    public function delete(int $id)
    {
        $response = parent::delete($id);

        return $response;
    }
}
