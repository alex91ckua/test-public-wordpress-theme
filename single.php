<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();
$timber_post = Timber::query_post();
$context['post'] = $timber_post;

if ( $timber_post->post_type === 'post' ) {
	$context['related_posts'] = Timber::get_posts(array(
		'post_type' => $timber_post->post_type,
		'category__in' => wp_get_post_categories(get_the_ID()),
		'post__not_in' => array(get_the_ID()),
		'posts_per_page' => 3,
		'orderby' => 'date'
	));
}

if ( post_password_required( $timber_post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array( 'single-' . $timber_post->ID . '.twig', 'single-' . $timber_post->post_type . '.twig', 'single.twig' ), $context );
}
