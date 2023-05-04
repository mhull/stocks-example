<?php

namespace StocksWp\PostTypes;

class ExchangePostType {
	public static function register() {
		$labels = array(
			'name'               => _x( 'Exchanges', 'post type general name', '' ),
			'singular_name'      => _x( 'Exchange', 'post type singular name', '' ),
			'menu_name'          => _x( 'Exchanges', 'admin menu', '' ),
			'name_admin_bar'     => _x( 'Exchange', 'add new on admin bar', '' ),
			'add_new'            => _x( 'Add New', '', '' ),
			'add_new_item'       => __( 'Add New Exchange', '' ),
			'new_item'           => __( 'New Exchange', '' ),
			'edit_item'          => __( 'Edit Exchange', '' ),
			'view_item'          => __( 'View Exchange', '' ),
			'all_items'          => __( 'All Exchanges', '' ),
			'search_items'       => __( 'Search Exchanges', '' ),
			'parent_item_colon'  => __( 'Parent :', '' ),
			'not_found'          => __( 'No exchanges found.', '' ),
			'not_found_in_trash' => __( 'No exchanges found in Trash.', '' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Exchanges', '' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon'          => 'dashicons-building',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'exchanges' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		);

		register_post_type( 'exchange', $args );
	}
}
