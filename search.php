<?php
/**
 * Search results page
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$templates = array( 'search.twig', 'archive.twig', 'index.twig' );
$context = Timber::context();

$context['posts'] = Timber\PostQuery();
$context['sidebar'] = Timber::get_sidebar('sidebar.php');

Timber::render( $templates, $context );
