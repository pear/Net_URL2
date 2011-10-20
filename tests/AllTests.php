<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Net_URL2_AllTests::main');
}


require_once 'PHPUnit/Autoload.php';

chdir(dirname(__FILE__) .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
require_once 'Net/URL2Test.php';


class Net_URL2_AllTests
{
    public static function main()
    {

        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Net_URL2 tests');
        /** Add testsuites, if there is. */
        $suite->addTestSuite('Net_URL2Test');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Net_URL2_AllTests::main') {
    Net_URL2_AllTests::main();
}
?>
