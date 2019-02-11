<?php

namespace Api\Helper;


class ValidateHelper
{
    public const RULES_USER = [
        'name' => [
            'verifyNotEmpty',
        ],
        'email' => [
            'verifyNotEmpty',
            'verifyEmail',
        ],
        'password' => [
            'verifyNotEmpty',
        ]
    ];

    public const RULES_TOKEN = [
        'body' => [
            'verifyNotEmpty',
        ],
        'user' => [
            'verifyNotEmpty',
        ],
    ];

    public const RULES_COMPANY = [
        'name' => [
            'verifyNotEmpty',
        ],
        'symbol' => [
            'verifyNotEmpty',
        ],
    ];
}
