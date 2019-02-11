<?php

namespace Api\Controller;

use Api\Entity\UsersEntity;
use Api\Exception\ApiException;
use Api\Helper\Validator\DataValidator;
use Api\Service\TokenManager;
use Api\Service\UserManager;
use Zend\View\Model\JsonModel;
use Api\Helper\DataHelper;
use Doctrine\ORM\EntityManager;

class UserController extends AbstractController
{
    protected const ENTITY_CLASS_NAME = UsersEntity::class;

    /**
     * UserController constructor.
     * @param $entityManager
     */
    public function __construct($entityManager)
    {
        parent::__construct($entityManager);
        $this->managerController = new UserManager($entityManager);
    }

    /**
     * @return array|JsonModel
     *
     * @throws ApiException
     */
    public function getListAction()
    {
        $this->checkAuth();
        $this->checkAdminKey();

        $users = parent::getListAction();
        return new JsonModel($users);
    }

    /**
     * @return array|JsonModel
     *
     * @throws ApiException
     */
    public function getAction()
    {
        $this->checkAuth();
        $this->checkAdminKey();

        $users = parent::getAction();
        return new JsonModel($users);
    }

    /**
     * @return mixed|JsonModel
     */
    public function createAction()
    {
        $users = parent::createAction();
        return new JsonModel(['id' => $users]);
    }

    /**
     * @return int|JsonModel
     *
     * @throws ApiException
     */
    public function updateAction()
    {
        $this->checkAuth();
        $this->checkAdminKey();

        $users = parent::updateAction();
        return new JsonModel(['id' => $users]);
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

        $users = parent::deleteAction();
        return new JsonModel(['deleted' => $users]);
    }

    /**
     * @return mixed|JsonModel
     * @throws ApiException
     */
    public function loginAction()
    {
        if (true === $this->getRequest()->isPost()) {
            $data = json_decode($this->getRequest()->getContent(), true);
            $response = $this->managerController->fetchAll(1, 10, ['email' => $data['email']]);

            if (true === empty($response['users'])) {
                throw new ApiException('User with this email not exist');
            }

            if (false === DataValidator::verifyPassword($data['password'], $response['users'][0]['password'])) {
                throw new ApiException('Password is incorrect');
            }

            $tokenManager = new TokenManager($this->entityManager);
            $existTokens = $tokenManager->fetchAll(1, 25, ['user' => $response['users'][0]['id']]);
            $activeToken = true === empty($existTokens['token']) ? null : $tokenManager->getActiveToken(
                $existTokens['token']
            );

            if (true === empty($activeToken)) {
                $token = DataHelper::generateToken(
                    $data['email'],
                    $response['users'][0]['password']
                );

                $tokenId = $tokenManager->create([
                    'body'       => $token,
                    'user'       => $response['users'][0]['id'],
                    'created_at' => date(DataHelper::DATA_FORMAT)
                ]);
            } else {
                $tokenId = $activeToken[0]['id'];
                $token = $activeToken[0]['body'];
            }


            return new JsonModel(
                [
                    'token' => [
                        'id' => $tokenId,
                        'token' => $token,
                    ]
                ]
            );
        }

        throw new ApiException('Something went wrong');
    }
}
