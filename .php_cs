<?php

return PhpCsFixer\Config::create()
    ->setRules(array(
        '@PSR2' => true,
        '@PSR1' => true,
        'array_syntax' => array('syntax' => 'long'),
        'not_operator_with_space' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'no_unused_imports' => true,
    ))
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__.'/src')
            ->in(__DIR__.'/tests')
    );
