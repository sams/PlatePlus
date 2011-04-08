<?php
/**
 * Html5 Helper
 *
 *
 * @copyright     Copyright 2011, Sam Sherlock
 * @link          http://samsherlock.com
 * @package       PlatePlus
 * @subpackage    plate_plus.views.helpers
 * @since         PlatePlus 0.0.2
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
App::import('helper', 'BakingPlate.HtmlPlus');

class Html5Helper extends HtmlPlusHelper {
	

	/**
	 * html tags used by this helper.
	 *
	 * @var array
	 * @access public
	 */
	var $tags = array(
	    'html' => '<html%s>',
	    'htmlend' => '</html>',
	    'meta' => '<meta%s>',
	    'metalink' => '<link href="%s"%s>',
	    'link' => '<a href="%s"%s>%s</a>',
	    'mailto' => '<a href="mailto:%s" %s>%s</a>',
	    'form' => '<form %s>',
	    'formend' => '</form>',
	    'input' => '<input name="%s" %s>',
	    'textarea' => '<textarea name="%s" %s>%s</textarea>',
	    'hidden' => '<input type="hidden" name="%s" %s>',
	    'checkbox' => '<input type="checkbox" name="%s" %s>',
	    'checkboxmultiple' => '<input type="checkbox" name="%s[]"%s >',
	    'radio' => '<input type="radio" name="%s" id="%s" %s >%s',
	    'selectstart' => '<select name="%s"%s>',
	    'selectmultiplestart' => '<select name="%s[]"%s>',
	    'selectempty' => '<option value=""%s>&nbsp;</option>',
	    'selectoption' => '<option value="%s"%s>%s</option>',
	    'selectend' => '</select>',
	    'optiongroup' => '<optgroup label="%s"%s>',
	    'optiongroupend' => '</optgroup>',
	    'checkboxmultiplestart' => '',
	    'checkboxmultipleend' => '',
	    'password' => '<input type="password" name="%s" %s>',
	    'file' => '<input type="file" name="%s" %s>',
	    'file_no_model' => '<input type="file" name="%s" %s>',
	    'submit' => '<input %s>',
	    'submitimage' => '<input type="image" src="%s" %s>',
	    'button' => '<button type="%s"%s>%s</button>',
	    'image' => '<img src="%s" %s>',
	    'tableheader' => '<th%s>%s</th>',
	    'tableheaderrow' => '<tr%s>%s</tr>',
	    'tablecell' => '<td%s>%s</td>',
	    'tablerow' => '<tr%s>%s</tr>',
	    'block' => '<div%s>%s</div>',
	    'blockstart' => '<div%s>',
	    'blockend' => '</div>',
	    'tag' => '<%s%s>%s</%s>',
	    'tagstart' => '<%s%s>',
	    'tagend' => '</%s>',
	    'para' => '<p%s>%s</p>',
	    'parastart' => '<p%s>',
	    'label' => '<label for="%s"%s>%s</label>',
	    'fieldset' => '<fieldset%s>%s</fieldset>',
	    'fieldsetstart' => '<fieldset><legend>%s</legend>',
	    'fieldsetend' => '</fieldset>',
	    'legend' => '<legend>%s</legend>',
	    'css' => '<link rel="%s" href="%s"%s>',
	    'style' => '<style %s>%s</style>',
	    'charset' => '<meta charset="%s">',
	    'ul' => '<ul%s>%s</ul>',
	    'ol' => '<ol%s>%s</ol>',
	    'li' => '<li%s>%s</li>',
	    'error' => '<div%s>%s</div>',
	    'javascriptblock' => '<script%s>%s</script>',
	    'javascriptstart' => '<script%s>',
	    'javascriptlink' => '<script src="%s"%s></script>',
	    'javascriptend' => '</script>',
		// @depeciated
	    'header' => '<header%s>%s</header>',
	    'footer' => '<footer%s>%s</footer>',
	    'article' => '<article%s>%s</article>',
	    'articlestart' => '<article%s>',
	    'articleend' => '</article>',
	    'section' => '<section%s>%s</section>',
	    'nav' => '<nav%s>%s</nav>',
	    'time' => '<time%s>%s</time>',
	    'output' => '<ouput%s>%s</ouput>',
	    'details' => '<details%s>%s</details>',
	    'menu' => '<menu%s>%s</menu>',
	    'command' => '<command%s>%s</command>',
	    'canvas' => '<canvas%s>%s</canvas>'
	);

	/**
	 * microformats https://developer.mozilla.org/en/Using_microformats
	 *
	 * @todo readup and decide
	 */

	/**
	 * html5 tags with html4.5 class fallbacks
	 * @todo output, audio, flash
	*/

	/** 
	 * function article
	 * @param $content string of content
	 * @param $content array  of options
	 */
	function article($content, $options = array()) {
		$classes = '';
		if(!empty($options['class']))    {
			$classes = ' ';
			$classes.= ($options['class'] !== array()) ? implode(' ', $options['class']) : $options['class'];
		}
		if($classes !== '') $options['class'] = trim($classes);

		return $this->tag('article', $content, $options);
	}

	/*
	 * function section
	 * @todo
	 * 	- handle aside and subsections
	 * 	- have option to sectionize see wp sectionize
	 * @param $content string of content for section
	 * @param $header array to be passed to header function
	 * @param $footer array to be passed to footer function
	 */
	function section($content, $header, $footer = array(), $options = array()) {
		# https://developer.mozilla.org/en/Sections_and_Outlines_of_an_HTML5_document
		$classes = '';
		if(!empty($options['class']))    {
			$classes = ' ';
			$classes.= ($options['class'] !== array()) ? implode(' ', $options['class']) : $options['class'];
		}
		
		if($classes !== '') $options['class'] = trim($classes);
		
		return $this->tag('section', $content, $options);
	}

	/*
	 * function nav
	 * @param $content string of nav content
	 * @param $options array of items such as class
	 */
	function nav($content, $options = array()) {
		$classes = '';

		if(!empty($options['class']))    {
			$classes = ' ';
			$classes.= ($options['class'] !== array()) ? implode(' ', $options['class']) : $options['class'];
		}

		if($classes !== '') $options['class'] = trim($classes);
		
		return $this->tag('nav', $content, $options);
	}

	/**
	 * function menu
	 * @todo
	 * @param 
	 */
	function menu() {
	}

	/**
	 * function command
	 * @todo
	 * 	- noby yet implements this I think
	 * @param 
	 */
	function command() {
	}

	/**
	 * function video
	 * @todo
	 * 	- see ideas mentioned in audio
	 * @param $sources array of video sources
	 * @param $options array of attribs etc
	 */
	function video($sources, $options = array()) {
	}

	/**
	 * function audio
	 * @todo
	 * 	- can pass built set of source
	 * 	- if built sources not passed looks for approp files based on name
	 * 	  and makes sources
	 * 	- make fallback option that uses flash swfobject if cfg'd
	 * @param $sources array of video sources
	 * @param $options array of attribs etc
	 */
	function audio($sources, $options = array()) {
	}

	/**
	 * function source
	 * @todo
	 *	- by default will not check the presence of file
	 * @param 
	 */
	function source($source, $options = array()) {
	}
	
	/**
	 * function output
	 * @todo
	 * @param 
	 */
	
	function output() {
	}

	/**
	 * function mark
	 * https://developer.mozilla.org/en/HTML/Element/mark
	 *
	 * @todo
	 * @param 
	 */
	function mark() {
	}

	/**
	 * function figure
	 * https://developer.mozilla.org/en/HTML/Element/figure
	 *
	 * @todo
	 *  - write tests
	 * @param 
	 */
	function figure($figure, $options = array(), $figcaption = '') {
		return $this->tag('figure', $figure, $options);
	}

	/**
	 * function figcaption
	 * https://developer.mozilla.org/en/HTML/Element/figcaption
	 *
	 * @todo
	 * @param 
	 */
	function figcaption($text, $options = array()) {
		return $this->tag('figcaption', $text, $options);
	}

	/**
	 * function canvas
	 * @todo
	 * @param $options array of options
	 * @param $fallback string of content
	 */
	function canvas($options, $fallback = '') {
		$fallback = (empty($fallback)) ? $this->para('message', __('content not available', true)) : $fallback;
		return $this->tag('canvas', $fallback, $options);
	}

	/**
	 * function time
	 *@todo:
	 * outputs time tag
	 *  <time class="..." pubdate>2011-11-11 11:11:00</time>
	 *  <span class="time ..." title="published">2011-11-11 11:11:00</span>
	 * 
	 * @param $content string time used to create attribs
	 * @param $options array of options
	 */
	function time($content, $options = array()) {
	}
}