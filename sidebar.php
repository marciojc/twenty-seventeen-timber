<?php
/**
 * The Template for displaying all single posts
 *
 *
 * @package  WordPress
 * @subpackage  Timber
 */

$data = array();
$data['sidebar1'] = Timber::get_widgets('sidebar-1');
Timber::render( array( 'sidebar.twig' ), $data );
