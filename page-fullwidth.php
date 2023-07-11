<?php
/**
 * Template Name: Fullwidth
 */

$context = Timber::context();

$timber_post = new Timber\Post();
$context['post'] = $timber_post;

Timber::render( array( 'page-fullwidth.twig' ), $context );
