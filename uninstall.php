<?php
//if uninstall not called from WordPress exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

function minsta_delete_plugin()
{
	// プラグインoption削除
	delete_option( 'minsta');
}

minsta_delete_plugin();
