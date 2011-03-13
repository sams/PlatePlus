<?php
/**
 * plate helper tests
 */
App::import('Core', array('Helper', 'AppHelper', 'ClassRegistry', 'Controller', 'Model', 'Folder'));
App::import('Helper', array('HtmlPlus', 'Html', 'FormPlus', 'Form', 'Plate'));


if (!defined('FULL_BASE_URL')) {
	define('FULL_BASE_URL', 'http://cakephp.org');
}

if (!defined('TEST_APP')) {
	define('TEST_APP', APP . 'tests' . DS . 'test_app' . DS);
	//die(TEST_APP);
}

if (!defined('JS')) {
	define('JS', TEST_APP . 'webroot' . DS . 'js' . DS);
}

if (!defined('CSS')) {
	define('CSS', TEST_APP . 'webroot' . DS . 'css' . DS);
}

if (!defined('THEME')) {
	define('THEME', TEST_APP . 'webroot' . DS . 'theme' . DS);
}


/**
 * TheHtmlTestController class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.view.helpers
 */
class TheHtmlTestController extends Controller {

/**
 * name property
 *
 * @var string 'TheTest'
 * @access public
 */
	var $name = 'TheTest';

/**
 * uses property
 *
 * @var mixed null
 * @access public
 */
	var $uses = null;
}

Mock::generate('View', 'HtmlHelperMockView');



class PlateHelperTestCase extends CakeTestCase {

    /**
     * html property
     *
     * @var object
     * @access public
     */
    var $Html = null;
    var $Form = null;
    var $Plate = null;
    var $View = null;

/**
 * Backup of app encoding configuration setting
 *
 * @var string
 * @access protected
 */
	var $_appEncoding;

/**
 * Backup of asset configuration settings
 *
 * @var string
 * @access protected
 */
	var $_asset;

/**
 * Backup of debug configuration setting
 *
 * @var integer
 * @access protected
 */
	var $_debug;

    /**
     * setUp method
     *
     * @access public
     * @return void
     */
	function startTest() {
		$this->Html =& new HtmlPlusHelper();
		$view =& new View(new TheHtmlTestController());
		ClassRegistry::addObject('view', $view);
		$this->Plate =& new PlateHelper();
		$this->Plate->Html =& new HtmlPlusHelper();
		$this->View = $view;
		//$this->Plate->Form =& new FormPlusHelper();
		//$this->Form =& new FormPlusHelper();
		$this->_appEncoding = Configure::read('App.encoding');
		$this->_asset = Configure::read('Asset');
		$this->_debug = Configure::read('debug');
	}
    
    /**
     * endTest method
     *
     * @access public
     * @return void
     */
    function endTest() {
            Configure::write('App.encoding', $this->_appEncoding);
            ClassRegistry::flush();
            unset($this->Html);
            unset($this->Form);
            unset($this->Plate);
    }
    
    /*
     * function testJsDefault
     * @param void
     */
    
    function testJsLibDefault() {
	Configure::write('Site.JsLib', array(
		'cdn' => 'Google',
		'name' => 'jQuery',
		'version' => '1.5.1',
		'compressed' => true
	    )
	);
	
	$expected = array(
            'script' => array('src' => '//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js')
	);
	
	$result = $this->Plate->jsLib(array('fallback' => false));
	echo "<pre>".htmlspecialchars($result)."</pre>";
        $this->assertTags($result, $expected, false, 'JS Lib Test Using jquery default 2.2 from google its minified');
        
	$settings = array(
		'cdn' => 'Google',
		'name' => 'jQuery',
		'version' => '1.3.2',
		'compressed' => true,
		'fallback' => true
	    );
	
	$expected = '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>' . 
	    '!window.jQuery && document.write(unescape(\'%3Cscript src="/js/libs/jquery-1.3.2.min.js"%3E%3C/script%3E\'))</script>';
	$result = $this->Plate->jsLib($settings);
	echo "<pre>".htmlspecialchars($result)."</pre>";
        echo "<pre>".htmlspecialchars($expected)."</pre>";
        $this->assertEqual($result, $expected, 'JS Lib Test Using jquery default 1.3.2 from google its minified');
    }
    
    function testJsLibVariousAndVersions() {
	//'<script src="//ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>';
	//'<script>!window.jQuery && document.write(unescape(\'%3Cscript src="js/libs/jquery-1.3.2.min.js"%3E%3C/script%3E\'))</script>';

        Configure::write('Site.jsLib.jQuery', array(
            'cdn' => 'Google',
            'lib' => 'jquery',
            'name' => 'jQuery',
            'version' => '1.3.2',
            'compressed' => true,
            'fallback' => true,
            'html5' => false
        ));

	$title = 'JS Lib Test Using jquery default 1.3.2 from Google its minified html4';
	$expected = array(
            'script' => array('src' => '//ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js', 'type' => 'text/javascript'),
	    'script',
	    '!window.jQuery && document.write(unescape(\'%3Cscript src="/js/libs/jquery-1.3.2.min.js"%3E%3C/script%3E\'))',
	    '/script'
	);
	$result = $this->Plate->jsLib();
	echo "<pre>".htmlspecialchars($result)."</pre>";
	$this->assertTags($result, $expected, false, $title);

	$title = 'JS Lib Test Using jquery default 1.3.2 from Google its minified html5';
	$expected = array(
            'script' => array('src' => '//ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js', 'type' => 'text/javascript'),
	    'script',
	    '!window.jQuery && document.write(unescape(\'%3Cscript src="/js/libs/jquery-1.3.2.min.js"%3E%3C/script%3E\'))',
	    '/script'
	);
	$result = $this->Plate->jsLib();
	echo "<pre>".htmlspecialchars($result)."</pre>";
	$this->assertTags($result, $expected, false, $title);

	$title = 'microsoft 1.2.6 cfg';
	Configure::write('Site.jsLib.jQuery.version', '1.2.6');
	Configure::write('Site.jsLib.jQuery.cdn', 'jQuery');
	$expected = array(
	    'script' => array('src' => 'http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.2.6.min.js'),
	    'script',
	    'window.jQuery || document.write(\'<script src="/js/libs/jquery-1.2.6.min.js">\x3C/script>\')',
	    '/script'
	);
	$result = $this->Plate->jsLib();
	echo "<pre>".htmlspecialchars($result)."</pre>";
	$this->assertTags($result, $expected, false, $title);

        //'<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.4.2.min.js"></script>';
	//'<script>!window.jQuery && document.write(unescape(\'%3Cscript src="/js/libs/jquery-1.4.2.min.js"%3E%3C/script%3E\'))</script>';
	$expected = array(
            'script' => array('src' => 'http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.4.2.min.js'),
	    'script',
	    'window.jQuery || document.write(\'<script src="/js/libs/jquery-1.4.2.min.js">\x3C/script>\')',
	    '/script'
	);
        $result = $this->Plate->jsLib(array(
            'cdn' => 'Microsoft',
            'version' => '1.4.2'
        ));
	$title = 'JS Lib Test Using jquery default 1.4.2 from Microsoft its minified';
	echo "<pre>".htmlspecialchars($result)."</pre>";
	$this->assertTags($result, $expected, false, $title);

	//'<script src="http://code.jquery.com/jquery-1.3.2.min.js"></script>';
	//'<script>!window.jQuery && document.write(unescape(\'%3Cscript src="/js/libs/jquery-1.3.2.min.js"%3E%3C/script%3E\'))</script>';
	Configure::write('Site.jslib.jQuery.version', '1.3.2');
	Configure::write('Site.jslib.jQuery.cdn', 'jQuery');
	$expected = array(
            'script' => array('src' => 'http://code.jquery.com/jquery-1.3.2.min.js'),
	    'script',
	    'window.jQuery || document.write(\'<script src="/js/libs/jquery-1.3.2.min.js">\x3C/script>\')',
	    '/script'
	);
	$result = $this->Plate->jsLib(array(
	    'cdn' => 'jQuery'
	));
	echo "<pre>".htmlspecialchars($result)."</pre>";
	$this->assertTags($result, $expected, false, 'JS Lib Test Using jquery default 1.3.2 from google its minified');

        Configure::write('Site.jsLib.SWFObject', array(
            'cdn' => 'Google',
            'lib' => 'swfobject',
            'name' => 'SWFObject',
            'version' => '2.2',
            'compressed' => true,
            'fallback' => true
        ));
        //'<script src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.min.js"></script>';
        //'<script>!window.swfobject && document.write(unescape(\'%3Cscript src="/js/libs/swfobject-2.2.min.js"%3E%3C/script%3E\'))</script>';
	$expected = array(
            'script' => array('src' => '//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.min.js'),
	    'script',
	    'window.swfobject && document.write(\'<script src="/js/libs/swfobject-2.2.min.js">\x3C/script>\')',
	    '/script'
	);
        $result = $this->Plate->jsLib('SWFObject');
	echo "<pre>".htmlspecialchars($result)."</pre>";
        $this->assertTags($result, $expected, false, 'JS Lib Test Using SwfOject 2.2 from google minified html5');
        
//        //Configure::write('Site.jslib.SWFObject', '2.1');
//        //'<script src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.min.js"></script>';
//          $expected = array(); //'<script>!window.swfobject && document.write(unescape(\'%3Cscript src="/js/libs/swfobject-2.2.min.js"%3E%3C/script%3E\'))</script>';
//        $result = $this->Plate->jsLib(array(
//            'name' => 'SWFObject'
//        ));
//        $this->assertTags($result, $expected, false, 'JS Lib Test Using SwfOject 2.2 from google minified');
//        
//        Configure::write('Site.jslib.SWFObject', '2.1');
//          $expected = array(); //'<script src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.min.js"></script>';
//        //$expected.= '<script>!window.swfobject && document.write(unescape(\'%3Cscript src="/js/libs/swfobject-2.2.min.js"%3E%3C/script%3E\'))</script>';
//        $result = $this->Plate->jsLib(array(
//            'name' => 'SWFObject',
//            'compressed' => false,
//            'fallback' => false
//        ));
//        $this->assertTags($result, $expected, false, 'JS Lib Test Using SwfOject 2.2 from google minified');
//        
//        Configure::write('Site.jslib.Prototype', '1.5');
//        //'<script src="//ajax.googleapis.com/ajax/libs/prototype/1.5/prototype.min.js"></script>';
//          $expected = array(); // '<script>!window.Prototype && document.write(unescape(\'%3Cscript src="/js/libs/prototype-1.5.min.js"%3E%3C/script%3E\'))</script>';
//        $result = $this->Plate->jsLib(array(
//            'name' => 'Protype'
//        ));
//        $this->assertTags($result, $expected, false, 'JS Lib Test Using Prototype from google non-minified');
    }
//    
//    function testPngFix() {
//  $expected = array(); //
////<!--[if lt IE 7 ]>
////  <script src="libs/dd_belatedpng"></script>
////  <script>DD_belatedPNG.fix('.myPngClass'); </script>
////<![endif]-->
//        $result = $this->Plate->pngFix('.myPngClass');
//        //echo htmlspecialchars($result);
//        $this->assertTags($result, $expected, false, 'Html5 Test Drew Diller PNG Fix - the belated but ultimate - string');
//        $result = $this->Plate->pngFix(array('.myPngClass', 'img', '#header'));
//        $expected = $expected = array(); //
////<!--[if lt IE 7 ]>
////  <script src="libs/dd_belatedpng"></script>
////  <script>DD_belatedPNG.fix('.myPngClass, img, #header'); </script>
////<![endif]-->
//        $this->assertTags($result, $expected, false, 'Html5 Test Drew Diller PNG Fix - the belated but ultimate - array');
//    }
    
	function testProfiling() {
	    Configure::write('Site.yahooProfiler', '2.8.2r1');
      
	$expected = array(
            'script' => array('src' => 'js/profiling/yahoo-profiling.min.js'),
            'script' => array('src' => 'js/profiling/config.js'),
	);
    
	    $result = $this->Plate->profiling();
	    echo "<pre>".htmlspecialchars($result)."</pre>";
	    $this->assertTags($result, $expected, false, 'Html5 Test Yahoo Profiling');
	}

    //function testConditionalComment() {
    //    $expected = array(); //'<!--[if IE ]><style>body { background: blue; }</style><![endif]-->';
    //    $style = $this->Html->style('body { background: blue; }', true);
    //    $result = $this->Plate->conditionalComment($style);
    //    $this->assertTags($result, $expected, false, 'IE Conditional Comments Default with style as content');
    //    
    //    $expected = array(); //'<!--[if lt IE7 ]><style>body { background: red; }</style><![endif]-->';
    //    //$expected.= '<!--[if IE 7 ]><style>body { background: green; }</style><![endif]-->';
    //    //$expected.= '<!--[if IE 8 ]><style>body { background: blue; }</style><![endif]-->';
    //    $styles[] = $this->Html->tag('style', '', 'body ' . $this->Html->style(array('background' => 'red'), true));
    //    $styles[] = $this->Html->tag('style', '', 'body ' . $this->Html->style(array('background' => 'green'), true));
    //    $styles[] = $this->Html->tag('style', '', 'body ' . $this->Html->style(array('background' => 'blue'), true));
    //    $ies = array(-7, 7, 8);
    //    $result = $this->Plate->conditionalComment($styles, $ies);
    //    $this->assertTags($result, $expected, false, 'IE Conditional Comments Default with style as content');
    //}

    function testChromeFrame() {
	$title = 'Html5 Chrome Frame Meta Tag';
	$expected = array('meta' => array('http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge,chrome=1'));
	$result = $this->Plate->chromeFrame();
	echo "<pre>".htmlspecialchars($result)."</pre>";
	$this->assertTags($result, $expected, false, $title);
    }

/*

<!-- Starting to render - extras/google_analytics -->
  <script>
   var _gaq = [['_setAccount', 'UA-9870-123'], ['_trackPageview']];
   (function(d, t) {
    var g = d.createElement(t),
        s = d.getElementsByTagName(t)[0];
    g.async = true;
    g.src = ('https:' == location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g, s);
   })(document, 'script');
  </script>
<!-- Finished - extras/google_analytics -->

*/

    function testAnalytics() {
        Configure::write('Site.GoogleAnalytics', 'UA-9870-123');

$GA1 = <<<GA1
   var _gaq = [['_setAccount', 'UA-9870-123'], ['_trackPageview']];
   (function(d, t) {
    var g = d.createElement(t),
        s = d.getElementsByTagName(t)[0];
    g.async = true;
    g.src = ('https:' == location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g, s);
   })(document, 'script');
GA1;

$GA2 = <<<GA2
   var _gaq = [['_setAccount', 'UA-9870-123'], ['_trackPageview']];
   (function(d, t) {
    var g = d.createElement(t),
        s = d.getElementsByTagName(t)[0];
    g.async = true;
    g.src = 'http://www.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g, s);
   })(document, 'script');
GA2;

	//App::import('Core', 'view');
	$expected = array(
	    'script',
	    $GA1,
	    '/script'
	);
        $result = $this->Plate->analytics();
        $this->assertTags($result, $expected, false, 'Html5 Google Analytics output test');

	$expected = array(
	    'script',
	    $GA2,
	    '/script'
	);
        $result = $this->Plate->analytics('extras/google_analytics2.ctp');
        $this->assertTags($result, $expected, false, 'Html5 Google Analytics output test with different element');
    }

    function testModernizr() {
        Configure::write('Site.ModernizrBuild', '1.7');
        
	$expected = array(
            'script' => array('src' => 'js/libs/modernizr-1.7.min.js')
	); //'<script src="js/libs/modernizr-1.7.min.js"></script>';
        $result = $this->Plate->modernizr();
	$this->Plate->log('### testModernizr no args ###################################', 'plate-test');
	$this->Plate->log('expected', 'plate-test');
	$this->Plate->log($expected, 'plate-test');
	$this->Plate->log('result', 'plate-test');
	$this->Plate->log($result, 'plate-test');
	$this->Plate->log('################################################', 'plate-test');
	echo "<pre>".htmlspecialchars($result)."</pre>";
	$this->assertTags($result, $expected, false,  'Html5 Modernizr using default build');
	
	$expected = array(
            'script' => array('src' => 'js/libs/modernizr2-yepnope.min.js')
	); //'<script src="js/libs/modernizr2-yepnope.js"></script>';
        $result = $this->Plate->modernizr(array('build' => 'modernizr2-yepnope'));
	$this->Plate->log('### testModernizr arg build  ###################################', 'plate-test');
	$this->Plate->log('expected', 'plate-test');
	$this->Plate->log($expected, 'plate-test');
	$this->Plate->log('result', 'plate-test');
	$this->Plate->log($result, 'plate-test');
	$this->Plate->log('################################################', 'plate-test');
	echo "<pre>".htmlspecialchars($result)."</pre>";
	$this->assertTags($result, $expected, false, 'Html5 Use Custom Build of Modernizr 2 min');
        
        Configure::write('Site.ModernizrBuild', 'modernizr2-yepnope');
	$expected = array(
            'script' => array('src' => 'js/libs/modernizr2-yepnope.js')
	); //'<script src="js/libs/modernizr2-yepnope.js"></script>';
        $result = $this->Plate->modernizr(array('min' => false));
	$this->Plate->log('### testModernizr arg min cfg build ###################################', 'plate-test');
	$this->Plate->log('expected', 'plate-test');
	$this->Plate->log($expected, 'plate-test');
	$this->Plate->log('result', 'plate-test');
	$this->Plate->log($result, 'plate-test');
	$this->Plate->log('################################################', 'plate-test');
	echo "<pre>".htmlspecialchars($result)."</pre>";
        $this->assertTags($result, $expected, false, 'Html5 Use Custom Build of Modernizr 2 equal');
    }
    
    /**
     * function testsiteVerification
     * @param void
     */
    
    function testSiteVerification() {
	$allResult = ''; $allExpected = array();
	// google google-site-verification
	$expected = array('meta' => array('name' => 'google-site-verification', 'content' => 'CcgaR8gH9Tf-rdWn6TLdAsU5H3fjYfC4hmmWWIzY7Ts'));
	$result = $this->Plate->siteVerification('google-site-verification', 'CcgaR8gH9Tf-rdWn6TLdAsU5H3fjYfC4hmmWWIzY7Ts');
	echo "<pre>".htmlspecialchars($result)."</pre>";
	$this->assertTags($result, $expected, false, 'google verification meta');
	$allResult = $result;

	// alexa
	$expected = array('meta' => array('name' => 'alexaVerifyID', 'content' => 'L21dX4SsiDjF70CfWyMbsATY4r4')); 
	$result = $this->Plate->siteVerification('alexaVerifyID', 'L21dX4SsiDjF70CfWyMbsATY4r4');
	echo "<pre>".htmlspecialchars($result)."</pre>";
	$this->assertTags($result, $expected, false, 'alexa verification meta');
	$allResult.= $result;

	// yahoo 
	$expected = array('meta' => array('name' => 'y_key', 'content' => 'L21dX4SsiDjF70CfWyMbsATY4r4')); 
	#$result = $this->Plate->siteVerification('google-site-verification', 'a45c6433c59ebe9y');
	$result = $this->Plate->siteVerification('y_key', 'L21dX4SsiDjF70CfWyMbsATY4r4');
	echo "<pre>".htmlspecialchars($result)."</pre>";
	$this->assertTags($result, $expected, false, 'alexa verification meta');
	$allResult.= $result;

	// bing
	$expected = array('meta' => array('name' => 'msvalidate.01', 'content' => 'F837D65F34C87C02ET781349926D615E')); 
	$result = $this->Plate->siteVerification('msvalidate.01', 'F837D65F34C87C02ET781349926D615E');
	echo "<pre>".htmlspecialchars($result)."</pre>";
	$this->assertTags($result, $expected, false, 'alexa verification meta');
	$allResult.= $result;

	// null test incorrect mismatch of args
	$result = $this->Plate->siteVerification(array('google-site-verification', 'alexaVerifyID', 'y_key', 'msvalidate.01'), array('CcgaR8gH9Tf-rdWn6TLdAsU5H3fjYfC4hmmWWIzY7Ts'));
	$this->assertNull($result,'mismatch number of args');
	
	// all
	$allExpected = '<meta name="google-site-verification" content="CcgaR8gH9Tf-rdWn6TLdAsU5H3fjYfC4hmmWWIzY7Ts">';
	$allExpected.= '<meta name="alexaVerifyID" content="L21dX4SsiDjF70CfWyMbsATY4r4">';
	$allExpected.= '<meta name="y_key" content="L21dX4SsiDjF70CfWyMbsATY4r4">';
	$allExpected.= '<meta name="msvalidate.01" content="F837D65F34C87C02ET781349926D615E">';
	$result = str_replace(array("\n", "\r"), '', $result);
	$result = $this->Plate->siteVerification(array('google-site-verification', 'alexaVerifyID', 'y_key', 'msvalidate.01'), array('CcgaR8gH9Tf-rdWn6TLdAsU5H3fjYfC4hmmWWIzY7Ts', 'L21dX4SsiDjF70CfWyMbsATY4r4', 'L21dX4SsiDjF70CfWyMbsATY4r4', 'F837D65F34C87C02ET781349926D615E'));
	$this->assertEqual($allResult, $allExpected, 'all seo meta verifications');
	echo "<pre>".htmlspecialchars($allResult)."</pre>";
    }
    
    /**
     * function testCustomCDN
     * @param void
     */
    
    function testCustomCDN() {
    }
    
    /**
     * function testSiteIcons
     * @param void
     */
    
    function testSiteIcon() {
	/*
	    <link rel="shortcut icon" href="http://static.samsherlock.ss33/favicon.ico">
	    <link rel="apple-touch-icon" href="http://static.samsherlock.ss33/apple-touch-icon.png">
	*/
	// just favicon
	
	// just apple-icon
	
	// just fb icon; not really an icon
	
	// all - not fb icon
    }
    
    /**
     * function testSiteIcons
     * @param void
     */
    
    function testCaptureElement() {
	$aside_for_layout = '';
	$AS1 = <<<AS1
	<aside>
		<h3>This is an aside</h3>
		<p>An aside is tangental to the subject of the section or article</p>
		<p>Though it is still relevent.</p>
	</aside>
AS1;

	$this->Plate->capture();
	?>
	<aside>
		<h3>This is an aside</h3>
		<p>An aside is tangental to the subject of the section or article</p>
		<p>Though it is still relevent.</p>
	</aside><?php
	$this->Plate->content_for('aside_for_layout');
	$result = $this->View->getVar('aside_for_layout');
	$expected = $AS1;
	$result = str_replace(array("\n", "\r", "\t"), '', $result);
	$expected = str_replace(array("\n", "\r", "\t"), '', $expected);
	$this->assertEqual($result, $expected, 'Capture Output');
	echo "<pre>".htmlspecialchars($result)."</pre>";
	echo "<hr><pre>".htmlspecialchars($expected)."</pre>";
    }
}


?>