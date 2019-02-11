<?php

namespace Api\Service;

use Doctrine\ORM\EntityManager;
use Api\Exception\ApiException;
use Api\Helper\DataHelper;

abstract class AbstractManager
{
    protected const ENTITY_NAME = null;
    protected const ENTITY_CLASS_NAME = null;

    protected $entityManager;
    protected $dataHelper;

    /**
     * @param array $data data
     */
    abstract public function create(array $data);

    /**
     * @param int   $id
     * @param array $data
     */
    abstract public function update(int $id, array $data);


    /**
     * AbstractController constructor.
     * @param $entityManager
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
        $this->dataHelper = new DataHelper();
    }

    /**
     * @param int   $page
     * @param int   $pageSize
     * @param array $filter
     *
     * @return mixed
     */
    public function fetchAll(int $page = 1, int $pageSize = 25, array $filter = [])
    {
        $response = $this->entityManager->getRepository(static::ENTITY_CLASS_NAME)->findBy(
            $filter,
            [],
            $pageSize,
            $page * $pageSize - $pageSize
        );

        return $response;
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function fetch(int $id)
    {
        $response = $this->entityManager->getRepository(static::ENTITY_CLASS_NAME)->find($id);
        return $response;
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
        $entity = self::fetch($id);
        if (true ===\is_null($entity)) {
            throw new ApiException('Record not exist');
        }

        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        if (null === $entity->getId()) {
            return true;
        }

        return false;
    }
}
