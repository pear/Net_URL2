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

/**
 * Test class for Net_URL2.
 *
 * @category Networking
 * @package  Net_URL2
 * @author   Some Pear Developers <pear@php.net>
 * @license  https://spdx.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version  Release: @package_version@
 * @link     https://pear.php.net/package/Net_URL2
 */
class Net_URL2Test extends PHPUnit_Framework_TestCase
{
    /**
     * Tests setting an empty userinfo part
     * Also: Regression test for Bug #20013
     *
     * @return void
     * @link https://pear.php.net/bugs/bug.php?id=20013
     */
    public function testSetEmptyUserinfo()
    {
        $url = new Net_URL2('http://@www.example.com/');
        $this->assertSame('http://www.example.com/', $url->getURL());

        $url = new Net_URL2('http://www.example.com/');
        $this->assertSame('http://www.example.com/', $url->getURL());
        $url->setUserinfo('');
        $this->assertSame('http://www.example.com/', $url->getURL());
    }

    /**
     * Tests setQueryVariable().
     *
     * @return void
     */
    public function testSetQueryVariable()
    {

        $url = new Net_URL2('http://www.example.com/');
        $url->setQueryVariable('pear', 'fun');
        $this->assertEquals($url->getURL(), 'http://www.example.com/?pear=fun');
    }

    /**
     * Tests setQueryVariables().
     *
     * @return void
     */
    public function testSetQueryVariables()
    {

        $url = new Net_URL2('http://www.example.com/');
        $url->setQueryVariables(array('pear' => 'fun'));
        $this->assertEquals('http://www.example.com/?pear=fun', $url->getURL());
        $url->setQueryVariables(array('pear' => 'fun for sure'));
        $this->assertEquals(
            'http://www.example.com/?pear=fun%20for%20sure', $url->getURL()
        );
    }

    /**
     * Tests unsetQueryVariable()
     *
     * @return void
     */
    public function testUnsetQueryVariable()
    {
        $url = new Net_URL2(
            'http://www.example.com/?name=david&pear=fun&fish=slippery'
        );

        $removes = array(
            'pear' => 'http://www.example.com/?name=david&fish=slippery',
            'name' => 'http://www.example.com/?fish=slippery',
            'fish' => 'http://www.example.com/',
        );

        foreach ($removes as $name => $expected) {
            $url->unsetQueryVariable($name);
            $this->assertEquals($expected, $url);
        }
    }

    /**
     * Tests setQuery().
     *
     * @return void
     */
    public function testSetQuery()
    {

        $url = new Net_URL2('http://www.example.com/');
        $url->setQuery('flapdoodle&dilly%20all%20day');
        $this->assertEquals(
            $url->getURL(), 'http://www.example.com/?flapdoodle&dilly%20all%20day'
        );
    }

    /**
     * Tests getQuery().
     *
     * @return void
     */
    public function testGetQuery()
    {

        $url = new Net_URL2('http://www.example.com/?foo');
        $this->assertEquals($url->getQuery(), 'foo');
        $url = new Net_URL2('http://www.example.com/?pear=fun&fruit=fruity');
        $this->assertEquals($url->getQuery(), 'pear=fun&fruit=fruity');
    }

    /**
     * Tests setScheme().
     *
     * @return void
     */
    public function testSetScheme()
    {

        $url = new Net_URL2('http://www.example.com/');
        $url->setScheme('ftp');
        $this->assertEquals($url->getURL(), 'ftp://www.example.com/');
        $url->setScheme('gopher');
        $this->assertEquals($url->getURL(), 'gopher://www.example.com/');
    }

    /**
     * Tests setting the fragment.
     *
     * @return void
     */
    public function testSetFragment()
    {

        $url = new Net_URL2('http://www.example.com/');
        $url->setFragment('pear');
        $this->assertEquals('http://www.example.com/#pear', $url->getURL());
    }

    /**
     * Test the resolve() function.
     *
     * @return void
     */
    public function testResolve()
    {
        // Examples from RFC 3986, section 5.4.
        // relative URL => absolute URL
        $tests   = array(
            'g:h'           => 'g:h',
            'g'             => 'http://a/b/c/g',
            './g'           => 'http://a/b/c/g',
            'g/'            => 'http://a/b/c/g/',
            '/g'            => 'http://a/g',
            '//g'           => 'http://g',
            '?y'            => 'http://a/b/c/d;p?y',
            'g?y'           => 'http://a/b/c/g?y',
            '#s'            => 'http://a/b/c/d;p?q#s',
            'g#s'           => 'http://a/b/c/g#s',
            'g?y#s'         => 'http://a/b/c/g?y#s',
            ';x'            => 'http://a/b/c/;x',
            'g;x'           => 'http://a/b/c/g;x',
            'g;x?y#s'       => 'http://a/b/c/g;x?y#s',
            ''              => 'http://a/b/c/d;p?q',
            '.'             => 'http://a/b/c/',
            './'            => 'http://a/b/c/',
            '..'            => 'http://a/b/',
            '../'           => 'http://a/b/',
            '../g'          => 'http://a/b/g',
            '../..'         => 'http://a/',
            '../../'        => 'http://a/',
            '../../g'       => 'http://a/g',
            '../../../g'    => 'http://a/g',
            '../../../../g' => 'http://a/g',
            '/./g'          => 'http://a/g',
            '/../g'         => 'http://a/g',
            'g.'            => 'http://a/b/c/g.',
            '.g'            => 'http://a/b/c/.g',
            'g..'           => 'http://a/b/c/g..',
            '..g'           => 'http://a/b/c/..g',
            './../g'        => 'http://a/b/g',
            './g/.'         => 'http://a/b/c/g/',
            'g/./h'         => 'http://a/b/c/g/h',
            'g/../h'        => 'http://a/b/c/h',
            'g;x=1/./y'     => 'http://a/b/c/g;x=1/y',
            'g;x=1/../y'    => 'http://a/b/c/y',
            'g?y/./x'       => 'http://a/b/c/g?y/./x',
            'g?y/../x'      => 'http://a/b/c/g?y/../x',
            'g#s/./x'       => 'http://a/b/c/g#s/./x',
            'g#s/../x'      => 'http://a/b/c/g#s/../x',
            'http:g'        => 'http:g',
        );
        $baseURL = 'http://a/b/c/d;p?q';
        $base    = new Net_URL2($baseURL);
        foreach ($tests as $relativeURL => $absoluteURL) {
            $this->assertEquals($absoluteURL, $base->resolve($relativeURL));
        }

        $base        = new Net_URL2(
            $baseURL, array(Net_URL2::OPTION_STRICT => false)
        );
        $relativeURL = 'http:g';
        $this->assertEquals('http://a/b/c/g', $base->resolve($relativeURL));

        // resolving a relative to a relative URL throws an exception
        $base = new Net_URL2('news.html?category=arts');
        $this->addToAssertionCount(1);
        try {
            $base->resolve('../arts.html#section-2.4');
        } catch (Exception $e) {
            return;
        }
        $this->fail('Expected exception not thrown.');
    }

    /**
     * Test UrlEncoding
     *
     * @return void
     * @link   https://pear.php.net/bugs/bug.php?id=18267
     */
    public function testUrlEncoding()
    {
        $url = new Net_URL2('http://localhost/bug.php');
        $url->setQueryVariables(
            array('indexed' => array(
                    'first value', 'second value', array('foo', 'bar'),
            ))
        );
        $this->assertEquals(
            'http://localhost/bug.php?indexed[0]=first%20value&indexed[1]'.
            '=second%20value&indexed[2][0]=foo&indexed[2][1]=bar',
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

    /**
     * Brackets for array query variables
     *
     * @return void
     */
    public function testUseBrackets()
    {
        $url = new Net_URL2('http://example.org/');
        $url->setQueryVariables(array('foo' => array('bar', 'foobar')));
        $this->assertEquals(
            'http://example.org/?foo[0]=bar&foo[1]=foobar',
            strval($url)
        );
    }

    /**
     * Do not use brackets for query variables passed as array
     *
     * @return void
     */
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
     * A dataProvider for pairs of paths with dot segments and
     * their form when removed.
     *
     * @see testRemoveDotSegments
     * @return array
     */
    public function providePath()
    {
        // The numbers behind are in reference to sections
        // in RFC 3986 5.2.4. Remove Dot Segments
        return array(
            array('../', ''),   // 2. A.
            array('./', ''),    // 2. A.
            array('/./', '/'),  // 2. B.
            array('/.', '/'),   // 2. B.
            array('/../', '/'), // 2. C.
            array('/..', '/'),  // 2. C.
            array('..', ''),    // 2. D.
            array('.', ''),     // 2. D.
            array('a', 'a'),    // 2. E.
            array('/a', '/a'),  // 2. E.
            array('/a/b/c/./../../g', '/a/g'),    // 3.
            array('mid/content=5/../6', 'mid/6'), // 3.
            array('../foo/bar.php', 'foo/bar.php'),
            array('/foo/../bar/boo.php', '/bar/boo.php'),
            array('/boo/..//foo//bar.php', '//foo//bar.php'),
            array('/./foo/././bar.php', '/foo/bar.php'),
            array('./.', ''),
        );
    }

    /**
     * Test removal of dot segments
     *
     * @param string $path      Path
     * @param string $assertion Assertion
     *
     * @dataProvider providePath
     * @return void
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
     * @link   https://pear.php.net/bugs/bug.php?id=17036
     */
    public function testQueryVariables()
    {
        $queryString = 'start=10&test[0][first][1.1][20]=coucou';
        $url         = new Net_URL2('?' . $queryString);
        $vars        = array();
        parse_str($url->getQuery(), $vars);

        $this->assertEquals('10', $vars['start']);
        $this->assertEquals('coucou', $vars['test'][0]['first']['1.1'][20]);
    }

    /**
     * This is a regression test to test that resolve() does
     * merge the path if the base path is empty as the opposite
     * was reported as Bug #19176 on 2011-12-31 02:07 UTC
     *
     * @return void
     */
    public function test19176()
    {
        $foo  = new Net_URL2('http://www.example.com');
        $test = $foo->resolve('test.html')->getURL();
        $this->assertEquals('http://www.example.com/test.html', $test);
    }

    /**
     * This is a regression test that removeDotSegments('0') is
     * working as it was reported as not-working in Bug #19315
     * on 2012-03-04 04:18 UTC.
     *
     * @return void
     */
    public function test19315()
    {
        $actual = Net_URL2::removeDotSegments('0');
        $this->assertSame('0', $actual);

        $nonStringObject = (object)array();
        try {
            Net_URL2::removeDotSegments($nonStringObject);
        } catch (PHPUnit_Framework_Error $error) {
            $this->addToAssertionCount(1);
        }

        if (!isset($error)) {
            $this->fail('Failed to verify that error was given.');
        }
        unset($error);
    }

    /**
     * This is a regression test to test that recovering from
     * a wrongly encoded URL is possible.
     *
     * It was requested as Request #19684 on 2011-12-31 02:07 UTC
     * that redirects containing spaces should work.
     *
     * @return void
     */
    public function test19684()
    {
        // Location: URL obtained Thu, 25 Apr 2013 20:51:31 GMT
        $urlWithSpace = 'http://www.sigmaaldrich.com/catalog/search?interface=CAS N'
            . 'o.&term=108-88-3&lang=en&region=US&mode=match+partialmax&N=0+2200030'
            . '48+219853269+219853286';

        $urlCorrect = 'http://www.sigmaaldrich.com/catalog/search?interface=CAS%20N'
            . 'o.&term=108-88-3&lang=en&region=US&mode=match+partialmax&N=0+2200030'
            . '48+219853269+219853286';

        $url = new Net_URL2($urlWithSpace);

        $this->assertTrue($url->isAbsolute());

        $urlPart = parse_url($urlCorrect, PHP_URL_PATH);
        $this->assertSame($urlPart, $url->getPath());

        $urlPart = parse_url($urlCorrect, PHP_URL_QUERY);
        $this->assertSame($urlPart, $url->getQuery());

        $this->assertSame($urlCorrect, (string) $url);

        $input    = 'http://example.com/get + + to my nose/';
        $expected = 'http://example.com/get%20+%20+%20to%20my%20nose/';
        $actual   = new Net_URL2($input);
        $this->assertEquals($expected, $actual);
        $actual->normalize();
    }

    /**
     * data provider of list of equivalent URLs.
     *
     * @see testNormalize
     * @see testConstructSelf
     * @return array
     */
    public function provideEquivalentUrlLists()
    {
        return array(
            // String equivalence:
            array('http://example.com/', 'http://example.com/'),

            // Originally first dataset:
            array('http://www.example.com/%9a', 'http://www.example.com/%9A'),

            // Example from RFC 3986 6.2.2.:
            array('example://a/b/c/%7Bfoo%7D', 'eXAMPLE://a/./b/../b/%63/%7bfoo%7d'),

            // Example from RFC 3986 6.2.2.1.:
            array('HTTP://www.EXAMPLE.com/', 'http://www.example.com/'),

            // Example from RFC 3986 6.2.3.:
            array(
                'http://example.com', 'http://example.com/',
                'http://example.com:/', 'http://example.com:80/'
            ),
        );
    }

    /**
     * This is a coverage test to invoke the normalize()
     * method.
     *
     * @return void
     *
     * @dataProvider provideEquivalentUrlLists
     */
    public function testNormalize()
    {
        $urls = func_get_args();

        $this->assertGreaterThanOrEqual(2, count($urls));

        $last = null;

        foreach ($urls as $index => $url) {
            $url = new Net_Url2($url);
            $url->normalize();
            if ($index) {
                $this->assertSame((string)$last, (string)$url);
            }
            $last = $url;
        }
    }

    /**
     * This is a coverage test to invoke __get and __set
     *
     * @covers Net_URL2::__get
     * @covers Net_URL2::__set
     * @return void
     */
    public function testMagicSetGet()
    {
        $url = new Net_URL2('');

        $property = 'authority';
        $url->$property = $value = 'value';
        $this->assertEquals($value, $url->$property);

        $property = 'unsetProperty';
        $url->$property = $value;
        $this->assertEquals(false, $url->$property);
    }

    /**
     * Tests Net_URL2 ctors URL parameter works with objects implementing
     * __toString().
     *
     * @dataProvider provideEquivalentUrlLists
     * @coversNothing
     * @return void
     */
    public function testConstructSelf()
    {
        $urls = func_get_args();
        foreach ($urls as $url) {
            $urlA = new Net_URL2($url);
            $urlB = new Net_URL2($urlA);
            $this->assertSame((string) $urlA, (string) $urlB);
        }
    }

    /**
     * This is a feature test to see that the userinfo's data is getting
     * encoded as outlined in #19684.
     *
     * @covers Net_URL2::setAuthority
     * @return void
     */
    public function testEncodeDataUserinfoAuthority()
    {
        $url = new Net_URL2('http://john doe:secret@example.com/');
        $this->assertSame('http://john%20doe:secret@example.com/', (string) $url);
    }

    /**
     * This is a regression test to test that using the
     * host-name "0" does work with getAuthority()
     *
     * It was reported as Bug #20156 on 2013-12-27 22:56 UTC
     * that setAuthority() with "0" as host would not work
     *
     * @covers Net_URL2::setAuthority
     * @covers Net_URL2::getAuthority
     * @covers Net_URL2::setHost
     * @return void
     */
    public function test20156()
    {
        $url = new Net_URL2('http://user:pass@example.com:127/');
        $host = '0';
        $url->setHost($host);
        $this->assertSame('user:pass@0:127', $url->getAuthority());

        $url->setHost(false);
        $this->assertSame(false, $url->getAuthority());

        $url->setAuthority($host);
        $this->assertSame($host, $url->getAuthority());
    }

    /**
     * This is a regression test to test that setting "0" as path
     * does not break normalize().
     *
     * It was reported as Bug #20157 on 2013-12-27 23:42 UTC that
     * normalize() with "0" as path would not work.
     *
     * @covers Net_URL2::normalize
     * @return void
     */
    public function test20157()
    {
        $subject = 'http://example.com';
        $url = new Net_URL2($subject);
        $url->setPath('0');
        $url->normalize();
        $this->assertSame("$subject/0", (string)$url);
    }

    /**
     * This is a regression test to ensure that fragment-only references can be
     * resolved to a non-absolute Base-URI.
     *
     * It was reported as Bug #20158 2013-12-28 14:49 UTC that fragment-only
     * references would not be resolved to non-absolute base URI
     *
     * @covers Net_URL2::resolve
     * @covers Net_URL2::_isFragmentOnly
     * @return void
     */
    public function test20158()
    {
        $base = new Net_URL2('myfile.html');
        $resolved = $base->resolve('#world');
        $this->assertSame('myfile.html#world', (string) $resolved);
    }

    /**
     * This is a regression test to ensure that authority and path are properly
     * combined when the path does not start with a slash which is the separator
     * character between authority and path.
     *
     * It was reported as Bug #20159 2013-12-28 17:18 UTC that authority
     * would not be terminated by slash
     *
     * @covers Net_URL2::getUrl
     * @return void
     */
    public function test20159()
    {
        $url = new Net_URL2('index.html');
        $url->setHost('example.com');
        $this->assertSame('//example.com/index.html', (string) $url);
    }
}
