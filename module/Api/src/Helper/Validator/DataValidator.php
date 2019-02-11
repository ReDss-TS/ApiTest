<?php

namespace Api\Helper\Validator;

use Api\Exception\ApiException;
use Zend\Crypt\Password\Bcrypt;
use Zend\Validator\Date;
use Zend\Validator\NotEmpty;

class DataValidator
{
    /**
     * @param string $password     password
     * @param string $passwordHash passwordHash
     *
     * @return bool
     */
    public static function verifyPassword($password, $passwordHash)
    {
        return (new Bcrypt())->setCost(14)->verify($password, $passwordHash);
    }

    /**
     * @param string $fieldName fieldName
     * @param string $str       str
     *
     * @return string
     *
     * @throws ApiException
     */
    public static function verifyNotEmpty(string $fieldName, string $str)
    {
        $validator = new NotEmpty();
        $validator->setType(NotEmpty::ALL);

        if (false === $validator->isValid($str)) {
            throw new ApiException('Field ' . $fieldName . ' can\'t be empty');
        }

        return $str;
    }

    /**
     * @param string $fieldName fieldName
     * @param string $str       str
     *
     * @return string
     *
     * @throws ApiException
     */
    public static function verifyString(string $fieldName, string $str)
    {
        if (false === \is_string($str)) {
            throw new ApiException('Field ' . $fieldName . ' must have string type');
        }

        return $str;
    }

    /**
     * @param string $fieldName fieldName
     * @param string $str       str
     *
     * @return string
     *
     * @throws ApiException
     */
    public static function verifyEmail(string $fieldName, string $str)
    {
        if (false === filter_var($str, FILTER_VALIDATE_EMAIL)) {
            throw new ApiException('Field ' . $fieldName . ' must have valid email');
        }

        return $str;
    }

    /**
     * @param string $fieldName fieldName
     * @param string $str       str
     *
     * @return string
     *
     * @throws ApiException
     */
    public static function verifyDate(string $fieldName, string $str)
    {
        $validator = new Date();
        $validator->setFormat('Y-m-d');

        if (false === $validator->isValid($str)) {
            throw new ApiException('Field ' . $fieldName . ' must have valid data');
        }

        return $str;
    }

}
