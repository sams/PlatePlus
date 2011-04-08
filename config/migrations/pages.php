<?php
class M4d9e99b6c80046169ba119e06a53203c extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'pages' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
					'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
					'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
					'level' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3, 'comment' => 'Page level in the tree hierarchy'),
					'slug' => array('type' => 'string', 'null' => false, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'comment' => 'URL friendly page name', 'charset' => 'utf8'),
					'url' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'Full URL relative to root of the application', 'charset' => 'utf8'),
					'title' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'content' => array('type' => 'text', 'null' => true, 'default' => NULL, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'description_meta_tag' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'keywords_meta_tag' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'draft' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
					'updated' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
					'user_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'slug' => array('column' => 'slug', 'unique' => 1),
						'parent_id' => array('column' => 'parent_id', 'unique' => 0),
						'lft' => array('column' => 'lft', 'unique' => 0),
						'rght' => array('column' => 'rght', 'unique' => 0),
						'draft' => array('column' => 'draft', 'unique' => 0),
						'content' => array('column' => 'content', 'unique' => 0),
					),
					'tableParameters' => array(),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'pages'
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
?>