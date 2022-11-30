<?php

// ecs.php
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ControlStructure\SwitchCaseSemicolonToColonFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestClassRequiresCoversFixer;
use PhpCsFixer\Fixer\Semicolon\MultilineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    // A. full sets
    $containerConfigurator->import(SetList::PHP_CS_FIXER);

    // B. standalone rule
    $services = $containerConfigurator->services();
    $services->set(ArraySyntaxFixer::class)
        ->call('configure', [[
            'syntax' => 'short',
        ]]);

    $services->set(MethodArgumentSpaceFixer::class)
        ->call('configure', [[
            'on_multiline' => 'ensure_fully_multiline',
        ]]);

    $services->set(NativeFunctionInvocationFixer::class)
        ->call('configure', [[
            'scope' => 'namespaced',
            'include' => ['@compiler_optimized'],
        ]]);

    $services->set(DeclareStrictTypesFixer::class);
    $services->set(ConcatSpaceFixer::class)->call('configure', [['spacing' => 'one']]);
    $services->set(MultilineWhitespaceBeforeSemicolonsFixer::class);
    $services->remove(YodaStyleFixer::class);
    $services->remove(PhpUnitTestClassRequiresCoversFixer::class);
    $services->remove(SwitchCaseSemicolonToColonFixer::class);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__.'/src', __DIR__.'/app', __DIR__.'/tests']);

    // configure cache paths & namespace - useful for Gitlab CI caching, where getcwd() produces always different path
    // [default: sys_get_temp_dir() . '/_changed_files_detector_tests']
    $parameters->set(Option::CACHE_DIRECTORY, '.ecs_cache');
    // [default: \Nette\Utils\Strings::webalize(getcwd())']
    $parameters->set(Option::CACHE_NAMESPACE, 'my_project_namespace');

    $parameters->set(Option::SKIP, [
        __DIR__.'/var',
        __DIR__.'/tests/bootstrap.php',
    ]);
};
