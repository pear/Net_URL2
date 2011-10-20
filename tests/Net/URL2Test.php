<?php
// Call Net_URL2Test::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Net_URL2Test::main");
}

require_once 'PHPUnit/Autoload.php';

$classFile = '';
if (strstr('@package_version@', '@package')) {
    // we run from a svn checkout
    $classFile .= __DIR__ . './../../Net/URL2.php';
} else {
    $classFile .= 'Net/URL2.php';
}
require_once $classFile;

/**
 * Test class for Net_URL2.
 */
class Net_URL2Test extends PHPUnit_Framework_TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main()
    {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("Net_URL2Test");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp() {
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown() {
    }

    /**
     * Tests setQueryVariable().
     */
    public function testSetQueryVariable() {
        $url = new Net_URL2('http://www.example.com/');
        $url->setQueryVariable('pear','fun');
        $this->assertEquals($url->getURL(), 'http://www.example.com/?pear=fun');
    }

    /**
     * Tests setQueryVariables().
     */
    public function testSetQueryVariables() {
        $url = new Net_URL2('http://www.example.com/');
        $url->setQueryVariables(array('pear'=>'fun'));
        $this->assertEquals('http://www.example.com/?pear=fun', $url->getURL());
        $url->setQueryVariables(array('pear'=>'fun for sure'));
        $this->assertEquals('http://www.example.com/?pear=fun%20for%20sure', $url->getURL());
    }

    /**
     * Tests unsetQueryVariable()
     */
    public function testUnsetQueryVariable() {
        $url = new Net_URL2('http://www.example.com/?name=david&pear=fun&fish=slippery');
        $url->unsetQueryVariable('pear');
        $this->assertEquals($url->getURL(), 'http://www.example.com/?name=david&fish=slippery');
        $url->unsetQueryVariable('name');
        $this->assertEquals($url->getURL(), 'http://www.example.com/?fish=slippery');
        $url->unsetQueryVariable('fish');
        $this->assertEquals($url->getURL(), 'http://www.example.com/');
    }

    /**
     * Tests setQuery().
     */
    public function testSetQuery() {
        $url = new Net_URL2('http://www.example.com/');
        $url->setQuery('flapdoodle&dilly%20all%20day');
        $this->assertEquals($url->getURL(), 'http://www.example.com/?flapdoodle&dilly%20all%20day');
    }

    /**
     * Tests getQuery().
     */
    public function testGetQuery() {
        $url = new Net_URL2('http://www.example.com/?foo');
        $this->assertEquals($url->getQuery(),'foo');
        $url = new Net_URL2('http://www.example.com/?pear=fun&fruit=fruity');
        $this->assertEquals($url->getQuery(),'pear=fun&fruit=fruity');
    }

    /**
     * Tests setScheme().
     */
    public function testSetScheme() {
        $url = new Net_URL2('http://www.example.com/');
        $url->setScheme('ftp');
        $this->assertEquals($url->getURL(), 'ftp://www.example.com/');
        $url->setScheme('gopher');
        $this->assertEquals($url->getURL(), 'gopher://www.example.com/');
    }

    /**
     * Tests setting the fragment.
     */
    public function testSetFragment() {
        $url = new Net_URL2('http://www.example.com/');
        $url->setFragment('pear');
        $this->assertEquals('http://www.example.com/#pear', $url->getURL());
    }

    /**
     * Test the resolve() function.
     */
    public function testResolve()
    {
        // Examples from RFC 3986, section 5.4.
        // relative URL => absolute URL
        $tests = array(
            ""              =>  "http://a/b/c/d;p?q",
            "g:h"           =>  "g:h",
            "g"             =>  "http://a/b/c/g",
            "./g"           =>  "http://a/b/c/g",
            "g/"            =>  "http://a/b/c/g/",
            "/g"            =>  "http://a/g",
            "//g"           =>  "http://g",
            "?y"            =>  "http://a/b/c/d;p?y",
            "g?y"           =>  "http://a/b/c/g?y",
            "#s"            =>  "http://a/b/c/d;p?q#s",
            "g#s"           =>  "http://a/b/c/g#s",
            "g?y#s"         =>  "http://a/b/c/g?y#s",
            ";x"            =>  "http://a/b/c/;x",
            "g;x"           =>  "http://a/b/c/g;x",
            "g;x?y#s"       =>  "http://a/b/c/g;x?y#s",
            ""              =>  "http://a/b/c/d;p?q",
            "."             =>  "http://a/b/c/",
            "./"            =>  "http://a/b/c/",
            ".."            =>  "http://a/b/",
            "../"           =>  "http://a/b/",
            "../g"          =>  "http://a/b/g",
            "../.."         =>  "http://a/",
            "../../"        =>  "http://a/",
            "../../g"       =>  "http://a/g",
            "../../../g"    =>  "http://a/g",
            "../../../../g" =>  "http://a/g",
            "/./g"          =>  "http://a/g",
            "/../g"         =>  "http://a/g",
            "g."            =>  "http://a/b/c/g.",
            ".g"            =>  "http://a/b/c/.g",
            "g.."           =>  "http://a/b/c/g..",
            "..g"           =>  "http://a/b/c/..g",
            "./../g"        =>  "http://a/b/g",
            "./g/."         =>  "http://a/b/c/g/",
            "g/./h"         =>  "http://a/b/c/g/h",
            "g/../h"        =>  "http://a/b/c/h",
            "g;x=1/./y"     =>  "http://a/b/c/g;x=1/y",
            "g;x=1/../y"    =>  "http://a/b/c/y",
            "g?y/./x"       =>  "http://a/b/c/g?y/./x",
            "g?y/../x"      =>  "http://a/b/c/g?y/../x",
            "g#s/./x"       =>  "http://a/b/c/g#s/./x",
            "g#s/../x"      =>  "http://a/b/c/g#s/../x",
            "http:g"        =>  "http:g",
        );  
        $baseURL = 'http://a/b/c/d;p?q';
        $base = new Net_URL2($baseURL);
        foreach ($tests as $relativeURL => $absoluteURL) {
            $this->assertEquals($absoluteURL, $base->resolve($relativeURL)->getURL());
        }

        $base = new Net_URL2($baseURL, array(Net_URL2::OPTION_STRICT => false));
        $relativeURL = 'http:g';
        $this->assertEquals('http://a/b/c/g', $base->resolve($relativeURL)->getURL());
    }

    /**
     * @return void
     * @link   http://pear.php.net/bugs/bug.php?id=18267
     */
    public function testUrlEncoding()
    {
        $url = new Net_URL2('http://localhost/bug.php');
        $url->setQueryVariables(
            array(
                'indexed' => array('first value', 'second value', array('foo', 'bar'))
            )
        );
        $this->assertEquals(
            'http://localhost/bug.php?indexed[0]=first%20value&indexed[1]=second%20value&indexed[2][0]=foo&indexed[2][1]=bar',
            strval($url)
        );
    }

    /**
     * A test to verify that keys in QUERY_STRING are encoded by default.
     *
     * @return void
     * @see    Net_URL2::OPTION_ENCODE_KEYS
     * @see    Net_URL2::buildQuery()
     */
    public function testEncodeKeys()
    {
        $url = new Net_URL2('http://example.org');
        $url->setQueryVariables(array('helgi rulez' => 'till too'));
        $this->assertEquals(
            'http://example.org?helgi%20rulez=till%20too',
            strval($url)
        );
    }

    /**
     * A test to verify that keys in QUERY_STRING are not encoded when we supply
     * 'false' via {@link Net_URL2::__construct()}.
     *
     * @return void
     * @see    Net_URL2::OPTION_ENCODE_KEYS
     * @see    Net_URL2::buildQuery()
     */
    public function testDontEncodeKeys()
    {
        $url = new Net_URL2(
            'http://example.org',
            array(Net_URL2::OPTION_ENCODE_KEYS => false)
        );
        $url->setQueryVariables(array('till rulez' => 'helgi too'));
        $this->assertEquals(
            'http://example.org?till rulez=helgi%20too',
            strval($url)
        );
    }

    public function testUseBrackets()
    {
        $url = new Net_URL2('http://example.org/');
        $url->setQueryVariables(array('foo' => array('bar', 'foobar')));
        $this->assertEquals(
            'http://example.org/?foo[0]=bar&foo[1]=foobar',
            strval($url)
        );
    }

    public function testDontUseBrackets()
    {
        $url = new Net_URL2(
            'http://example.org/',
            array(Net_URL2::OPTION_USE_BRACKETS => false)
        );
        $url->setQueryVariables(array('foo' => array('bar', 'foobar')));
        $this->assertEquals(
            'http://example.org/?foo=bar&foo=foobar',
            strval($url)
        );
    }

    /**
     * A dataProvider for {@link self::testRemoveDotSegments()}.
     *
     * @return array
     */
    public static function pathProvider()
    {
        return array(
            //array('../foo/bar.php', '../foo/bar.php'),
	        array('/foo/../bar/boo.php', '/bar/boo.php'),
            array('/boo/..//foo//bar.php', '//foo//bar.php'),
            array('/./foo/././bar.php', '/foo/bar.php'),
            //array('./.', '/'),
        );
    }

    /**
     * @dataProvider pathProvider
     */
    public function testRemoveDotSegments($path, $assertion)
    {
        $this->assertEquals($assertion, Net_URL2::removeDotSegments($path));
    }

    /**
     * This is some example code from a bugreport. Trying to proof that
     * the parsing works indeed.
     *
     * @return void
     * @link   http://pear.php.net/bugs/bug.php?id=17036
     */
    public function testQueryVariables()
    {
        $queryString = 'start=10&test[0][first][1.1][20]=coucou';
        $url = new Net_URL2("?{$queryString}");
        $vars = array(); parse_str($url->getQuery(), $vars);

        $this->assertEquals('10', $vars['start']);
        $this->assertEquals('coucou', $vars['test'][0]['first']['1.1'][20]);
    }
}

// Call Net_URL2Test::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Net_URL2Test::main") {
    Net_URL2Test::main();
}
