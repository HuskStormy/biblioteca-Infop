<?php

return [
    'Seguridad' => [
        [
            'title' => 'usuarios',
            'icon'  => 'bi bi-people-fill',
            'route' => 'usuarios'
        ],
        [
            'title' => 'Permisos',
            'icon'  => 'bi bi-person-fill-lock',
            'route' => 'tabla_permisos'
        ],
        [
            'title' => 'Roles',
            'icon'  => 'bi bi-person-fill-gear',
            'route' => 'tabla_rol'
        ],
        [
            'title' => 'Parametro',
            'icon'  => 'bi bi-sliders',
            'route' => 'parametro'
        ],
        [
            'title' => 'Objeto',
            'icon'  => 'bi bi-window-desktop',
            'route' => null
        ],
        [
            'title' => 'Bitacora',
            'icon'  => 'bi bi-file-earmark-text',
            'route' => null
        ]
    ],
    'Configuración' => [
        [
            'title' => 'Ajustes Generales',
            'icon'  => 'fas fa-cogs',
            'route' => null
        ],
        [
            'title' => 'Preferencias',
            'icon'  => 'fas fa-sliders-h',
            'submenu' => [
                [
                    'title' => 'Notificaciones',
                    'route' => null,
                    'icon'  => 'fas fa-bell'
                ],
                [
                    'title' => 'Idioma',
                    'route' => null,
                    'icon'  => 'fas fa-language'
                ]
            ]
        ]
    ]
];
