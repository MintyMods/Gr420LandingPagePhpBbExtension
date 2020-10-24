<?php
/**
 *
 * Home Page. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Minty, https://www.mintymods.info/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace minty\homepage\migrations;

class install_acp_module extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['minty_homepage_goodbye']);
	}

	public static function depends_on()
	{
		return array('\phpbb\db\migration\data\v320\v320');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('minty_homepage_goodbye', 0)),

			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_HOMEPAGE_TITLE'
			)),
			array('module.add', array(
				'acp',
				'ACP_HOMEPAGE_TITLE',
				array(
					'module_basename'	=> '\minty\homepage\acp\main_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}
}
