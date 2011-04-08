<?php
class PageRoute extends CakeRoute {
	function parse($url) {
		$params = parent::parse($url);
		if (empty($params)) {
			return false;
		}
		$slugs = Cache::read('page_routes', 'routes');
		if (empty($slugs)) {
			App::import('Model', 'Page');
			$Page = new Page();
			$pages = $Page->find('all', array(
				'fields' => array('Page.slug'),
				'conditions' => array('Page.published' => 1),
				'recursive' => -1
			));
			$slugs = array_flip(Set::extract('/Page/slug', $pages));
			
			//Cache::write('page_slugs', $slugs, 'routes');
			
			Cache::write('page_routes', $slugs, 'slug');
		}
		if (isset($slugs[$params['slug']])) {
			return $params;
		}
		return false;
	}
}