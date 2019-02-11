<?php

namespace Api\Controller;

use Api\Exception\ApiException;
use Api\Service\TokenManager;
use Zend\Http\Client;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

abstract class AbstractController extends AbstractRestfulController
{
    protected const ADMIN_KEY = 'admin_key';

    protected const ENTITY_CLASS_NAME = null;

    protected $entityManager;
    protected $managerController;

    /**
     * AbstractController constructor.
     * @param $entityManager
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getListAction()
    {
        $response = $this->managerController->fetchAll();
        return $response;
    }

    /**
     * @return array
     */
    public function getAction()
    {
        $id = $this->params()->fromRoute('id', -1);
        $response = $this->managerController->fetch($id);
        return $response;
    }

    /**
     * @return int
     */
    public function createAction()
    {
        $response = [];

        if (true === $this->getRequest()->isPost()) {
            $data = json_decode($this->getRequest()->getContent(), true);
            $response = $this->managerController->create($data);
        }

        return $response;
    }

    /**
     * @return int
     */
    public function updateAction()
    {
        $id = $this->params()->fromRoute('id', -1);

        if (true === $this->getRequest()->isPut()) {
            $data = json_decode($this->getRequest()->getContent(), true);
            $response = $this->managerController->update($id, $data);
        }

        return $response;
    }

    /**
     * @return mixed|JsonModel
     */
    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id', -1);

        if (true === $this->getRequest()->isPost() || $this->getRequest()->isGet()) {
            $response = $this->managerController->delete($id);
        }

        return $response;
    }

    /**
     * @throws ApiException
     */
    public function checkAuth()
    {
        $request = $this->getRequest();

        if (false === $authToken = $request->getHeaders('Authorization')) {
            throw new ApiException('Unauthorized');
        }

        $tokenManager = new TokenManager($this->entityManager);
        $existTokens = $tokenManager->fetchAll(
            1,
            25,
            ['body' => \ltrim($authToken->toString(), 'Authorization: ')]
        );

        $activeToken = true === empty($existTokens['token']) ? null : $tokenManager->getActiveToken(
            $existTokens['token']
        );

        if (true === empty($activeToken)) {
            throw new ApiException('Unauthorized');
        }

    }

    /**
     * @throws ApiException
     */
    public function checkAdminKey()
    {
        $request = $this->getRequest();

        if (false === $adminKey = $request->getHeaders('AdminKey')) {
            throw new ApiException('AdminKey is empty');
        }

        if (\ltrim($adminKey->toString(), 'Adminkey: ') !== self::ADMIN_KEY) {
            throw new ApiException('AdminKey is incorrect');
        }
    }
}
