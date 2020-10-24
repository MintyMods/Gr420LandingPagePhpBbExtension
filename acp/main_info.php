<?php
/**
 *
 * Home Page. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Minty, https://www.mintymods.info/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace minty\homepage\acp;

/**
 * Home Page ACP module info.
 */
class main_info
{
	public function module()
	{
		return array(
			'filename'	=> '\minty\homepage\acp\main_module',
			'title'		=> 'ACP_HOMEPAGE_TITLE',
			'modes'		=> array(
				'settings'	=> array(
					'title'	=> 'ACP_HOMEPAGE',
					'auth'	=> 'ext_minty/homepage && acl_a_board',
					'cat'	=> array('ACP_HOMEPAGE_TITLE')
				),
			),
		);
	}
}
