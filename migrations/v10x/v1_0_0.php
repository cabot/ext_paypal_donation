<?php
/**
*
* PayPal Donation extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Skouat
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace skouat\ppde\migrations\v10x;

class v1_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['ppde_version']) && version_compare($this->config['ppde_version'], '1.0.0', '>=');
	}

	public static function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\gold');
	}

	public function update_schema()
	{
		return array(
			'add_tables' => array(
				$this->table_prefix . 'ppde_item' => array(
					'COLUMNS' => array(
						'item_id'					=> array('UINT', null, 'auto_increment'),
						'item_type'					=> array('VCHAR:16', ''),
						'item_name'					=> array('VCHAR:50', ''),
						'item_iso_code'				=> array('VCHAR:10', ''),
						'item_symbol'				=> array('VCHAR:10', ''),
						'item_text'					=> array('TEXT', ''),
						'item_enable'				=> array('BOOL', 1),
						'left_id'					=> array('UINT', 0),
						'right_id'					=> array('UINT', 0),
						'item_text_bbcode_bitfield'	=> array('VCHAR:255', ''),
						'item_text_bbcode_uid'		=> array('VCHAR:8', ''),
						'item_text_bbcode_options'	=> array('UINT:4', 7),
					),

					'PRIMARY_KEY'	=> array('item_id'),
				),

				$this->table_prefix . 'ppde_data' => array(
					'COLUMNS' => array(
						'transaction_id'	=> array('UINT', null, 'auto_increment'),
						'txn_id'			=> array('VCHAR:18', ''),
						'txn_type'			=> array('VCHAR:32', ''),
						'confirmed'			=> array('BOOL', 0),
						'user_id'			=> array('UINT', 0),
						'item_name'			=> array('VCHAR:128', ''),
						'item_number'		=> array('VCHAR:128', ''),
						'payment_time'		=> array('TIMESTAMP', 0),
						'business'			=> array('VCHAR:128', ''),
						'receiver_id'		=> array('VCHAR:16', ''),
						'receiver_email'	=> array('VCHAR:128', ''),
						'payment_status'	=> array('VCHAR:32', ''),
						'mc_gross'			=> array('DECIMAL:8', 0),
						'mc_fee'			=> array('DECIMAL:8', 0),
						'mc_currency'		=> array('VCHAR:16', ''),
						'settle_amount'		=> array('DECIMAL:8', 0),
						'settle_currency'	=> array('VCHAR:16', ''),
						'net_amount'		=> array('DECIMAL:8', 0),
						'exchange_rate'		=> array('VCHAR:16', ''),
						'payment_type'		=> array('VCHAR:16', ''),
						'payment_date'		=> array('VCHAR:32', ''),
						'payer_id'			=> array('VCHAR:16', ''),
						'payer_email'		=> array('VCHAR:128', ''),
						'payer_status'		=> array('VCHAR:16', ''),
						'first_name'		=> array('VCHAR:10', ''),
						'last_name'			=> array('VCHAR:10', ''),
//						'memo'				=> array('VCHAR', ''),
					),

					'PRIMARY_KEY'			=> array('transaction_id'),
					'KEYS' => array(
						'user_id'			=> array('INDEX', array('user_id')),
						'txn_id'			=> array('INDEX', array('txn_id')),
					),
				),
			),
		);
	}


	public function update_data()
	{
		return array(
			// Global Settings
			array('config.add', array('ppde_enable', false)),
			array('config.add', array('ppde_account_id', '')),
			array('config.add', array('ppde_default_currency', 1)),
			array('config.add', array('ppde_default_value', 0)),
			array('config.add', array('ppde_dropbox_enable', false)),
			array('config.add', array('ppde_dropbox_value', '1,2,3,4,5,10,20,25,50,100')),

			// Sandbox Settings
			array('config.add', array('ppde_sandbox_enable', false)),
			array('config.add', array('ppde_sandbox_founder_enable', true)),
			array('config.add', array('ppde_sandbox_address', '')),

			// Statistics Settings
			array('config.add', array('ppde_stats_index_enable', false)),
			array('config.add', array('ppde_goal', 0)),
			array('config.add', array('ppde_goal_enable', false)),
			array('config.add', array('ppde_raised', 0)),
			array('config.add', array('ppde_raised_enable', false)),
			array('config.add', array('ppde_used', 0)),
			array('config.add', array('ppde_used_enable', false)),

			//Misc Settings
			array('config.add', array('ppde_install_date', time())),

			array('permission.add', array('u_ppde_use', true)),
			array('permission.add', array('a_ppde_manage', true)),

			array('permission.permission_set',
				array('ROLE_USER_FULL',
					array('u_ppde_use')
				)
			),

			array('permission.permission_set',
				array('ROLE_ADMIN_FULL',
					array('a_ppde_manage')
				)
			),

			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_DONATION_MOD',
				array(
					'module_enabled'	=> 1,
					'module_display'	=> 1,
					'module_langname'	=> 'ACP_DONATION_MOD',
					'module_auth'		=> 'ext_skouat/ppde && acl_a_ppde_manage',
				)
			)),

			array('module.add', array(
				'acp',
				'ACP_DONATION_MOD',
				array(
					'module_basename'	=> '\skouat\ppde\acp\ppde_module',
					'modes'				=> array('overview', 'settings', 'donation_pages', 'currency'),
				)
			)),
		);
	}
}
