<?php

namespace Api\Controller;

use Api\Entity\CompanyEntity;
use Api\Exception\ApiException;
use Api\Service\CompanyManager;
use Zend\View\Model\JsonModel;

class CompanyController extends AbstractController
{
    protected const ENTITY_CLASS_NAME = CompanyEntity::class;

    protected $entityManager;
    protected $managerController;

    /**
     * CompanyController constructor.
     * @param $entityManager
     */
    public function __construct($entityManager)
    {
        parent::__construct($entityManager);
        $this->managerController = new CompanyManager($entityManager);
    }

    /**
     * @return mixed|JsonModel
     */
    public function getListAction()
    {
        $data = parent::getListAction();
        return new JsonModel($data);
    }

    /**
     * @return array|JsonModel
     *
     * @throws ApiException
     */
    public function getAction()
    {
        $request = $this->getRequest();

        $this->checkAuth();

        if (false === $request->isGet()) {
            return new JsonModel([]);
        }
////        $data = parent::getAction();
        $symbol = false === empty($symbol = $request->getQuery('symbol')) ? $symbol : 'MSFT';
        $interval = false === empty($interval = $request->getQuery('interval')) ? $interval : 5;
        $response = $this->managerController->getStockValues($symbol, (int)$interval);
        return new JsonModel($response);
    }

    /**
     * @return int|JsonModel
     *
     * @throws ApiException
     */
    public function createAction()
    {
        $this->checkAuth();
        $this->checkAdminKey();

        $data = parent::createAction();
        return new JsonModel(['id' => $data]);
    }

    /**
     * @return mixed|JsonModel
     *
     * @throws ApiException
     */
    public function updateAction()
    {
        $this->checkAuth();
        $this->checkAdminKey();

        $data = parent::updateAction();
        return new JsonModel(['id' => $data]);
    }

    /**
     * @return mixed|JsonModel
     *
     * @throws ApiException
     */
    public function deleteAction()
    {
        $this->checkAuth();
        $this->checkAdminKey();

        $data = parent::deleteAction();
        return new JsonModel(['deleted' => $data]);
    }
}
