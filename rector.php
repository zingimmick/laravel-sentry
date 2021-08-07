<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\Core\Configuration\Option;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\ChangeOrIfReturnToEarlyReturnRector;
use Rector\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector;
use Rector\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector;
use Rector\PHPUnit\Rector\Class_\AddSeeTestAnnotationRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Privatization\Rector\Class_\RepeatedLiteralToClassConstantRector;
use Rector\Privatization\Rector\MethodCall\PrivatizeLocalGetterToPropertyRector;
use Rector\Privatization\Rector\Property\PrivatizeLocalPropertyToPrivatePropertyRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Zing\CodingStandard\Set\RectorSetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(RectorSetList::CUSTOM);
    $containerConfigurator->import(PHPUnitSetList::PHPUNIT_CODE_QUALITY);
    $containerConfigurator->import(SetList::PHP_70);
    $containerConfigurator->import(SetList::PHP_71);
    $containerConfigurator->import(SetList::PHP_72);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(
        Option::SKIP,
        [
            FinalizeClassesWithoutChildrenRector::class,
            ChangeReadOnlyVariableWithDefaultValueToConstantRector::class,
            AddSeeTestAnnotationRector::class,
            RepeatedLiteralToClassConstantRector::class,
            RenameParamToMatchTypeRector::class,
            RenameVariableToMatchMethodCallReturnTypeRector::class,
            EncapsedStringsToSprintfRector::class,
            PrivatizeLocalPropertyToPrivatePropertyRector::class,
            ChangeOrIfReturnToEarlyReturnRector::class,
            PrivatizeLocalGetterToPropertyRector::class,
            ChangeAndIfToEarlyReturnRector::class,
            VarConstantCommentRector::class,
            RemoveUselessParamTagRector::class,
            RemoveUselessReturnTagRector::class,
        ]
    );
    $parameters->set(
        Option::PATHS,
        [__DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/ecs.php', __DIR__ . '/rector.php']
    );
};
