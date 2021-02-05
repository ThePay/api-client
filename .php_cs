<?php

return PhpCsFixer\Config::create()
    ->setRules(array(
        '@PSR12' => true,
        '@PSR1' => true,
        'braces' => [
            'allow_single_line_closure' => false,
            'position_after_functions_and_oop_constructs' => 'next',
            'position_after_control_structures' => 'same',
            'position_after_anonymous_constructs' => 'same',
        ],
        'binary_operator_spaces' => true,
        'unary_operator_spaces' => true,
        'no_trailing_whitespace' => true,
        'concat_space' => ['spacing' => 'one'],
        'no_singleline_whitespace_before_semicolons' => true,
        'no_whitespace_before_comma_in_array' => true,
        'whitespace_after_comma_in_array' => true,
        'not_operator_with_space' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_unused_imports' => true,
        'not_operator_with_space' => true,
        'trailing_comma_in_multiline_array' => true,
        'normalize_index_brace' => true,
        'cast_spaces' => true,
        // old php compatibility
        'array_syntax' => ['syntax' => 'long'],
        'list_syntax' => ['syntax' => 'long'],
        'visibility_required' => ['property', 'method'],
    ))
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__.'/src')
            ->in(__DIR__.'/tests')
    );
