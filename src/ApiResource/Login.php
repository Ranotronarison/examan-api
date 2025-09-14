<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/login_check',
            controller: null,
            read: false,
            write: false,
            status: 200,
            openapi: new Model\Operation(
                summary: 'User login',
                description: 'Login with email and password to get JWT token',
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'username' => ['type' => 'string', 'example' => 'user@example.com'],
                                    'password' => ['type' => 'string', 'example' => 'password'],
                                ],
                                'required' => ['username', 'password'],
                            ],
                        ],
                    ])
                ),
                responses: [
                    '200' => [
                        'description' => 'Login successful',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'token' => ['type' => 'string'],
                                        'refresh_token' => ['type' => 'string'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    '401' => ['description' => 'Invalid credentials'],
                ]
            )
        ),
    ],
    routePrefix: '/api'
)]
class Login
{
    // This class is only for documentation purposes
    // The actual login is handled by the security firewall
}
