<?php

namespace Api\Service\Config;

use Api\Entity\CompanyEntity;
use Api\Entity\UsersEntity;
use Api\Exception\ApiException;
use Api\Helper\DataHelper;
use Api\Helper\ValidateHelper;
use Exception;
use Zend\Http\Client;

class CompanyManagerConfig
{
    public const DATA_RESOURCE = 'https://www.alphavantage.co/query';
    public const DATA_RESOURCE_API_KEY = 'SO36E5H0R7IU3W1E';

    public const DATA_RESOURCE_INTERVAL = [
        1  => '1min',
        5  => '5min',
        15 => '15min',
        30 => '30min',
        60 => '60min',
    ];

    public const DATA_RESOURCE_DEFAULT_PARAMETERS = [
        'function' => 'TIME_SERIES_INTRADAY',
        'symbol'   => 'MSFT',
        'interval' => self::DATA_RESOURCE_INTERVAL[5],
        'apikey'   => self::DATA_RESOURCE_API_KEY
    ];



}
