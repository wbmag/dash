<?php
namespace Workspace;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Workspace\Controller\Index'
                    ),
                ),
            ),            
          
//             'index' => array(
//                'type' => 'Segment',
//                'options' => array(
//                    'route' => '/message/index',
//                    'defaults' => array(
//                        '__NAMESPACE__' => 'Message\Controller',
//                        'controller'    => 'Message',
//                        'action'        => 'index',
//                    )
//                ),
//            ),
            
//            'message' => array(
//                'type' => 'segment',
//                'options' => array(
//                    'route' => '/message[/:id]',
//                        'constraints' => array(
//                            'id' => '[0-9]+',
//                        ),
//                        'defaults' => array(
//                            'controller' => 'Message\Controller\MessageController',
//                        ),
//                ),
//             ),
//             
//             
//            
             'workspace' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/workspace[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Workspace\Controller\Workspace',
                    ),
                ),
            ),
            
            'workspace-member-create' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/workspace/workspace-member-create',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Workspace\Controller',
                        'controller'    => 'workspace',
                        'action'        => 'workspace-member-create',
                    )
                ),
            ),
            
                  
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Workspace\Controller\Workspace'   => 'Workspace\Controller\WorkspaceController',
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),    
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Workspace/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Workspace\Entity' => 'application_entities'
                )
            )
        ),       
    ),
  
);
