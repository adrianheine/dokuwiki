<?php

require_once DOKU_INC.'lib/exe/css.php';


class css_css_compress_test extends UnitTestCase {

    private $old_conf = array();

    function setup() {
        global $conf;
        $this->old_conf[] = $conf['compress'];
        $conf['compress'] = 1;
    }

    function teardown() {
        global $conf;
        $conf['compress'] = array_pop($this->old_conf);
    }

    function test_mlcom1(){
        $text = '/**
                  * A multi
                  * line *test*
                  * check
                  */';
        $this->assertEqual(css_process($text), '');
    }

    function test_mlcom2(){
        $text = '#comment/* */ {
                    color: lime;
                }';
        $this->assertEqual(css_process($text), '#comment/* */{color:lime;}');
    }

    function test_slcom1(){
        $text = '// this is a comment';
        $this->assertEqual(css_process($text), '');
    }

    function test_slcom2(){
        $text = '#foo {
                    color: lime; // another comment
                }';
        $this->assertEqual(css_process($text), '#foo{color:lime;}');
    }

    function test_slcom3(){
        $text = '#foo {
                    background-image: url(http://foo.bar/baz.jpg);
                }';
        $this->assertEqual(css_process($text), '#foo{background-image:url(http://foo.bar/baz.jpg);}');
    }

    function test_hack(){
        $text = '/* Mac IE will not see this and continue with inline-block */
                 /* \\*/
                 display: inline; 
                 /* */';
        $this->assertEqual(css_process($text), '/* \\*/display:inline;/* */');
    }

    function test_hack2(){
        $text = '/* min-height hack for Internet Explorer http://www.cssplay.co.uk/boxes/minheight.html */
                 /*\\*/
                 * html .page {
                     height: 450px;
                 }
                 /**/';
        $this->assertEqual(css_process($text), '/*\\*/* html .page{height:450px;}/**/');
    }

    function test_nl1(){
        $text = "a{left:20px;\ntop:20px}";
        $this->assertEqual(css_process($text), 'a{left:20px;top:20px;}');
    }

}

//Setup VIM: ex: et ts=4 :
