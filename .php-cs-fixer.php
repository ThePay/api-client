<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

$header = <<<'EOF'
This file is part of PHP CS Fixer.
(c) Fabien Potencier <fabien@symfony.com>
    Dariusz Rumiński <dariusz.ruminski@gmail.com>
This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

$finder = PhpCsFixer\Finder::create()
            ->in(__DIR__.'/src')
            ->in(__DIR__.'/tests')
;

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules(array(
        '@PSR12' => true,
        '@PSR1' => true,
        'braces' => array(
            'allow_single_line_closure' => false,
            'position_after_functions_and_oop_constructs' => 'next',
            'position_after_control_structures' => 'same',
            'position_after_anonymous_constructs' => 'same',
        ),
        'binary_operator_spaces' => true,
        'unary_operator_spaces' => true,
        'no_trailing_whitespace' => true,
        'concat_space' => array('spacing' => 'one'),
        'no_singleline_whitespace_before_semicolons' => true,
        'no_whitespace_before_comma_in_array' => true,
        'whitespace_after_comma_in_array' => true,
        'not_operator_with_space' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_unused_imports' => true,
        'not_operator_with_space' => true,
        'trailing_comma_in_multiline' => true,
        'normalize_index_brace' => true,
        'cast_spaces' => true,
        // old php compatibility
        'array_syntax' => array('syntax' => 'long'),
        'list_syntax' => array('syntax' => 'long'),
        'visibility_required' => array('elements' => array('method', 'property')),
    ))
    ->setFinder($finder)
;

// special handling of fabbot.io service if it's using too old PHP CS Fixer version
if (false !== getenv('FABBOT_IO')) {
    try {
        PhpCsFixer\FixerFactory::create()
            ->registerBuiltInFixers()
            ->registerCustomFixers($config->getCustomFixers())
            ->useRuleSet(new PhpCsFixer\RuleSet($config->getRules()))
        ;
    } catch (PhpCsFixer\ConfigurationException\InvalidConfigurationException $e) {
        $config->setRules(array());
    } catch (UnexpectedValueException $e) {
        $config->setRules(array());
    } catch (InvalidArgumentException $e) {
        $config->setRules(array());
    }
}

return $config;
