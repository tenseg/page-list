<?php
/*
Plugin Name: TG Page-list
Plugin URI: http://wordpress.org/plugins/page-list/
Description: This is a modified version of the Page-list plugin by webvitaly. It provides a [pagelist_dev] shortcode in addition to the [pagelist], [subpages], [siblings] and [pagelist_ext] shortcodes. The version number has been bumped to prevent overwriting by the original
Version: 500.1.1
Author: webvitaly, tenseg
Author URI: http://web-profile.net/wordpress/plugins/
License: GPLv3
 */

define( 'PAGE_LIST_PLUGIN_VERSION', '500.1.1' );

$pagelist_unq_settings = [
	'version'            => PAGE_LIST_PLUGIN_VERSION,
	'powered_by'         => "\n" . '<!-- Page-list plugin v.' . PAGE_LIST_PLUGIN_VERSION . ' wordpress.org/plugins/page-list/ -->' . "\n",
	'page_list_defaults' => [
		'depth'        => '0',
		'child_of'     => '0',
		'exclude'      => '0',
		'exclude_tree' => '',
		'include'      => '0',
		'title_li'     => '',
		'number'       => '',
		'offset'       => '',
		'meta_key'     => '',
		'meta_value'   => '',
		'show_date'    => '',
		'date_format'  => get_option( 'date_format' ),
		'authors'      => '',
		'sort_column'  => 'menu_order, post_title',
		'sort_order'   => 'ASC',
		'link_before'  => '',
		'link_after'   => '',
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'class'        => '',
	],
];

if ( !function_exists( 'pagelist_unqprfx_add_stylesheet' ) ) {
	function pagelist_unqprfx_add_stylesheet() {
		wp_enqueue_style( 'page-list-style', plugins_url( '/css/page-list.css', __FILE__ ), false, PAGE_LIST_PLUGIN_VERSION, 'all' );
	}
	add_action( 'wp_enqueue_scripts', 'pagelist_unqprfx_add_stylesheet' );
}

if ( !function_exists( 'pagelist_unqprfx_shortcode' ) ) {
	function pagelist_unqprfx_shortcode( $atts ) {
		global $post, $pagelist_unq_settings;
		$return = '';
		extract( shortcode_atts( $pagelist_unq_settings['page_list_defaults'], $atts ) );

		$page_list_args = [
			'depth'        => $depth,
			'child_of'     => pagelist_unqprfx_norm_params( $child_of ),
			'exclude'      => pagelist_unqprfx_norm_params( $exclude ),
			'exclude_tree' => $exclude_tree,
			'include'      => $include,
			'title_li'     => $title_li,
			'number'       => $number,
			'offset'       => $offset,
			'meta_key'     => $meta_key,
			'meta_value'   => $meta_value,
			'show_date'    => $show_date,
			'date_format'  => $date_format,
			'echo'         => 0,
			'authors'      => $authors,
			'sort_column'  => $sort_column,
			'sort_order'   => $sort_order,
			'link_before'  => $link_before,
			'link_after'   => $link_after,
			'post_type'    => $post_type,
			'post_status'  => $post_status,
		];
		$list_pages = wp_list_pages( $page_list_args );

		$return .= $pagelist_unq_settings['powered_by'];
		if ( $list_pages ) {
			$return .= '<ul class="page-list ' . $class . '">' . "\n" . $list_pages . "\n" . '</ul>';
		} else {
			$return .= '<!-- no pages to show -->';
		}
		return $return;
	}
	add_shortcode( 'pagelist', 'pagelist_unqprfx_shortcode' );
	add_shortcode( 'page_list', 'pagelist_unqprfx_shortcode' );
	add_shortcode( 'page-list', 'pagelist_unqprfx_shortcode' ); // not good (Shortcode names should be all lowercase and use all letters, but numbers and underscores (not dashes!) should work fine too.)
	add_shortcode( 'sitemap', 'pagelist_unqprfx_shortcode' );
}

if ( !function_exists( 'subpages_unqprfx_shortcode' ) ) {
	function subpages_unqprfx_shortcode( $atts ) {
		global $post, $pagelist_unq_settings;
		$return = '';
		extract( shortcode_atts( $pagelist_unq_settings['page_list_defaults'], $atts ) );

		$page_list_args = [
			'depth'        => $depth,
			'child_of'     => $post->ID,
			'exclude'      => pagelist_unqprfx_norm_params( $exclude ),
			'exclude_tree' => $exclude_tree,
			'include'      => $include,
			'title_li'     => $title_li,
			'number'       => $number,
			'offset'       => $offset,
			'meta_key'     => $meta_key,
			'meta_value'   => $meta_value,
			'show_date'    => $show_date,
			'date_format'  => $date_format,
			'echo'         => 0,
			'authors'      => $authors,
			'sort_column'  => $sort_column,
			'sort_order'   => $sort_order,
			'link_before'  => $link_before,
			'link_after'   => $link_after,
			'post_type'    => $post_type,
			'post_status'  => $post_status,
		];
		$list_pages = wp_list_pages( $page_list_args );

		$return .= $pagelist_unq_settings['powered_by'];
		if ( $list_pages ) {
			$return .= '<ul class="page-list subpages-page-list ' . $class . '">' . "\n" . $list_pages . "\n" . '</ul>';
		} else {
			$return .= '<!-- no pages to show -->';
		}
		return $return;
	}
	add_shortcode( 'subpages', 'subpages_unqprfx_shortcode' );
	add_shortcode( 'sub_pages', 'subpages_unqprfx_shortcode' );
}

if ( !function_exists( 'siblings_unqprfx_shortcode' ) ) {
	function siblings_unqprfx_shortcode( $atts ) {
		global $post, $pagelist_unq_settings;
		$return = '';
		extract( shortcode_atts( $pagelist_unq_settings['page_list_defaults'], $atts ) );

		if ( 'current' == $exclude || 'this' == $exclude ) {
			$exclude = $post->ID;
		}

		$page_list_args = [
			'depth'        => $depth,
			'child_of'     => $post->post_parent,
			'exclude'      => pagelist_unqprfx_norm_params( $exclude ),
			'exclude_tree' => $exclude_tree,
			'include'      => $include,
			'title_li'     => $title_li,
			'number'       => $number,
			'offset'       => $offset,
			'meta_key'     => $meta_key,
			'meta_value'   => $meta_value,
			'show_date'    => $show_date,
			'date_format'  => $date_format,
			'echo'         => 0,
			'authors'      => $authors,
			'sort_column'  => $sort_column,
			'sort_order'   => $sort_order,
			'link_before'  => $link_before,
			'link_after'   => $link_after,
			'post_type'    => $post_type,
			'post_status'  => $post_status,
		];
		$list_pages = wp_list_pages( $page_list_args );

		$return .= $pagelist_unq_settings['powered_by'];
		if ( $list_pages ) {
			$return .= '<ul class="page-list siblings-page-list ' . $class . '">' . "\n" . $list_pages . "\n" . '</ul>';
		} else {
			$return .= '<!-- no pages to show -->';
		}
		return $return;
	}
	add_shortcode( 'siblings', 'siblings_unqprfx_shortcode' );
}

if ( !function_exists( 'pagelist_unqprfx_dev_shortcode' ) ) {
	function pagelist_unqprfx_dev_shortcode( $atts, $level = 0 ) {
		global $post, $pagelist_unqprfx_settings;
		$level = intval( $level );
		$level_atts = shortcode_atts( [
			'show_image'           => 0,
			'show_first_image'     => 0,
			'show_title'           => 1,
			'show_content'         => 0,
			'more_tag'             => 1,
			'limit_content'        => 250,
			'image_width'          => '150',
			'image_height'         => '150',
			'child_of'             => '',
			'sort_order'           => 'ASC',
			'sort_column'          => 'menu_order, post_title',
			'hierarchical'         => 0,
			'exclude'              => '0',
			'include'              => '0',
			'meta_key'             => '',
			'meta_value'           => '',
			'authors'              => '',
			'parent'               => 0,
			'exclude_tree'         => '',
			'number'               => '',
			'offset'               => 0,
			'post_type'            => 'page',
			'post_status'          => 'publish',
			'class'                => '',
			'strip_tags'           => 1,
			'strip_shortcodes'     => 1,
			'show_child_count'     => '',
			'child_count_template' => 'Subpages: %child_count%',
			'show_meta_key'        => 1,
			'meta_template'        => '%meta%',
			'recurse'              => 1,
			'show_details'         => 1,
			'show_password'        => 1,
		], $atts );

		extract( $level_atts );

		if ( '' == $child_of ) { // show subpages if child_of is empty
			$child_of = $post->ID;
		}

		$page_list_dev_args = [
			'show_image'           => $show_image,
			'show_first_image'     => $show_first_image,
			'show_title'           => $show_title,
			'show_content'         => $show_content,
			'more_tag'             => $more_tag,
			'limit_content'        => $limit_content,
			'image_width'          => $image_width,
			'image_height'         => $image_height,
			'sort_order'           => $sort_order,
			'sort_column'          => $sort_column,
			'hierarchical'         => $hierarchical,
			'exclude'              => pagelist_unqprfx_norm_params( $exclude ),
			'include'              => $include,
			'meta_key'             => $meta_key,
			'meta_value'           => $meta_value,
			'authors'              => $authors,
			'child_of'             => pagelist_unqprfx_norm_params( $child_of ),
			'parent'               => pagelist_unqprfx_norm_params( $parent ),
			'exclude_tree'         => $exclude_tree,
			'number'               => '', // $number - own counter
			'offset'               => 0, // $offset - own offset
			'post_type'            => $post_type,
			'post_status'          => $post_status,
			'class'                => $class,
			'strip_tags'           => $strip_tags,
			'strip_shortcodes'     => $strip_shortcodes,
			'show_child_count'     => $show_child_count,
			'child_count_template' => $child_count_template,
			'show_meta_key'        => $show_meta_key,
			'meta_template'        => $meta_template,
		];
		$page_list_dev_args_all = [
			'show_image'           => $show_image,
			'show_first_image'     => $show_first_image,
			'show_title'           => $show_title,
			'show_content'         => $show_content,
			'more_tag'             => $more_tag,
			'limit_content'        => $limit_content,
			'image_width'          => $image_width,
			'image_height'         => $image_height,
			'sort_order'           => $sort_order,
			'sort_column'          => $sort_column,
			'hierarchical'         => $hierarchical,
			'exclude'              => pagelist_unqprfx_norm_params( $exclude ),
			'include'              => $include,
			'meta_key'             => $meta_key,
			'meta_value'           => $meta_value,
			'authors'              => $authors,
			'child_of'             => 0, // for showing all pages
			'parent'               => pagelist_unqprfx_norm_params( $parent ),
			'exclude_tree'         => $exclude_tree,
			'number'               => '', // $number - own counter
			'offset'               => 0, // $offset - own offset
			'post_type'            => $post_type,
			'post_status'          => $post_status,
			'class'                => $class,
			'strip_tags'           => $strip_tags,
			'strip_shortcodes'     => $strip_shortcodes,
			'show_child_count'     => $show_child_count,
			'child_count_template' => $child_count_template,
			'show_meta_key'        => $show_meta_key,
			'meta_template'        => $meta_template,
		];
		$list_pages = get_pages( $page_list_dev_args );
		if ( count( $list_pages ) == 0 ) { // if there is no subpages
			$list_pages = get_pages( $page_list_dev_args_all ); // we are showing all pages
		}
		$list_pages_html = '';
		$count = 0;
		$offset_count = 0;
		if ( false !== $list_pages && count( $list_pages ) > 0 ) {
			foreach ( $list_pages as $page ) {
				$count++;
				$offset_count++;
				if ( !empty( $offset ) && is_numeric( $offset ) && $offset_count <= $offset ) {
					$count = 0; // number counter to zero if offset is not finished
				}
				if (  ( !empty( $offset ) && is_numeric( $offset ) && $offset_count > $offset ) || ( empty( $offset ) ) || ( !empty( $offset ) && !is_numeric( $offset ) ) ) {
					if (  ( !empty( $number ) && is_numeric( $number ) && $count <= $number ) || ( empty( $number ) ) || ( !empty( $number ) && !is_numeric( $number ) ) ) {
						$link = get_permalink( $page->ID );
						$list_pages_html .= '<div class="page-list-dev-item">';
						if ( 1 == $show_image ) {
							if ( get_the_post_thumbnail( $page->ID ) ) { // if there is a featured image
								$list_pages_html .= '<div class="page-list-dev-image"><a href="' . $link . '" title="' . esc_attr( $page->post_title ) . '">';
								//$list_pages_html .= get_the_post_thumbnail($page->ID, array($image_width,$image_height)); // doesn't work good with image size

								$image = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ), [$image_width, $image_height] ); // get featured img; 'large'
								$img_url = $image[0]; // get the src of the featured image
								$list_pages_html .= '<img src="' . $img_url . '" width="' . $image_width . '" alt="' . esc_attr( $page->post_title ) . '" />'; // not using height="'.$image_height.'" because images could be not square shaped and they will be stretched

								$list_pages_html .= '</a></div> ';
							} else {
								if ( 1 == $show_first_image ) {
									$img_scr = pagelist_unqprfx_get_first_image( $page->post_content );
									if ( !empty( $img_scr ) ) {
										$list_pages_html .= '<div class="page-list-dev-image"><a href="' . $link . '" title="' . esc_attr( $page->post_title ) . '">';
										$list_pages_html .= '<img src="' . $img_scr . '" width="' . $image_width . '" alt="' . esc_attr( $page->post_title ) . '" />'; // not using height="'.$image_height.'" because images could be not square shaped and they will be stretched
										$list_pages_html .= '</a></div> ';
									}
								}
							}
						}

						if ( 1 == $show_title ) {
							$list_pages_html .= '<h5 class="page-list-dev-title"><a href="' . $link . '" title="' . esc_attr( $page->post_title ) . '">' . $page->post_title . '</a></h5>';
						}
						if ( 1 == $show_details ) {
							$list_pages_html .= '<div class="page-list-details>';
							$list_pages_html .= '<span class="slug">' . $page->post_name . '</span>';
							$password = $page->post_password;
							if ( $password ) {
								$password = 1 == $show_password ? $password : 'password protected';
								$list_pages_html .= ' <span class="password">{' . $password . '}</span>';
							}
							$status = $page->post_status;
							if ( 'publish' != $status ) {
								$list_pages_html .= ' <span class="status">[' . $page->post_status . ']</span>';
							}
							$date = date( 'Y-m-d', strtotime( $page->post_date ) );
							$list_pages_html .= ' <span class="date">(' . $date . ')</span>';
							$list_pages_html .= '</div>';
						}
						if ( 1 == $show_content ) {
							//$content = apply_filters('the_content', $page->post_content);
							//$content = str_replace(']]>', ']]&gt;', $content); // both used in default the_content() function

							if ( !empty( $page->post_excerpt ) ) {
								$text_content = $page->post_excerpt;
							} else {
								$text_content = $page->post_content;
							}

							if ( post_password_required( $page ) ) {
								$content = '<!-- password protected -->';
							} else {
								$content = pagelist_unqprfx_parse_content( $text_content, $limit_content, $strip_tags, $strip_shortcodes, $more_tag );
								$content = do_shortcode( $content );

								if ( 0 == $show_title ) { // make content as a link if there is no title
									$content = '<a href="' . $link . '">' . $content . '</a>';
								}
							}

							$list_pages_html .= '<div class="page-list-dev-item-content">' . $content . '</div>';

						}
						if ( 1 == $show_child_count ) {
							$count_subpages = count( get_pages( 'child_of=' . $page->ID ) );
							if ( $count_subpages > 0 ) { // hide empty
								$child_count_pos = strpos( $child_count_template, '%child_count%' ); // check if we have %child_count% marker in template
								if ( false === $child_count_pos ) { // %child_count% not found in template
									$child_count_template_html = $child_count_template . ' ' . $count_subpages;
									$list_pages_html .= '<div class="page-list-dev-child-count">' . $child_count_template_html . '</div>';
								} else { // %child_count% found in template
									$child_count_template_html = str_replace( '%child_count%', $count_subpages, $child_count_template );
									$list_pages_html .= '<div class="page-list-dev-child-count">' . $child_count_template_html . '</div>';
								}
							}
						}
						if ( $recurse ) {
							$recurse_atts = $level_atts;
							$recurse_atts['parent'] = $page->ID;
							$list_pages_html .= pagelist_unqprfx_dev_shortcode( $recurse_atts, $level + 1 );
						}
						if ( '' != $show_meta_key ) {
							$post_meta = get_post_meta( $page->ID, $show_meta_key, true );
							if ( !empty( $post_meta ) ) { // hide empty
								$meta_pos = strpos( $meta_template, '%meta%' ); // check if we have %meta% marker in template
								if ( false === $meta_pos ) { // %meta% not found in template
									$meta_template_html = $meta_template . ' ' . $post_meta;
									$list_pages_html .= '<div class="page-list-dev-meta">' . $meta_template_html . '</div>';
								} else { // %meta% found in template
									$meta_template_html = str_replace( '%meta%', $post_meta, $meta_template );
									$list_pages_html .= '<div class="page-list-dev-meta">' . $meta_template_html . '</div>';
								}
							}
						}
						$list_pages_html .= '</div>' . "\n";
					}
				}
			}
		}
		$return .= $pagelist_unqprfx_settings['powered_by'];
		if ( $list_pages_html ) {
			$class .= $level ? 'page-list-sub' : 'page list page-list-dev';
			if ( !$level ) {
				$return .= <<<SUBSTYLE
				<style>
					.page-list-sub {
						padding-left: 2em;
						border-left: 1px solid lightgray;
					}
					h5.page-list-dev-title {
						margin-bottom: 0;
					}
					.page-list-dev-item .status,
					.page-list-dev-item .password {
						font-weight: 900;
					}
				</style>
				SUBSTYLE;
			}
			$return .= '<div class="' . $class . '">' . "\n" . $list_pages_html . "\n" . '</div>';
		} else {
			$return .= '<!-- no pages to show -->'; // this line will not work, because we show all pages if there is no pages to show
		}
		return $return;
	}
	add_shortcode( 'pagelist_dev', 'pagelist_unqprfx_dev_shortcode' );
	add_shortcode( 'pagelistdev', 'pagelist_unqprfx_dev_shortcode' );
}

if ( !function_exists( 'pagelist_unqprfx_ext_shortcode' ) ) {
	function pagelist_unqprfx_ext_shortcode( $atts ) {
		global $post, $pagelist_unq_settings;
		$return = '';
		extract( shortcode_atts( [
			'show_image'           => 1,
			'show_first_image'     => 0,
			'show_title'           => 1,
			'show_content'         => 1,
			'more_tag'             => 1,
			'limit_content'        => 250,
			'image_width'          => '150',
			'image_height'         => '150',
			'child_of'             => '',
			'sort_order'           => 'ASC',
			'sort_column'          => 'menu_order, post_title',
			'hierarchical'         => 1,
			'exclude'              => '0',
			'include'              => '0',
			'meta_key'             => '',
			'meta_value'           => '',
			'authors'              => '',
			'parent'               => -1,
			'exclude_tree'         => '',
			'number'               => '',
			'offset'               => 0,
			'post_type'            => 'page',
			'post_status'          => 'publish',
			'class'                => '',
			'strip_tags'           => 1,
			'strip_shortcodes'     => 1,
			'show_child_count'     => 0,
			'child_count_template' => 'Subpages: %child_count%',
			'show_meta_key'        => '',
			'meta_template'        => '%meta%',
		], $atts ) );

		if ( '' == $child_of ) { // show subpages if child_of is empty
			$child_of = $post->ID;
		}

		$page_list_ext_args = [
			'show_image'           => $show_image,
			'show_first_image'     => $show_first_image,
			'show_title'           => $show_title,
			'show_content'         => $show_content,
			'more_tag'             => $more_tag,
			'limit_content'        => $limit_content,
			'image_width'          => $image_width,
			'image_height'         => $image_height,
			'sort_order'           => $sort_order,
			'sort_column'          => $sort_column,
			'hierarchical'         => $hierarchical,
			'exclude'              => pagelist_unqprfx_norm_params( $exclude ),
			'include'              => $include,
			'meta_key'             => $meta_key,
			'meta_value'           => $meta_value,
			'authors'              => $authors,
			'child_of'             => pagelist_unqprfx_norm_params( $child_of ),
			'parent'               => pagelist_unqprfx_norm_params( $parent ),
			'exclude_tree'         => $exclude_tree,
			'number'               => '', // $number - own counter
			'offset'               => 0, // $offset - own offset
			'post_type'            => $post_type,
			'post_status'          => $post_status,
			'class'                => $class,
			'strip_tags'           => $strip_tags,
			'strip_shortcodes'     => $strip_shortcodes,
			'show_child_count'     => $show_child_count,
			'child_count_template' => $child_count_template,
			'show_meta_key'        => $show_meta_key,
			'meta_template'        => $meta_template,
		];
		$page_list_ext_args_all = [
			'show_image'           => $show_image,
			'show_first_image'     => $show_first_image,
			'show_title'           => $show_title,
			'show_content'         => $show_content,
			'more_tag'             => $more_tag,
			'limit_content'        => $limit_content,
			'image_width'          => $image_width,
			'image_height'         => $image_height,
			'sort_order'           => $sort_order,
			'sort_column'          => $sort_column,
			'hierarchical'         => $hierarchical,
			'exclude'              => pagelist_unqprfx_norm_params( $exclude ),
			'include'              => $include,
			'meta_key'             => $meta_key,
			'meta_value'           => $meta_value,
			'authors'              => $authors,
			'child_of'             => 0, // for showing all pages
			'parent'               => pagelist_unqprfx_norm_params( $parent ),
			'exclude_tree'         => $exclude_tree,
			'number'               => '', // $number - own counter
			'offset'               => 0, // $offset - own offset
			'post_type'            => $post_type,
			'post_status'          => $post_status,
			'class'                => $class,
			'strip_tags'           => $strip_tags,
			'strip_shortcodes'     => $strip_shortcodes,
			'show_child_count'     => $show_child_count,
			'child_count_template' => $child_count_template,
			'show_meta_key'        => $show_meta_key,
			'meta_template'        => $meta_template,
		];
		$list_pages = get_pages( $page_list_ext_args );
		if ( count( $list_pages ) == 0 ) { // if there is no subpages
			$list_pages = get_pages( $page_list_ext_args_all ); // we are showing all pages
		}
		$list_pages_html = '';
		$count = 0;
		$offset_count = 0;
		if ( false !== $list_pages && count( $list_pages ) > 0 ) {
			foreach ( $list_pages as $page ) {
				$count++;
				$offset_count++;
				if ( !empty( $offset ) && is_numeric( $offset ) && $offset_count <= $offset ) {
					$count = 0; // number counter to zero if offset is not finished
				}
				if (  ( !empty( $offset ) && is_numeric( $offset ) && $offset_count > $offset ) || ( empty( $offset ) ) || ( !empty( $offset ) && !is_numeric( $offset ) ) ) {
					if (  ( !empty( $number ) && is_numeric( $number ) && $count <= $number ) || ( empty( $number ) ) || ( !empty( $number ) && !is_numeric( $number ) ) ) {
						$link = get_permalink( $page->ID );
						$list_pages_html .= '<div class="page-list-ext-item">';
						if ( 1 == $show_image ) {
							if ( get_the_post_thumbnail( $page->ID ) ) { // if there is a featured image
								$list_pages_html .= '<div class="page-list-ext-image"><a href="' . $link . '" title="' . esc_attr( $page->post_title ) . '">';
								//$list_pages_html .= get_the_post_thumbnail($page->ID, array($image_width,$image_height)); // doesn't work good with image size

								$image = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ), [$image_width, $image_height] ); // get featured img; 'large'
								$img_url = $image[0]; // get the src of the featured image
								$list_pages_html .= '<img src="' . $img_url . '" width="' . $image_width . '" alt="' . esc_attr( $page->post_title ) . '" />'; // not using height="'.$image_height.'" because images could be not square shaped and they will be stretched

								$list_pages_html .= '</a></div> ';
							} else {
								if ( 1 == $show_first_image ) {
									$img_scr = pagelist_unqprfx_get_first_image( $page->post_content );
									if ( !empty( $img_scr ) ) {
										$list_pages_html .= '<div class="page-list-ext-image"><a href="' . $link . '" title="' . esc_attr( $page->post_title ) . '">';
										$list_pages_html .= '<img src="' . $img_scr . '" width="' . $image_width . '" alt="' . esc_attr( $page->post_title ) . '" />'; // not using height="'.$image_height.'" because images could be not square shaped and they will be stretched
										$list_pages_html .= '</a></div> ';
									}
								}
							}
						}

						if ( 1 == $show_title ) {
							$list_pages_html .= '<h3 class="page-list-ext-title"><a href="' . $link . '" title="' . esc_attr( $page->post_title ) . '">' . $page->post_title . '</a></h3>';
						}
						if ( 1 == $show_content ) {
							//$content = apply_filters('the_content', $page->post_content);
							//$content = str_replace(']]>', ']]&gt;', $content); // both used in default the_content() function

							if ( !empty( $page->post_excerpt ) ) {
								$text_content = $page->post_excerpt;
							} else {
								$text_content = $page->post_content;
							}

							if ( post_password_required( $page ) ) {
								$content = '<!-- password protected -->';
							} else {
								$content = pagelist_unqprfx_parse_content( $text_content, $limit_content, $strip_tags, $strip_shortcodes, $more_tag );
								$content = do_shortcode( $content );

								if ( 0 == $show_title ) { // make content as a link if there is no title
									$content = '<a href="' . $link . '">' . $content . '</a>';
								}
							}

							$list_pages_html .= '<div class="page-list-ext-item-content">' . $content . '</div>';

						}
						if ( 1 == $show_child_count ) {
							$count_subpages = count( get_pages( 'child_of=' . $page->ID ) );
							if ( $count_subpages > 0 ) { // hide empty
								$child_count_pos = strpos( $child_count_template, '%child_count%' ); // check if we have %child_count% marker in template
								if ( false === $child_count_pos ) { // %child_count% not found in template
									$child_count_template_html = $child_count_template . ' ' . $count_subpages;
									$list_pages_html .= '<div class="page-list-ext-child-count">' . $child_count_template_html . '</div>';
								} else { // %child_count% found in template
									$child_count_template_html = str_replace( '%child_count%', $count_subpages, $child_count_template );
									$list_pages_html .= '<div class="page-list-ext-child-count">' . $child_count_template_html . '</div>';
								}
							}
						}
						if ( '' != $show_meta_key ) {
							$post_meta = get_post_meta( $page->ID, $show_meta_key, true );
							if ( !empty( $post_meta ) ) { // hide empty
								$meta_pos = strpos( $meta_template, '%meta%' ); // check if we have %meta% marker in template
								if ( false === $meta_pos ) { // %meta% not found in template
									$meta_template_html = $meta_template . ' ' . $post_meta;
									$list_pages_html .= '<div class="page-list-ext-meta">' . $meta_template_html . '</div>';
								} else { // %meta% found in template
									$meta_template_html = str_replace( '%meta%', $post_meta, $meta_template );
									$list_pages_html .= '<div class="page-list-ext-meta">' . $meta_template_html . '</div>';
								}
							}
						}
						$list_pages_html .= '</div>' . "\n";
					}
				}
			}
		}
		$return .= $pagelist_unq_settings['powered_by'];
		if ( $list_pages_html ) {
			$return .= '<div class="page-list page-list-ext ' . $class . '">' . "\n" . $list_pages_html . "\n" . '</div>';
		} else {
			$return .= '<!-- no pages to show -->'; // this line will not work, because we show all pages if there is no pages to show
		}
		return $return;
	}
	add_shortcode( 'pagelist_ext', 'pagelist_unqprfx_ext_shortcode' );
	add_shortcode( 'pagelistext', 'pagelist_unqprfx_ext_shortcode' );
}

if ( !function_exists( 'pagelist_unqprfx_norm_params' ) ) {
	function pagelist_unqprfx_norm_params( $str ) {
		global $post;
		$new_str = $str;
		$new_str = str_replace( 'this', $post->ID, $new_str ); // exclude this page
		$new_str = str_replace( 'current', $post->ID, $new_str ); // exclude current page
		$new_str = str_replace( 'curent', $post->ID, $new_str ); // exclude curent page with mistake
		$new_str = str_replace( 'parent', $post->post_parent, $new_str ); // exclude parent page
		return $new_str;
	}
}

if ( !function_exists( 'pagelist_unqprfx_parse_content' ) ) {
	function pagelist_unqprfx_parse_content( $content, $limit_content = 250, $strip_tags = 1, $strip_shortcodes = 1, $more_tag = 1 ) {

		$more_tag_found = 0;

		if ( $more_tag ) { // "more_tag" have higher priority than "limit_content"
			if ( preg_match( '/<!--more(.*?)?-->/', $content, $matches ) ) {
				$more_tag_found = 1;
				$more_tag = $matches[0];
				$content_temp = explode( $matches[0], $content );
				$content_temp = $content_temp[0];
				$content_before_more_tag_length = strlen( $content_temp );
				$content = substr_replace( $content, '###more###', $content_before_more_tag_length, 0 );
			}
		}

		// replace php and comments tags so they do not get stripped
		//$content = preg_replace("@<\?@", "#?#", $content);
		//$content = preg_replace("@<!--@", "#!--#", $content); // save html comments
		// strip tags normally
		//$content = strip_tags($content);
		if ( $strip_tags ) {
			$content = str_replace( '</', ' </', $content ); // <p>line1</p><p>line2</p> - adding space between lines
			$content = strip_tags( $content ); // ,'<p>'
		}
		// return php and comments tags to their origial form
		//$content = preg_replace("@#\?#@", "<?", $content);
		//$content = preg_replace("@#!--#@", "<!--", $content);

		if ( $strip_shortcodes ) {
			$content = strip_shortcodes( $content );
		}

		if ( $more_tag && $more_tag_found ) { // "more_tag" have higher priority than "limit_content"
			$fake_more_pos = mb_strpos( $content, '###more###', 0, 'UTF-8' );
			if ( false === $fake_more_pos ) {
				// substring not found in string and this is strange :)
			} else {
				$content = mb_substr( $content, 0, $fake_more_pos, 'UTF-8' );
			}
		} else {
			if ( strlen( $content ) > $limit_content ) { // limiting content
				$pos = strpos( $content, ' ', $limit_content ); // find first space position
				if ( false !== $pos ) {
					$first_space_pos = $pos;
				} else {
					$first_space_pos = $limit_content;
				}
				$content = mb_substr( $content, 0, $first_space_pos, 'UTF-8' ) . '...';
			}
		}

		$output = force_balance_tags( $content );
		return $output;
	}
}

if ( !function_exists( 'pagelist_unqprfx_get_first_image' ) ) {
	function pagelist_unqprfx_get_first_image( $content = '' ) {
		$first_img = '';
		$matchCount = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches );
		if ( 0 !== $matchCount ) { // if we found first image
			$first_img = $matches[1][0];
		}
		return $first_img;
	}
}

if ( !function_exists( 'pagelist_unqprfx_plugin_meta' ) ) {
	function pagelist_unqprfx_plugin_meta( $links, $file ) { // add links to plugin meta row
		if ( plugin_basename( __FILE__ ) == $file ) {
			$row_meta = [
				'support' => '<a href="http://web-profile.net/wordpress/plugins/page-list/" target="_blank">' . __( 'Page-list', 'page-list' ) . '</a>',
				'donate'  => '<a href="http://web-profile.net/donate/" target="_blank">' . __( 'Donate', 'page-list' ) . '</a>',
				'pro'     => '<a href="https://1.envato.market/KdRNz" target="_blank" title="Advanced iFrame Pro">' . __( 'Advanced iFrame Pro', 'page-list' ) . '</a>',
			];
			$links = array_merge( $links, $row_meta );
		}
		return (array) $links;
	}
	add_filter( 'plugin_row_meta', 'pagelist_unqprfx_plugin_meta', 10, 2 );
}
