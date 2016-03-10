<?php
namespace SynergyDataGrid\Grid;

/**
 * This file is part of the Synergy package.
 *
 * (c) Pele Odiase <info@rhemastudio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Pele Odiase
 * @license http://opensource.org/licenses/BSD-3-Clause
 *
 */

use SynergyDataGrid\Grid\GridType\DoctrineODMGrid;
use SynergyDataGrid\Util\ArrayUtils;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractGridFactory
 * @package SynergyDataGrid\Grid
 */
class AbstractGridFactory implements AbstractFactoryInterface
{

    protected $_configPrefix = 'jqgrid';

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (substr($requestedName, 0, strlen($this->_configPrefix)) != $this->_configPrefix) {
            return false;
        }

        return true;
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $gridType   = trim(str_replace($this->_configPrefix, '', $requestedName), '\\');
        $config     = $serviceLocator->get('Config');
        $gridConfig = $config['jqgrid'];

        if (array_key_exists('factories', $gridConfig)) {
            $util = new ArrayUtils();
            foreach ((array)$gridConfig['factories'] as $alias) {
                if ($serviceLocator->has($alias)) {
                    $addConfig  = $serviceLocator->get($alias);
                    $gridConfig = $util->arrayMergeRecursiveCustom($gridConfig, $addConfig);
                }
            }
        }

        switch ($gridType) {
            case 'odm':
                $manager = $serviceLocator->get('doctrine.entitymanager.odm_default');
                $class   = 'SynergyDataGrid\Grid\GridType\DoctrineODMGrid';
                break;
            default:
                $manager = $serviceLocator->get('doctrine.entitymanager.orm_default');
                $class   = 'SynergyDataGrid\Grid\GridType\DoctrineORMGrid';
        }

        /** @var DoctrineODMGrid $grid */
        $grid = new $class($gridConfig, $serviceLocator, $manager);

        return $grid;
    }
}
