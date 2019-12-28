<?php
namespace Modules\Core\Extensions\Doctrine\Listener;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Event\ConnectionEventArgs;
use Gedmo\Mapping\MappedEventSubscriber;

class DistinctFixer extends MappedEventSubscriber
{
    /**
     * Specifies the list of events to listen
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'postConnect',
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function getNamespace()
    {
        return __NAMESPACE__;
    }

    /**
     * Triggers after connection to Database
     *
     * @param ConnectionEventArgs $args
     * @return \Doctrine\DBAL\Connection
     */
    public function postConnect(ConnectionEventArgs $args)
    {
        $connectionParams = $args->getConnection()->getParams();
        $connectionParams['driverOptions'] = [
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))"
        ];
        $evm = new EventManager();
        return DriverManager::getConnection($connectionParams, null, $evm);
    }
}