<?php

namespace Api\Service;

use Api\Entity\CompanyEntity;
use Api\Entity\UsersEntity;
use Api\Exception\ApiException;
use Api\Helper\DataHelper;
use Api\Helper\ValidateHelper;
use Api\Service\Config\CompanyManagerConfig;
use Exception;
use Zend\Http\Client;
use Zend\Http\Request;

class CompanyManager extends AbstractManager
{
    protected const ENTITY_NAME = 'company';
    protected const ENTITY_CLASS_NAME = CompanyEntity::class;

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
        $users = parent::fetch($id);
        return [self::ENTITY_NAME => json_decode(json_encode($users), true)];
    }

    /**
     * @param array $data data
     *
     * @return int
     */
    public function create(array $data)
    {
        $data = $this->dataHelper->verifyData($data, ValidateHelper::RULES_COMPANY);
        $data = $this->dataHelper->sanitizeData($data);

        $company = new CompanyEntity();
        $company->setName($data['name']);
        $company->setSymbol($data['symbol']);

        $this->entityManager->persist($company);
        $this->entityManager->flush();

        return $company->getId();
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
        $existCompany = parent::fetch($id);

        if (true === \is_null($existCompany)) {
            throw new ApiException('Record not exist');
        }

        $data = $this->dataHelper->verifyData($data, ValidateHelper::RULES_COMPANY);
        $data = $this->dataHelper->sanitizeData($data);

        $existCompany->setName($data['name']);
        $existCompany->setSymbol($data['symbol']);

        $this->entityManager->persist($existCompany);
        $this->entityManager->flush();
        return $existCompany->getId();
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

    /**
     * @param string $symbol   symbol
     * @param int    $interval interval
     *
     * @return array
     *
     * @throws ApiException
     */
    public function getStockValues(string $symbol, int $interval): array
    {
        $this->validateParams($symbol, $interval);

        $client = new Client();
        $client->setUri(CompanyManagerConfig::DATA_RESOURCE);
        $client->setMethod(Request::METHOD_GET);

        $client->setParameterGet([
            'function' => CompanyManagerConfig::DATA_RESOURCE_DEFAULT_PARAMETERS['function'],
            'symbol'   => $symbol,
            'interval' => CompanyManagerConfig::DATA_RESOURCE_INTERVAL[$interval],
            'apikey'   => CompanyManagerConfig::DATA_RESOURCE_API_KEY,
        ]);

        $response = $client->send();

        if (200 !== $response->getStatusCode()) {
            throw new ApiException('Can\'t connect to resource. Please contact us');
        }

        $content = json_decode($response->getBody(), true);
        return array_slice($content, 1, 1, true);
    }

    /**
     * @param string $symbol symbol
     * @param int $interval interval
     *
     * @throws ApiException
     */
    private function validateParams(string $symbol, int $interval)
    {
        if (false === \in_array($interval, \array_keys(CompanyManagerConfig::DATA_RESOURCE_INTERVAL))) {
            throw new ApiException(
                'Interval is incorrect. Needed values = ' .
                implode(', ', \array_keys(CompanyManagerConfig::DATA_RESOURCE_INTERVAL))
            );
        }

        if (false === \in_array($symbol, $symbols = $this->getCompanySymbols())) {
            throw new ApiException(
                'Symbols is incorrect. Needed values = ' .
                implode(', ', $symbols)
            );
        }
    }

    /**
     * @return array|null
     */
    public function getCompanySymbols(): ?array
    {
        $symbols = [];
        $page = 1;
        $pageSize = 25;

        do {
            $response = $this->fetchAll($page, $pageSize);
            if (false === empty($response['company'])) {
                foreach ($response['company'] as $company) {
                    $symbols[] = $company['symbol'];
                }
            }
            $page++;
        } while ($pageSize === \count($response['company']));

        return $symbols;
    }
}
