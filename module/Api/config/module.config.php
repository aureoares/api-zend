<?php

namespace Api;

use Zend\Router\Http\Segment;

return [
    'view_manager' => [
        'template_path_stack' => [
            'api' =>__DIR__ . '/../view',
        ],
    ],
    'router' => [
        'routes' => [
            'companies' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/api/companies[/:id]',
                    'constraints' => [
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\CompanyController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'teams' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/api/companies/:companyId/teams[/:id]',
                    'constraints' => [
                        'companyId' => '[0-9]+',
                        'id'        => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\TeamController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'members' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/api/companies/:companyId/teams/:teamId/members[/:id]',
                    'constraints' => [
                        'companyId' => '[0-9]+',
                        'teamId'    => '[0-9]+',
                        'id'        => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\MemberController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
];
