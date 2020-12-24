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
			case 'homepage' :
				$this->template->assign_var('HOMEPAGE_RESULT', 'homepage render me');
				return $this->helper->render('@minty_homepage/homepage.html', $result);
			case 'podcasts' :
				$this->template->assign_var('HOMEPAGE_RESULT', 'PodCasts');
				return $this->helper->render('@minty_homepage/podcasts.html', $result);
			case 'tutorials' :
				$this->template->assign_var('HOMEPAGE_RESULT', 'Tutorials');
				return $this->helper->render('@minty_homepage/tutorials.html', $result);
			case 'links' :
				$this->template->assign_var('HOMEPAGE_RESULT', 'Links');
				return $this->helper->render('@minty_homepage/links.html', $result);
			case 'comps' :
				$this->template->assign_var('HOMEPAGE_RESULT', 'Competitions');
				return $this->helper->render('@minty_homepage/comps.html', $result);
			case 'reviews' :
				$this->template->assign_var('HOMEPAGE_RESULT', 'Reviews');
				return $this->helper->render('@minty_homepage/reviews.html', $result);
			case 'diaries' :
				$this->template->assign_var('HOMEPAGE_RESULT', 'Diaries');
				return $this->helper->render('@minty_homepage/diaries.html', $result);
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
