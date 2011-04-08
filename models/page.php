<?php
class Page extends AppModel {
/**
 * Name
 *
 * @var string $name
 * @access public
 */
	public $name = 'Page';


/**
 * Behaviors
 *
 * @var array
 * @access public
 */
public $actsAs = array(
				'Search.Searchable',
				'Utils.Sluggable' => array(
								'label' => 'title',
								'slug' => 'slug',
								'scope' => array(),
								'separator' => '-',
								'length' => 255,
								'unique' => true,
								'update' => true,
								'trigger' => false
				),
				'Utils.Btree',
				'Utils.Publishable',
);

/**
 * Validation parameters - initialized in constructor
 *
 * @var array
 * @access public
 */
	public $validate = array();

/**
 * belongsTo association
 *
 * @var array $belongsTo 
 * @access public
 */
	public $belongsTo = array(
		'ParentPage' => array(
			'className' => 'Page',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
/**
 * hasMany association
 *
 * @var array $hasMany
 * @access public
 */

	public $hasMany = array(
		'ChildPage' => array(
			'className' => 'Page',
			'foreignKey' => 'parent_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);



/**
 * Constructor
 *
 * @param mixed $id Model ID
 * @param string $table Table name
 * @param string $ds Datasource
 * @access public
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->validate = array(
			'slug' => array(
				'notempty' => array('rule' => array('notempty'), 'required' => true, 'allowEmpty' => false, 'message' => __('Please enter a Slug', true))),
			'url' => array(
				'notempty' => array('rule' => array('notempty'), 'required' => true, 'allowEmpty' => false, 'message' => __('Please enter a Url', true))),
			'title' => array(
				'notempty' => array('rule' => array('notempty'), 'required' => true, 'allowEmpty' => false, 'message' => __('Please enter a Title', true))),
			'published' => array(
				'boolean' => array('rule' => array('boolean'), 'required' => true, 'allowEmpty' => false, 'message' => __('Please enter a Published', true))),
		);
	}



/**
 * Additional Find types to be used with find($type);
 *
 * @var array
 **/
	public $_findMethods = array(
		'search' => true
	);

/**
 * Field names accepted for search queries.
 *
 * @var array
 * @see SearchableBehavior
 */
	public $filterArgs = array(
		//array('name' => 'title', 'type' => 'string'),
	);
	

/**
 * Adds a new record to the database
 *
 * @param string $userId, user id
 * @param array post data, should be Contoller->data
 * @return array
 * @access public
 */
	public function add($userId = null, $data = null) {
		if (!empty($data)) {
			$data['Page']['user_id'] = $userId;
			$this->create();
			$result = $this->save($data);
			if ($result !== false) {
				$this->data = array_merge($data, $result);
				return true;
			} else {
				throw new OutOfBoundsException(__('Could not save the page, please check your inputs.', true));
			}
			return $return;
		}
	}

/**
 * Edits an existing Page.
 *
 * @param string $id, page id 
 * @param string $userId, user id
 * @param array $data, controller post data usually $this->data
 * @return mixed True on successfully save else post data as array
 * @throws OutOfBoundsException If the element does not exists
 * @access public
 */
	public function edit($id = null, $userId = null, $data = null) {
		$page = $this->find('first', array(
			'contain' => array('AppUser'),
			'conditions' => array(
				"{$this->alias}.{$this->primaryKey}" => $id,
				"{$this->alias}.user_id" => $userId
				)));

		if (empty($page)) {
			throw new OutOfBoundsException(__('Invalid Page', true));
		}
		$this->set($page);

		if (!empty($data)) {
			$this->set($data);
			$result = $this->save(null, true);
			if ($result) {
				$this->data = $result;
				return true;
			} else {
				return $data;
			}
		} else {
			return $page;
		}
	}

/**
 * Returns the record of a Page.
 *
 * @param string $slug, page slug.
 * @return array
 * @throws OutOfBoundsException If the element does not exists
 * @access public
 */
	public function view($slug = null, $type = 'slug') {
		if($type == 'url' && $slug[0] !== '/') $slug = '/'.$slug;
		$page = $this->find('first', array(
			'conditions' => array(
				'Page.' . $type => $slug)));

		if (empty($page)) {
			//throw new OutOfBoundsException(__('Invalid Page', true));
		}

		return $page;
	}

/**
 * Validates the deletion
 *
 * @param string $id, page id 
 * @param string $userId, user id
 * @param array $data, controller post data usually $this->data
 * @return boolean True on success
 * @throws OutOfBoundsException If the element does not exists
 * @access public
 */
	public function validateAndDelete($id = null, $userId = null, $data = array()) {
		$page = $this->find('first', array(
			'conditions' => array(
				"{$this->alias}.{$this->primaryKey}" => $id,
				"{$this->alias}.user_id" => $userId
				)));

		if (empty($page)) {
			throw new OutOfBoundsException(__('Invalid Page', true));
		}

		$this->data['page'] = $page;
		if (!empty($data)) {
			$data['Page']['id'] = $id;
			$tmp = $this->validate;
			$this->validate = array(
				'id' => array('rule' => 'notEmpty'),
				'confirm' => array('rule' => '[1]'));

			$this->set($data);
			if ($this->validates()) {
				if ($this->delete($data['Page']['id'])) {
					return true;
				}
			}
			$this->validate = $tmp;
			throw new Exception(__('You need to confirm to delete this Page', true));
		}
	}

 
/**
 * Returns the search data
 *
 * @param string
 * @param array
 * @param array
 * @return
 * @access protected
 */
	protected function _findSearch($state, $query, $results = array()) {
		if ($state == 'before') {
			$this->Behaviors->attach('Containable', array('autoFields' => false));
			$results = $query;

			if (isset($query['operation']) && $query['operation'] == 'count') {
				$results['fields'] = array('COUNT(*)');
			}

			return $results;
		} elseif ($state == 'after') {
			if (isset($query['operation']) && $query['operation'] == 'count') {
				if (isset($query['group']) && is_array($query['group']) && !empty($query['group'])) {
					return count($results);
				}
				return $results[0][0]['COUNT(*)'];
			}
			return $results;
		}
	}

/**
 * Customized paginateCount method
 *
 * @param array
 * @param integer
 * @param array
 * @return
 * @access public
 */
	function paginateCount($conditions = array(), $recursive = 0, $extra = array()) {
		$parameters = compact('conditions');
		if ($recursive != $this->recursive) {
			$parameters['recursive'] = $recursive;
		}
		if (isset($extra['type']) && isset($this->_findMethods[$extra['type']])) {
			$extra['operation'] = 'count';
			return $this->find($extra['type'], array_merge($parameters, $extra));
		} else {
			return $this->find('count', array_merge($parameters, $extra));
		}
	}


}
?>