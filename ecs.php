<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ReturnNotation\ReturnAssignmentFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Zing\CodingStandard\Set\ECSSetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->sets([ECSSetList::PHP_72, ECSSetList::CUSTOM]);
    $parameters = $ecsConfig->parameters();
    $ecsConfig->parallel();
    $ecsConfig->skip([
        // bug
        ReturnAssignmentFixer::class,
    ]);
    $parameters->set(
        Option::PATHS,
        [__DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/ecs.php', __DIR__ . '/rector.php']
    );
};
