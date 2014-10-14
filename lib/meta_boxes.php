<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category TrendyRoom
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'cmb_sample_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_sample_metaboxes( array $meta_boxes ) {

	$categories = get_terms( 'category', array( 'hide_empty' => '0' ) );
	$cats = array();
	foreach($categories as $cat) {
		$cats[$cat->term_id] = $cat->name;
	}

	$prefix = '_home1_';

	$meta_boxes[] = array(
		'id'         => 'home1_metabox',
		'title'      => 'Custom info for homepage 1 page template',
		'show_on'    => array( 'key' => 'page-template', 'value' => 'page-template-home1.php'),
		'pages'      => array( 'page', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array( // Text Input
			    'name' => 'Number of posts shown in the slider', // <label>
			    'desc'  => 'The number of posts shown on the slider!', // description
			    'id'    => $prefix . 'nrposts', // field id and name
			    'type'  => 'text', // type of field,
			    'std' => 8
			    ),
			array(
				'name'     => 'Categories to include on the slider',
				'desc'     => 'The page will show portfolio items from these categories',
				'id'       => $prefix . 'categories',
				'type'     => 'multicheck',
				'options' => $cats, // Taxonomy Slug
			),
		)
	);

	$prefix = '_home2_';

	$meta_boxes[] = array(
		'id'         => 'home2_metabox',
		'title'      => 'Custom info for homepage 2 template',
		'show_on'    => array( 'key' => 'page-template', 'value' => 'page-template-home2.php'),
		'pages'      => array( 'page', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array( // Text Input
			    'name' => 'Number of posts shown in the top slider', // <label>
			    'desc'  => 'The number of posts shown on the top slider!', // description
			    'id'    => $prefix . 'nrposts', // field id and name
			    'type'  => 'text', // type of field,
			    'std' => 8
			    ),
			array(
				'name'     => 'Categories to include on the top slider',
				'desc'     => 'The slider will show posts from these categories',
				'id'       => $prefix . 'categories',
				'type'     => 'multicheck',
				'options' => $cats, // Taxonomy Slug
			),
			array(
				'name'     => 'Categories to include on the normal blogroll below the slider',
				'desc'     => 'The blogroll below the slider will show posts from these categories',
				'id'       => $prefix . 'categories_normal',
				'type'     => 'multicheck',
				'options' => $cats, // Taxonomy Slug
			),
		)
	);

	$prefix = '_home3_';

	$meta_boxes[] = array(
		'id'         => 'home3_metabox',
		'title'      => 'Custom info for homepage 3 page template',
		'show_on'    => array( 'key' => 'page-template', 'value' => 'page-template-home3.php'),
		'pages'      => array( 'page', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array( // Text Input
			    'name' => 'Number of posts shown in the top slider', // <label>
			    'desc'  => 'The number of posts shown on the top slider!', // description
			    'id'    => $prefix . 'nrposts', // field id and name
			    'type'  => 'text', // type of field,
			    'std' => 8
			    ),
			array(
				'name'     => 'Categories to include on the top slider',
				'desc'     => 'The slider will show posts from these categories',
				'id'       => $prefix . 'categories',
				'type'     => 'multicheck',
				'options' => $cats, // Taxonomy Slug
			),
			array(
				'name'     => 'Categories to include on the normal blogroll below the slider',
				'desc'     => 'The blogroll below the slider will show posts from these categories',
				'id'       => $prefix . 'categories_normal',
				'type'     => 'multicheck',
				'options' => $cats, // Taxonomy Slug
			),
		)
	);

	$prefix = '_single_';

	$meta_boxes[] = array(
		'id'         => 'single_metabox',
		'title'      => 'Custom info for single posts',
		'pages'      => array( 'post', 'page' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name'    => __( 'Show sidebar on post page?', 'cmb' ),
				'desc'    => __( 'Here you can disable the sidebar and make the post full width', 'cmb' ),
				'id'      => $prefix . 'fullwidth',
				'type'    => 'select',
				'options' => array(
					1 	=> __( 'Full Width', 'cmb' ),
					2   => __( 'With Sidebar', 'cmb' ),
				),
				'std' => 1
			),

		)
	);

	return $meta_boxes;
}
?>