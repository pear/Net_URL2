<?php

/**
 * Net_URL2, a class representing a URL as per RFC 3986.
 *
 * PHP version 5
 *
 * @category Networking
 * @package  Net_URL2
 * @author   Some Pear Developers <pear@php.net>
 * @license  https://spdx.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link     https://tools.ietf.org/html/rfc3986
 */

if (!class_exists('PHPUnit_Framework_TestCase'))
{
    class_alias('PHPUnit\Framework\TestCase', 'PHPUnit_Framework_TestCase');
}

function shutdown($error_log)
{
    if (is_file($error_log))
    {
        printf("%s:\n", $error_log);
        fpassthru(fopen($error_log, 'r'));
        printf("%s: found. STOP.\n", $error_log);
        exit(1);
    }
}

ini_set('error_reporting', '-1');
ini_set('error_log', dirname(dirname(__FILE__)) . vsprintf('/.php-%d.%d.%d-error.log', sscanf(PHP_VERSION, '%d.%d.%d')));
ini_set('log_errors', '1');
shutdown(ini_get('error_log'));
register_shutdown_function('shutdown', ini_get('error_log'));
ini_set('display_errors', 'stderr');

require dirname(__FILE__) . '/../Net/URL2.php';
