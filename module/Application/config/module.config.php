<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => Controller\IndexController::class,
            'Application\Controller\Profiles'   => 'Application\Controller\ProfilesController',
            'Application\Controller\Accounts'   => 'Application\Controller\AccountsController',
            'Application\Controller\Files'      => 'Application\Controller\FilesController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),

            // Application [/application/:controller/:action] ----------------------------------------------------------

            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),

            // Profiles [/:profile_name/:action/:id] -------------------------------------------------------------------
            'profiles' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/:profile_name[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Profiles',
                        'action'        => 'index',
                        'profile_name'  => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/]:action',
                            'constraints' => array(
                                'controller' => 'Profiles',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),

            // Accounts [/accounts/:action/:id] ------------------------------------------------------------------------
            'accounts' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/accounts',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Accounts',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:action[/[:id]]]',
                            'constraints' => array(
                                'controller' => 'Accounts',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),

            // Files [/files/:action/:id] ------------------------------------------------------------------------
            'files' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/files',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Files',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:action[/[:id]]]',
                            'constraints' => array(
                                'controller' => 'Files',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'strategies' => array(
            'ViewJsonStrategy',
        ),
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
