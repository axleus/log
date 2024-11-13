<?php

declare(strict_types=1);

namespace Log\Repository;

use Db;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\TableGateway;
use Log\ConfigProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class RepositoryHandlerFactory
{
    /**
     *
     * @param ContainerInterface $container
     * @return RepositoryHandler
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RepositoryHandler
    {
        /** @var array{log: array{table: string}} */
        $config = $container->get('config');
        if (
            ! empty($config[ConfigProvider::APP_SETTINGS_KEY])
            && ! empty($config[ConfigProvider::APP_SETTINGS_KEY][ConfigProvider::class])
        ) {
            $config = $config[ConfigProvider::APP_SETTINGS_KEY][ConfigProvider::class];
        }
        /** @var Adapter */
        $adapter = $container->get(AdapterInterface::class);

        //$table = $config['log']['table'];
        return new RepositoryHandler(
            new TableGateway(
                new Db\Sql\TableIdentifier(
                    $config['table'],
                    $config['table_prefix']
                ),
                $adapter
            )
        );
    }
}
