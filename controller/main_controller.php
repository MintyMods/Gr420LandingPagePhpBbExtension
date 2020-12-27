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
	protected $php_ext;
	protected $phpbb_root_path;

	public function __construct(\phpbb\config\config $config, 
								\phpbb\controller\helper $helper, 
								\phpbb\template\template $template, 
								\phpbb\language\language $language,
								\phpbb\db\driver\factory $dbal,
								\phpbb\log\log $log,
								$table_name,
								$phpbb_root_path, 
								$phpEx
								) {
		$this->config	= $config;
		$this->helper	= $helper;
		$this->template	= $template;
		$this->language	= $language;
		$this->db = $dbal;
		$this->log		= $log;
		$this->table_name = $table_name;
		$this->php_ext = $phpEx;
		$this->phpbb_root_path = $phpbb_root_path;		
	}


	public function handle($command, $post_id, $section, $topic_id, $forum_id) {
		$json_response = new \phpbb\json_response();
		$result = false;
		$search_limit = 5;
		$this->template->assign_vars(array(
			'HOMEPAGE_SECTION'	=> $section,
		));
		switch ($command) {
			case 'remove' :
				return $json_response->send($this->deleteRecord($post_id));
			case 'add' :
				return $json_response->send($this->addRecord($post_id, $section, $topic_id, $forum_id));
			case 'render' :
			case 'homepage' :
				$this->selectPostForSection($post_id, $section, $topic_id, $forum_id);
				return $this->helper->render('@minty_homepage/homepage.html', $result);
			case 'podcasts' :
				$this->selectPostForSection($post_id, $section, $topic_id, $forum_id);
				return $this->helper->render('@minty_homepage/podcasts.html', $result);
			case 'tutorials' :
				$this->selectPostForSection($post_id, $section, $topic_id, $forum_id);				
				return $this->helper->render('@minty_homepage/tutorials.html', $result);
			case 'links' :
				$this->selectPostForSection($post_id, $section, $topic_id, $forum_id);
				return $this->helper->render('@minty_homepage/links.html', $result);
			case 'compititions' :
				$this->selectPostForSection($post_id, $section, $topic_id, $forum_id);
				return $this->helper->render('@minty_homepage/comps.html', $result);
			case 'reviews' :
				$this->selectPostForSection($post_id, $section, $topic_id, $forum_id);
				return $this->helper->render('@minty_homepage/reviews.html', $result);
			case 'diaries' :
				$this->selectPostForSection($post_id, $section, $topic_id, $forum_id);
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


	public function selectPostForSection($post_id, $section, $topic_id, $forum_id) {
		
		global $db, $auth, $user;
		
		if (!class_exists('bbcode')) {
			include_once($this->phpbb_root_path . 'includes/bbcode' . $this->php_ext);
		}

		$posts_ary = array(
			'SELECT'    => 'p.*',
			'FROM'      => array(
				POSTS_TABLE     => 'p',
				$this->table_name => 'homepage',
			),
			'LEFT_JOIN' => array(
				array(
					'FROM'  => array(USERS_TABLE => 'u'),
					'ON'    => 'u.user_id = p.poster_id'
				),
				array(
					'FROM'  => array(TOPICS_TABLE => 't'),
					'ON'    => 'p.topic_id = t.topic_id'
				),
			),
			'WHERE'     =>  'homepage.post_id = p.post_id AND homepage.link_type = "' . $section . '"'
		);
		
		$posts = $db->sql_build_query('SELECT', $posts_ary);
	    $posts_result =$db->sql_query($posts);
		
		while( $posts_row = $db->sql_fetchrow($posts_result) ) {
			$topic_title = $posts_row['post_subject'];
			$post_author = get_username_string('full', $posts_row['poster_id'], $posts_row['username'], $posts_row['user_colour']);
			$post_date = $user->format_date($posts_row['post_time']);
			$post_link = append_sid($this->phpbb_root_path . "viewtopic" . $this->php_ext, 'f=' . $posts_row['forum_id'] . '&amp;t=' . $posts_row['topic_id'] . '&amp;p=' . $posts_row['post_id']) . '#p' . $posts_row['post_id'];
			$post_text = generate_text_for_display( $posts_row[ 'post_text' ], $posts_row[ 'bbcode_uid' ], $posts_row[ 'bbcode_bitfield' ], 7 );

		

			$this->template->assign_block_vars('HOMEPAGE_POSTS', array(
			 'TOPIC_TITLE'       => censor_text($topic_title),
			 'POST_AUTHOR'       => $post_author,
			 'POST_DATE'       	 => $post_date,
			 'POST_LINK'      	 => $post_link,
			 'POST_TEXT'         => censor_text($post_text),
			));
		}
	}

}
