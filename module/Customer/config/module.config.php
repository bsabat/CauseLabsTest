<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Customer\Controller\Rest' => 'Customer\Controller\CustomerRestController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'customer-rest' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/customer[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Customer\Controller\Rest',
                    ),
                ),
            ),/*
            'customer' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/customer',
                    'defaults' => array(
                        'controller' => 'Customer\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),*/


        ),
    ),
    'view_manager' => array( //Add this config
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);