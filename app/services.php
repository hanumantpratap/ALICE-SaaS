<?php
declare(strict_types=1);


use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        VisitsService::class => DI\create()->constructor(DI\get(DistrictDatabaseConnection::class), DI\get(LoggerInterface::class))
    ]);
};
