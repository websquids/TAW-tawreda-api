<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
  ->in([
    __DIR__ . '/app',
    __DIR__ . '/routes',
    __DIR__ . '/database',
    __DIR__ . '/tests',
  ])
  ->name('*.php')
  ->notName('*.blade.php') // Avoid formatting Blade files
  ->ignoreDotFiles(true)
  ->ignoreVCS(true);

return (new Config())
  ->setRules([
    '@PSR12' => false,
    'braces' => [
      'position_after_functions_and_oop_constructs' => 'same', // Same-line curly braces
    ],
    'array_syntax' => ['syntax' => 'short'], // Short array syntax
    // 'binary_operator_spaces' => ['default' => 'align_single_space'], // Align operators
    'trailing_comma_in_multiline' => [
      'elements' => ['arrays', 'arguments'], // Add trailing commas
    ],
    'no_unused_imports' => true, // Remove unused imports
    'single_blank_line_at_eof' => true, // Ensure single blank line at EOF
    'no_trailing_whitespace' => true, // Remove trailing whitespace
  ])
  ->setFinder($finder);
