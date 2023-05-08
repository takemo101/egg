<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.13.2|configurator
 * you can change this configuration by importing this file.
 *
 * ref https://tech.012grp.co.jp/entry/code_formatter
 */
$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PHP82Migration' => true,
        '@PSR12' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude([
                'vendor',
                'storage',
                'mysql',
                'docker',
                '.github',
            ])
            ->in(__DIR__)
    );
