<?php
return array(
    'doctrine' => array(
        'driver'     => array(
            'test\entity\default' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(__DIR__ . '/module/SynergyDataGridTest/Entity'),
            ),

            'orm_default'         => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => array(
                    'SynergyDataGridTest\Entity' => 'test\entity\default',
                )
            )
        ),

        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params'      => array(
                    'host'     => '127.0.0.1',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => '',
                    'dbname'   => 'test',
                ),
            )
        ),

    ),
    'jqgrid'   => array(
        'compress_script' > true,
        'grid_model'      => array(
            'testBrands' => array(
                'isSubGridAsGrid' => true
            )
        ),
        'toolbar_buttons' => array(
            'global' => array(
                'help' => array(
                    'title'      => 'Help',
                    'icon'       => 'icon-info-sign',
                    'position'   => 'top',
                    'class'      => 'btn btn-mini',
                    'callback'   => new \Zend\Json\Expr('function(){ alert("i am here");}'),
                    'onLoad'     => 'var home = "";',
                    'attributes' => array(
                        'data - entity' => 'templates',
                        'data - href'   => 'my_url',
                    )
                )
            )
        )
    ),
);
