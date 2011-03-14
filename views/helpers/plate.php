<?php
/**
 * Plate Helper class for easy use of HTML widgets.
 *
 * PlateHelper misc helper methods for use with apps baked with BakingPlate
 *
 * @package       cake
 * @subpackage    cake.cake.libs.view.helpers
 * @link http://book.cakephp.org/view/1434/HTML
 */


class PlateHelper extends AppHelper {
	var $helpers = array('Html', 'Form');
	var $_currentView;
	var $modernizrBuild = 'modernizr-1.7';
	
	/**
	 * defaultJsLib
	 * @var array named content deployment networks
	 */
	private $_libs = array(
		'jquery' => 'jQuery',
		'swfobject' => 'SWFObject',
		'prototype' => 'Prototype',
		'dojo' => 'dojo',
		'mootools' => 'MooTools'
		);
	
	/**
	 * defaultJsLib
	 * @var array named content deployment networks
	 */
	private $_defaultJsLib = array(
		'cdn' => 'Google',
		'lib' => 'jquery',
		'name' => 'jQuery',
		'version' => '1.5.1',
		'compressed' => true,
		'fallback' => true,
		'min' => '.min',
		'html5' => false
	    );
	
	/**
	 * jsLibFallbac
	 * @var array named content deployment networks
	 */
	private $_jsLibFallback = array(
		'window.:name || document.write(\'<script src="/js/libs/:lib-:version:min.js">\x3C/script>\')',
		'!window.:name && document.write(unescape(\'%3Cscript src="/js/libs/:lib-:version:min.js"%3E%3C/script%3E\'))'
		);
	
	/**
	 * cdns
	 * @var array named content deployment networks
	 */
	private $_cdns = array(
		'Google' => '//ajax.googleapis.com/ajax/libs/:lib/:version/:lib:min.js',
		'Microsoft' => 'http://ajax.aspnetcdn.com/ajax/:name/:lib-:version:min.js',
		'jQuery' => 'http://code.jquery.com/jquery-:version:min.js'
	);
	
	/**
	 * contruct
	 * 	- allow defaults to be overridden
	 * @param $settings array
	 */
	
	public function __construct ($settings = array()) {
		// current view is used by analytics and capture ouput
		$this->_currentView = &ClassRegistry::getObject('view');

		// todo: set up configure defaults
	}
	
	
	/**
	 * jsLibFallback
	 * @param $options array host = google, lib = jquery, version = null, compressed = true
	 */
	public function jsLibFallback($options) {
		$html5 = ($options['html5'] || $this->Html->getType('short') == 'html5') ? 1 : 0;
		if($options['name'] == 'SWFObject') $options['name'] = $options['lib'];
		$fallback = $this->_jsLibFallback[$html5];
		foreach($options as $key => $value) {
			$fallback = str_replace(':'.$key, $value, $fallback);
		} 
	    return $fallback;
	}
	
	
	/**
	 * cdnlib
	 * @param options array of options eg host = google, lib = jquery, version = null, compressed = true
	 */
	function cdnLib($options) {
		$cdn = $this->_cdns[$options['cdn']];
		foreach($options as $key => $value) {
			$cdn = str_replace(':'.$key, $value, $cdn);
		} 
	    return $cdn;
	}
	
	
	/**
	 * jsLib
	 * @example jsLib()
	 * @example jsLib(array('cdn' => <cdName>, 'lib' => <libName>, 'version' => <versionRelease>))
	 * @param array of javascrip library options such as cdn, libname, version, minification
	 */
	public function jsLib($options = array()) {
		$options = is_string($options) ? Configure::read('Site.jsLib.' . $options) : $options;
		$options = is_array(($options)) ? array_merge($this->_defaultJsLib, $options) : $this->_defaultJsLib;
		
		$options['html5'] = ($this->Html->getType('short') == 'html5') ? true : false;
		
		if(!isset($options['name'])) {
			$options['name'] = $this->libs[$options['lib']];
		}
		
		if(is_null($options['version'])) {
			$options['version'] = Configure::read('Site.jsLib.' . $options['name'] . '.version');
		}

		$options['min'] = (!isset($options['compressed']) ||$options['compressed'] === true) ? '.min' : '';
		
		$cdn = $this->Html->script($this->cdnLib($options));
		$this->log($cdn, 'plate');

		$fallback = '';
		//$fallback = is_null($version) ? '' : $this->Html->scriptBlock("!window.jQuery && document.write(unescape('%3Cscript src=\"libs/{$lib}-{$version}{$min}\"%3E%3C/script%3E'))");
		$fallback = $options['fallback'] === true ? $this->Html->scriptBlock($this->jsLibFallback($options)) : '';
		//$this->log($fallback, 'plate');

	    return $cdn.$fallback;
	}

	/**
	 * dd_png
	 * @param $fixClasses array of elements/classnames to fix
	 */
	public function pngFix($fixClasses = array('img', '.png')) {
		$classes = (is_array($fixClasses)) ? implode(', ', $fixClasses) : $fixClasses;
		$pngFix = $this->Html->script(array('libs/dd_belatedpng')) .
			    $this->Html->scriptBlock("DD_belatedPNG.fix('$classes'); ", array('safe' => false));
	    return $this->conditionalComment($pngFix, -7);
	}

	/**
	 * profiling
	 * outputs yahoo profiling code - only if admin is logged in and debug is set
	 * @param void
	 */   
	public function profiling() {
		if(Configure::read('Site.yahooProfiler'))	{
		    return $this->Html->script(array('profiling/yahoo-profiling.min', 'profiling/config'));
		}
	}

	/**
	 * conditionalComment
	 * outputs an ie conditional comment containing content
	 * @example conditionalComment('all ies')
	 * @example conditionalComment('just ie7 and below', -7)
	 * @param $content string of content
	 * @param $ie mixed true for all ie false for non ie, or string ie condition
	 */
	public function conditionalComment($content, $ie = true) {
		$iee = 'IE';
		$template = '<!--[if %2$s ]>%1$s<![endif]-->';
		if($ie !== false) {
			if($ie < 0) {
				// lessthan equal to the reverse of the negtive number
				$iee = abs($ie);
				$template = '<!--[if lt IE %2$d ]>%1$s<![endif]-->';
			} elseif(is_numeric($ie)) {
				// straight number
				$iee = 'IE';
				$template = '<!--[if IE %2$s ]>%1$s<![endif]-->';
			}
		} else {
		    // not ie a 
		}

		if(is_array($content)) {
			$output = '';
			foreach($content as $iec) {
				$iec = str_replace("\\r\\n", "\\r\\n\\t",  $iec);
				$output.= "\n$iec\n";
			}
		    return sprintf($template, $output, $iee);
		}
		
		if(strpos($content, "\\r\\n"))   {
			$content = str_replace("\\r\\n", "\\r\\n\\t",  $content);
			$content = "\n$content\n";
		}
	    return sprintf($template, $content, $iee);
	}
	
	/**
	 * chromeFrame
	 * outputs a chromFrame meta innvocation
	 * @param void
	 */
	public function chromeFrame() {
	    return $this->Html->meta(array('http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge,chrome=1'));
	}
	
	/**
	 * css
	 * output the basic boilerplate stylesheets implied all media and by default a basic handheld might wrap in asset plugin support
	 * @todo
	 * 	- handle cdn
	 * 	- option for handheld.css
	 * 	- support various asset plugins - default to html->css
	 * @param $style mixed string or array of css basenames witgout suffixes for implied all css
	 * @param $handheld mixed string or array of css basenames witgout suffixes for implied all css, false to omit it
	 */
	
	public function css($style = 'style', $options = array()) {
		$style = '<link rel="stylesheet" href="/css/style.css">';
		$handheld = '<link rel="stylesheet" media="handheld" href="/css/handheld.css">';
	    return $style.$handheld;
	}
	
	/**
	 * analytics
	 * outputs google analytics code - only if on live domain and the GA id is set
	 * @todo
	 * 	- move elemets to plugin
	 * 	- make the app elements override the plugins
	 * @param $element string to override the default (elements should be moved into plugin)
	 */
	public function analytics($element = '') {
		$GoogleAnalytics = Configure::read('Site.GoogleAnalytics');
		if(!$GoogleAnalytics)	{
		    return;
		}
		$element = !empty($element) ? $element : 'extras/google_analytics';
	    return $this->_currentView->element($element, array('google_analytics' => $GoogleAnalytics));
	}
	
	/**
	 * siteVerification
	 * output single or set of seo verification metas
	 * @param $name mixed string meta name or array of meta names
	 * @param $name mixed string meta content or array of meta content
	 */
	public function siteVerification($name, $content) {
		if(is_array($name) && is_array($content)) {
			$metas = count($name);
			if(!($metas == count($content))) return null;
			$meta = '';
			for($i = 0; $i < $metas; $i++) {
				$meta.= $this->Html->meta('meta', null, array('name' => $name[$i], 'content' => $content[$i]));
			}
		    return $meta;
		} else {
		    return $this->Html->tag('meta', null, compact('name', 'content'));
		}
	}

	/**
	 * siteIcons
	 * @todo
	 * 	- will output other icons to if they exist
	 * 	- use phpThumb to generate them (not here comp?)
	 * @param $icon string uri of icon to be used as favicon
	 * @author Sam Sherlock
	 */
	public function siteIcons($icon) {
	}

	/**
	 * modernizr
	 * @todo
	 * 	- resolve path issues
	 * @param $options mixed array of options to override cfg build settings
	 * @author Sam Sherlock
	 */
	public function modernizr($options = false) {
		$defaults = array(
			'min' => '.min',
			'build' => $this->modernizrBuild
		);
		$options = is_array($options) ? array_merge($defaults, $options) : $defaults;
		$min = $options['min'] === false ? '' : '.min';
	    return $this->Html->script('libs/' . $options['build'] . $min);
	}

	/**
	 * Begin capturing a block of HTML content
	 *
	 * @author Chris Your
	 */
	public function capture(){
		ob_start();
	}

	/**
	 * Set the captured block of HTML content to a $variable
	 *
	 * @param string $variable Assigned name
	 * @return void
	 * @author Chris Your
	 */
	public function content_for($variable){
		$this->_currentView->set($variable, ob_get_clean());
	}
    
}
