<?php

namespace Api\Service;

use Api\Entity\TokensEntity;
use Api\Entity\UsersEntity;
use Api\Exception\ApiException;
use Api\Helper\DataHelper;
use Api\Helper\ValidateHelper;
use Exception;

class TokenManager extends AbstractManager
{
    private const TOKEN_MAX_HOURS_LIFE = 5;

    protected const ENTITY_NAME = 'token';
    protected const ENTITY_CLASS_NAME = TokensEntity::class;

    /**
     * @param int $page page
     * @param int $pageSize pageSize
     * @param array $filter filter
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
        $user = $this->entityManager->getRepository(UsersEntity::class)->find($data['user']);

        $token = new TokensEntity();
        $token->setBody($data['body']);
        $token->setUser($user);
        $token->setCreatedAt($data['created_at']);

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        return $token->getId();
    }

    /**
     * @param int $id id
     * @param array $data data
     *
     * @return int
     * @throws Exception
     */
    public function update(int $id, array $data)
    {
        $token = parent::fetch($id);

        if (true === \is_null($token)) {
            throw new ApiException('Token not exist');
        }

        $token->setBody($data['body']);
        $token->setUser($this->entityManager->getRepository(UsersEntity::class)->find($data['user']));
        $token->setCreatedAt($data['created_at']);

        $this->entityManager->persist($token);
        $this->entityManager->flush();
        return $token->getId();
    }

    /**
     * @param int $id id
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

    /**
     * @param array $tokens tokens
     *
     * @return array|null
     *
     * @throws ApiException
     */
    public function getActiveToken(array $tokens): ?array
    {
        foreach ($tokens as $key => $token) {
            $timeDiff = DataHelper::getDateDifference(
                date(DataHelper::DATA_FORMAT),
                $token['created_at'],
                DataHelper::DATA_HOURS
            );

            if (self::TOKEN_MAX_HOURS_LIFE < $timeDiff) {
                if (true === $this->delete($token['id'])) {
                    unset($tokens[$key]);
                }
            }

        }

        return $tokens;
    }
}
