<?php
/**
 *
 * Home Page. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Minty, https://www.mintymods.info/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace minty\homepage\controller;

class main_controller {

	protected $config;
	protected $helper;
	protected $template;
	protected $language;
	protected $db;
	protected $log;
	protected $table_name = "";

	public function __construct(\phpbb\config\config $config, 
								\phpbb\controller\helper $helper, 
								\phpbb\template\template $template, 
								\phpbb\language\language $language,
								\phpbb\db\driver\factory $dbal,
								\phpbb\log\log $log,
								$table_name								
								) {
		$this->config	= $config;
		$this->helper	= $helper;
		$this->template	= $template;
		$this->language	= $language;
		$this->db = $dbal;
		$this->log		= $log;
		$this->table_name = $table_name;		
	}


	public function handle($command, $post_id, $section, $topic_id, $forum_id) {
		$json_response = new \phpbb\json_response();
		$result = false;
		switch ($command) {
			case 'remove' :
				return $json_response->send($this->deleteRecord($post_id));
			case 'add' :
				return $json_response->send($this->addRecord($post_id, $section, $topic_id, $forum_id));
			case 'render' :
				$this->template->assign_var('HOMEPAGE_RESULT', 'render me');
				return $this->helper->render('@minty_homepage/homepage_body.html', $result);
			case 'podcast' :
				$this->template->assign_var('HOMEPAGE_RESULT', 'PodCast');
				return $this->helper->render('@minty_homepage/homepage_body.html', $result);
		}
	}
	
	public function deleteRecord($post_id) {
		$sql = 'DELETE FROM ' . $this->table_name . ' WHERE post_id = ' . $post_id;
		return $this->db->sql_query($sql);
	}

	public function addRecord($post_id, $section, $topic_id, $forum_id) {
		$this->deleteRecord($post_id);
		if ($section != 'none') {
			$sql_ary = array(
				'post_id'      => $post_id,
				'topic_id'      => $topic_id,
				'forum_id'      => $forum_id,
				'link_type'     => $section,
				
			);
			$sql = 'INSERT INTO ' . $this->table_name . $this->db->sql_build_array('INSERT', $sql_ary);
			$result = $this->db->sql_query($sql);
		}
		return $result;
	}


}
