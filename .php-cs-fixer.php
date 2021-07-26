<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony'                   => true,
        'binary_operator_spaces'     => [
            'default'   => 'align_single_space_minimal',
            'operators' => [
                '='  => 'align',
                '=>' => 'align',
            ],
        ],
        'native_function_invocation' => [
            'include' => [
                '@compiler_optimized',
            ],
            'scope'   => 'namespaced',
        ],
    ])
    ->setFinder($finder);
