<?php
class PagesController extends AppController {
/**
 * Controller name
 *
 * @var string
 * @access public
 */
	public $name = 'Pages';

/**
 * Helpers
 *
 * @var array
 * @access public
 */
	public $helpers = array('Html', 'Form');

/**
 * Components
 *
 * @var array
 * @access public
 */
	public $components = array('Search.Prg');
/**
 * Fields to preset in search forms.
 *
 * @var array $presetVars
 * @see Search.PrgComponent
 * @access public
 */
	public $presetVars = array(
		//array('field' => 'title', 'type' => 'value'),
	);

	function beforeFilter() {
			parent::beforeFilter();
	}

/**
 * Find for page.
 * 
 * @access public
 */
	public function find() {
		$this->Prg->commonProcess();
		$this->paginate = array('search', 'conditions' => $this->Page->parseCriteria($this->passedArgs));
		$this->set('pages', $this->paginate()); 
	}

/**
 * Display a static (non db) page.
 * 
 * @access public
 */
	function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}

/**
 * View for page.
 *
 * @param string $slug, page slug 
 * @access public
 */
	public function view($slug = null) {
	  $this->Page->recursive = 1;
		try {
			$page = (strpos($this->params['url']['url'], '/') === false && isset($slug)) ? $this->Page->view($slug) : $this->Page->view($this->params['url']['url'], 'url');
		} catch (OutOfBoundsException $e) {
			
			$this->cakeError('error404', array(
				'code' => 404,
				'base' => $this->base,
				'url' => $this->here,
				'message' => $this->here . $e->getMessage(),
				'name' => __('404 File Not Found', true)
			));
		}
		$this->set(compact('page')); 
	}

/**
 * Add for page.
 * 
 * @access public
 */
	public function add() {
		try {
			$result = $this->Page->add($this->Auth->user('id'), $this->data);
			if ($result === true) {
				$this->Session->setFlash(__('The page has been saved', true));
				$this->redirect(array('action' => 'index'));
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}
		$parentPages = $this->Page->ParentPage->find('list');
		$users = $this->Page->User->find('list');
		$this->set(compact('parentPages', 'users'));
 
	}

/**
 * Edit for page.
 *
 * @param string $id, page id 
 * @access public
 */
	public function edit($id = null) {
		try {
			$result = $this->Page->edit($id, $this->Auth->user('id'), $this->data);
			if ($result === true) {
				$this->Session->setFlash(__('Page saved', true));
				$this->redirect(array('action' => 'view', $this->Page->data['Page']['slug']));
				
			} else {
				$this->data = $result;
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}
		$parentPages = $this->Page->ParentPage->find('list');
		$users = $this->Page->User->find('list');
		$this->set(compact('parentPages', 'users'));
 
	}

/**
 * Delete for page.
 *
 * @param string $id, page id 
 * @access public
 */
	public function delete($id = null) {
		try {
			$result = $this->Page->validateAndDelete($id, $this->Auth->user('id'), $this->data);
			if ($result === true) {
				$this->Session->setFlash(__('Page deleted', true));
				$this->redirect(array('action' => 'index'));
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->Page->data['page'])) {
			$this->set('page', $this->Page->data['page']);
		}
	}

/**
 * Admin find for page.
 * 
 * @access public
 */
	public function admin_find() {
		$this->Prg->commonProcess();
		$this->paginate = array('search', 'conditions' => $this->Page->parseCriteria($this->passedArgs));
		$this->set('pages', $this->paginate()); 
	}

/**
 * Admin index for page.
 * 
 * @access public
 */
	public function admin_index() {
		$this->Page->recursive = 0;
		$this->set('pages', $this->paginate()); 
	}

/**
 * Admin view for page.
 *
 * @param string $slug, page slug 
 * @access public
 */
	public function admin_view($slug = null) {
		try {
			$page = $this->Page->view($slug);
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}
		$this->set(compact('page')); 
	}

/**
 * Admin add for page.
 * 
 * @access public
 */
	public function admin_add() {
		try {
			$result = $this->Page->add($this->Auth->user('id'), $this->data);
			if ($result === true) {
				$this->Session->setFlash(__('The page has been saved', true));
				$this->redirect(array('action' => 'index'));
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}
		$parentPages = $this->Page->ParentPage->find('list');
		$users = $this->Page->User->find('list');
		$this->set(compact('parentPages', 'users'));
 
	}

/**
 * Admin edit for page.
 *
 * @param string $id, page id 
 * @access public
 */
	public function admin_edit($id = null) {
		try {
			$result = $this->Page->edit($id, $this->Auth->user('id'), $this->data);
			if ($result === true) {
				$this->Session->setFlash(__('Page saved', true));
				$this->redirect(array('action' => 'view', $this->Page->data['Page']['slug']));
				
			} else {
				$this->data = $result;
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}
		$parentPages = $this->Page->ParentPage->find('list');
		$users = $this->Page->User->find('list');
		$this->set(compact('parentPages', 'users'));
 
	}

/**
 * Admin delete for page.
 *
 * @param string $id, page id 
 * @access public
 */
	public function admin_delete($id = null) {
		try {
			$result = $this->Page->validateAndDelete($id, $this->Auth->user('id'), $this->data);
			if ($result === true) {
				$this->Session->setFlash(__('Page deleted', true));
				$this->redirect(array('action' => 'index'));
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->Page->data['page'])) {
			$this->set('page', $this->Page->data['page']);
		}
	}

}
?>