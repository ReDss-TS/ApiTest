<?php

namespace Api\Helper;

use Api\Helper\ValidateHelper;
use Api\Helper\Validator\DataValidator;
use DateTime;
use Zend\Crypt\Password\Bcrypt;

/**
 * @method verifyUserData(array $data)
 */
class DataHelper
{
    public const DATA_FORMAT = 'Y-m-d H:i:s';

    public const DATA_SECONDS = 1;
    public const DATA_MINUTES = 60;
    public const DATA_HOURS   = 60 * 60;
    public const DATA_DAYS    = 60 * 60 * 24;

    public const FIELD_EMAIL = 'email';
    public const FIELD_PASSWORD = 'password';

    /**
     * @param string $password password
     *
     * @return string
     */
    public static function hashPassword($password)
    {
        return (new Bcrypt())->setCost(14)->create($password);
    }

    /**
     * @param string $email    email
     * @param string $password password
     *
     * @return string
     */
    public static function generateToken(string $email, string $password): string
    {
        return base64_encode($email) . '.' . $password . '|' . \base64_encode(date("Y-m-d H:i:s"));
    }

    /**
     * @param string $newerDate
     * @param string $olderDate
     * @param int    $needTime
     *
     * @return float
     *
     */
    public static function getDateDifference(string $newerDate, string $olderDate, $needTime): float
    {
        return round((strtotime($newerDate) - strtotime($olderDate)) / $needTime, 1);
    }

    /**
     * @param array $data  data
     * @param array $rules rules
     *
     * @return array
     */
    public function verifyData(array $data, array $rules): array
    {
        foreach ($data as $field => &$value) {
            if (true === isset($rules[$field]) &&  true === is_string($value)) {
                foreach ($rules[$field] as $rule) {
                    $data[$field] = DataValidator::$rule($field, $value);
                }
            }
        }

        return $data;
    }

    /**
     * @param array $data data
     *
     * @return array
     */
    public function sanitizeData(array $data): array
    {
        foreach ($data as $field => $value) {
            switch ($field) {
                case self::FIELD_EMAIL:
                    $data[$field] = filter_var($value, FILTER_SANITIZE_EMAIL);
                    break;
                default:
                    $data[$field] = filter_var($value, FILTER_SANITIZE_MAGIC_QUOTES);
            }
        }

        return $data;
    }
}
