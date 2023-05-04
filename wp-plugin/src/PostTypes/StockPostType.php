<?php

namespace StocksWp\PostTypes;

class StockPostType {
	public static function register() {
		$labels = array(
			'name'               => _x( 'Stocks', 'post type general name', '' ),
			'singular_name'      => _x( 'Stock', 'post type singular name', '' ),
			'menu_name'          => _x( 'Stocks', 'admin menu', '' ),
			'name_admin_bar'     => _x( 'Stock', 'add new on admin bar', '' ),
			'add_new'            => _x( 'Add New', '', '' ),
			'add_new_item'       => __( 'Add New Stock', '' ),
			'new_item'           => __( 'New Stock', '' ),
			'edit_item'          => __( 'Edit Stock', '' ),
			'view_item'          => __( 'View Stock', '' ),
			'all_items'          => __( 'All Stocks', '' ),
			'search_items'       => __( 'Search Stocks', '' ),
			'parent_item_colon'  => __( 'Parent :', '' ),
			'not_found'          => __( 'No stocks found.', '' ),
			'not_found_in_trash' => __( 'No stocks found in Trash.', '' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Stocks', '' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon'          => 'dashicons-chart-line',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'stocks' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		);

		register_post_type( 'stock', $args );
	}
}
