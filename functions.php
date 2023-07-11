<?php
require_once(__DIR__ . '/inc/bootstrap.php');

if (!class_exists('Timber')) {
	add_action('admin_notices', function () {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
	});

	add_filter('template_include', function ($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

Timber::$dirname = array('templates');

add_filter('wpcf7_autop_or_not', '__return_false');

remove_filter('get_the_excerpt', 'wp_trim_excerpt');

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

add_filter('use_block_editor_for_post_type', function ($enabled, $post_type) {
	return 'page' === $post_type ? false : $enabled;
}, 10, 2);

add_action('pre_get_posts', 'show_all_locations');

function show_all_locations($query) {
	if (!is_admin() && $query->is_main_query()) {
		if (is_post_type_archive('location')) {
			$query->set('posts_per_page', -1);
		}
	}
}
