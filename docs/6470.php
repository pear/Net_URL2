<?php
// +-----------------------------------------------------------------------+
// | Copyright (c) 2002-2003, Richard Heyes                                     |
// | All rights reserved.                                                  |
// |                                                                       |
// | Redistribution and use in source and binary forms, with or without    |
// | modification, are permitted provided that the following conditions    |
// | are met:                                                              |
// |                                                                       |
// | o Redistributions of source code must retain the above copyright      |
// |   notice, this list of conditions and the following disclaimer.       |
// | o Redistributions in binary form must reproduce the above copyright   |
// |   notice, this list of conditions and the following disclaimer in the |
// |   documentation and/or other materials provided with the distribution.|
// | o The names of the authors may not be used to endorse or promote      |
// |   products derived from this software without specific prior written  |
// |   permission.                                                         |
// |                                                                       |
// | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS   |
// | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT     |
// | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR |
// | A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT  |
// | OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, |
// | SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT      |
// | LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, |
// | DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY |
// | THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT   |
// | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE |
// | OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.  |
// |                                                                       |
// +-----------------------------------------------------------------------+
// | Author: Richard Heyes <richard at php net>                            |
// +-----------------------------------------------------------------------+
// $Id$
/**
* This example will decode the url given and display its
* constituent parts.
*/
    error_reporting(E_ALL | E_STRICT);

    require_once 'Net/URL2.php';

    //$url = &new Net_URL2('https://www.example.com/foo/bar/index.php?foo=bar');
    Net_URL2::setOption('encode_query_keys', true);
    $url = new Net_URL2;
    
?>
<html>
<body>

<pre>
Protocol...: <?php echo $url->protocol; ?>

Username...: <?php echo $url->user; ?>

Password...: <?php echo $url->pass; ?>

Server.....: <?php echo $url->host; ?>

Port.......: <?php $url->port; ?>

File/path..: <?php $url->path; ?>

Querystring: <?php print_r($url->querystring); ?>

Anchor.....: <?php echo $url->anchor;?>

Full URL...: <?php echo $url->getUrl(); ?>


Resolve path (/.././/foo/bar/joe/./././../jabba): <b><?php Net_URL2::resolvePath('/.././/foo/bar/joe/./././../jabba'); ?></b>
</pre>

</body>
</html>
