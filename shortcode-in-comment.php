<?php
/**
 * Plugin Name: Shortcode in Comment
 * Plugin URI: http://www.kelvinblog.tk/shortcode-in-comment
 * Description: Allow users to use some shortcodes in comments
 * Version: 1.1.1
 * (function.bugfix.wordpress_update)
 * Author: Kelvin Ng
 * Author URI: http://www.kelvinblog.tk
 * License: GPL3
 */

class shortcode_in_comment_options_page
{
	function __construct()
	{
		add_action('admin_menu', array($this, 'admin_menu'));
	}

	function admin_menu()
	{
		add_options_page(__('Enabled Shortcode in Comment', 'shortcode-in-comment'), __('Shortcode in Comment', 'shortcode-in-comment'), 'manage_options', 'shortcode_in_comment', array($this, 'settings_page'));
	}

	function settings_page()
	{
		if (isset($_POST['comment_enabled_shortcode']))
		{
			add_option('comment_enabled_shortcode');
			update_option('comment_enabled_shortcode', $_POST['comment_enabled_shortcode']);
		}
?>
<div class="wrap">
<h2><?php echo _e('Enabled Shortcode in Comment', 'shortcode-in-comment') ?><h2>

<form method="post" action=""> <!-- <form method="post"> can be used in HTML5, which is the standard --!>
	<?php settings_fields('shortcode-in-comment-settings-group'); ?>
	<?php do_settings_sections('shortcode-in-comment-settings-group'); ?>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><?php _e('Enabled shortcode (comma seperated)', 'shortcode-in-comment') ?></th>
			<td><input type="text" name="comment_enabled_shortcode" value="<?php echo get_option('comment_enabled_shortcode'); ?>" /></td>
		</tr>
	</table>
	
	<?php submit_button(); ?>
</form>
</div>
<?php
	}
}

class shortcode_in_comment
{
	function __construct()
	{
		load_plugin_textdomain('shortcode-in-comment', false, basename(dirname(__FILE__)) . '/languages/');

		add_filter('comments_template', array($this, 'init_enabled_shortcodes'));
	}

	function init_enabled_shortcodes()
	{
		$enabled_shortcodes = get_option('comment_enabled_shortcode');
		$enabled_shortcodes = explode(',', $enabled_shortcodes);

		global $shortcode_tags;
		foreach ($shortcode_tags as $tag => $func)
		{
			if (!in_array($tag, $enabled_shortcodes))
			{
				remove_shortcode($tag);
			}
		}

		add_filter('comment_text', 'do_shortcode');
	}
}

new shortcode_in_comment_options_page;
new shortcode_in_comment;

?>
