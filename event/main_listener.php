<?php
/**
 *
 * Home Page. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Minty, https://www.mintymods.info/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace minty\homepage\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Home Page Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return array(
			'core.user_setup'							=> 'load_language_on_setup',
			'core.page_header'							=> 'add_page_header_link',
			'core.viewonline_overwrite_location'		=> 'viewonline_page',
			'core.viewtopic_modify_post_row' 			=> 'viewtopic_modify_postrow',
		);
	}


	protected $language;
	protected $helper;
	protected $template;
	protected $php_ext;
	protected $db;
	protected $log;
	protected $table_name = "phpbb_minty_homepage"; // @todo

	public function __construct(\phpbb\language\language $language, 
								\phpbb\controller\helper $helper, 
								\phpbb\template\template $template, 
								$php_ext,
								\phpbb\db\driver\factory $dbal,
								\phpbb\log\log $log,
								$table_name
								)
	{
		$this->language = $language;
		$this->helper   = $helper;
		$this->template = $template;
		$this->php_ext  = $php_ext;
		$this->db = $dbal;
		$this->log		= $log;
		$this->table_name = $table_name;
	}

	public function load_language_on_setup($event) {
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'minty/homepage',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function add_page_header_link() {
		$this->template->assign_vars(array(
			'U_HOMEPAGE_PAGE'	=> $this->helper->route('minty_homepage_controller', array()),
		));
	}

	public function viewtopic_modify_postrow($event) {
		$row = $event['row'];
		$postrow = $event['post_row'];
		$topic_data = $event['topic_data'];
		$forum_id = (int) $row['forum_id'];
		$poster_id = (int) $row['user_id'];
		$this->output_homepage($poster_id, $postrow, $row, $topic_data, $forum_id);
		$event['post_row'] = $postrow;
	}

	public function output_homepage($poster_id, &$postrow, $row, $topic_data, $forum_id) 	{
		if (!empty($postrow)) {
			$status = $this->get_homepage_status($row['post_id']);
			$postrow = array_merge($postrow, $status, array(
				'HOMEPAGE_FORUM_ID' => $forum_id,
				'HOMEPAGE_TOPIC_DATE' => $topic_data,
			));
		}
	}

	function get_homepage_status($post_id) {
		$status = 'none';
		$sql = 'SELECT link_type FROM ' . $this->table_name . ' WHERE post_id = ' . $post_id;
		$result = $this->db->sql_query($sql);
		if ($row = $this->db->sql_fetchrow($result))	{
			$status = $row['link_type'];
		}
		$this->db->sql_freeresult($result);
		
		return array(
			'HOMEPAGE_POST_ID' => $post_id,
			'HOMEPAGE_STATUS' => $status,
			'HOMEPAGE_IMG' => $this->get_status_image($status),
		);
	}
	
	function get_status_image($status) {
		$image = 'icon fas fa-bug icon-error';
		switch ($status) {
			case 'none' :
				$image = 'icon fas fa-plus-square';
				break;
			case 'homepage' :
				$image = 'icon fas fa-address-card';
				break;
			case 'tutorials' :
				$image = 'icon fas fa-graduation-cap';
				break;
			case 'podcasts' :
				$image = 'icon fas fa-podcast';
				break;
			case 'diaries' :
				$image = 'icon fas fa-book';
				break;
			case 'reviews' :
				$image = 'icon fas fa-binoculars';
				break;
			case 'sponsors' :
				$image = 'icon fas fa-gift';
				break;
			case 'error' :
				$image = 'icon fas fa-exclamation-triangle icon-error';
				break;				
		}
		return $image;
	}

}
