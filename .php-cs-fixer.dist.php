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
    '@PSR12' => false, // Disable strict PSR-12 compliance
    'braces' => [
      'position_after_functions_and_oop_constructs' => 'same', // Same-line curly braces
    ],
    'array_syntax' => ['syntax' => 'short'], // Short array syntax
    // 'binary_operator_spaces' => ['default' => 'align_single_space'], // Align operators
    'trailing_comma_in_multiline' => [
      'elements' => ['arrays', 'arguments'], // Ensure trailing commas in multiline arrays
    ],
    'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'], // No multiline semicolons
    'method_argument_space' => [
      'on_multiline' => 'ensure_fully_multiline', // Correct indentation for method arguments
    ],
    'binary_operator_spaces' => [
      'default' => 'single_space',
    ],
    'array_indentation' => true, // Ensure arrays are properly indented
    'indentation_type' => true, // Use spaces for indentation
    'phpdoc_indent' => true, // Consistent PHPDoc indentation
    'no_unused_imports' => true, // Remove unused imports
    'single_blank_line_at_eof' => true, // Single blank line at EOF
    'no_trailing_whitespace' => true, // No trailing whitespace
  ])
  ->setIndent('  ') // Set indentation to 2 spaces
  ->setLineEnding("\n") // Ensure consistent line endings
  ->setFinder($finder);
