<?php

// Footer Widgets
function jotus_widgets_init() {
    register_sidebar( array(
        'name' => 'Footer Widgets',
        'id' => 'footer_widgets',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h4>',
        'after_title' => '</h4>',
    ) );

    register_sidebar( array(
        'name' => '404 Widgets',
        'id' => 'four04_widgets',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h4>',
        'after_title' => '</h4>',
    ) );
}
add_action( 'widgets_init', 'jotus_widgets_init' );

// Custom Excerpt Lengths
function excerpt($limit) {
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).'...';
    } else {
        $excerpt = implode(" ",$excerpt);
    }
    $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
    return $excerpt;
}
remove_filter('the_excerpt', 'wpautop'); // Also, remove p tags

// Body Classes for Styling
function jotus_body_class( $print = true ) {
	global $wp_query;

	$c = array();

	is_home()       ? $c[] = 'home'       : null;
    is_single()     ? $c[] = 'single'     : null;
	is_page()       ? $c[] = 'page'       : null;
    is_category()   ? $c[] = 'category'   : null;
	is_tag()        ? $c[] = 'tag'        : null;
	is_archive()    ? $c[] = 'archive'    : null;
	is_search()     ? $c[] = 'search'     : null;
	is_404()        ? $c[] = 'four04'     : null;

	// Separates classes with a single space, collates classes for BODY
	$c = join(' ', apply_filters('body_class',  $c));

	return $print ? print($c) : $c;
}

// Post Classes for Syling
function jotus_post_class( $print = true ) {
	global $post, $jotus_post_index;

	$c = array("p$jotus_post_index");
	$c = join(' ', apply_filters('post_class', $c));

    ++$jotus_post_index;

	return $print ? print($c) : $c;
}
$jotus_post_index = 1;

// Generate a CSS background from image meta
function the_post_background() {
    global $post;

    // if an image_url is set in the post meta, use it
    $image_url = get_post_meta( $post->ID, 'image_url', true );
    $image_pos = get_post_meta( $post->ID, 'image_pos', true ) ?: 'center center';

    if ( $image_url == '' ) {
        // find first post attachment or image w/in content
        $image_url = first_image_url();

        if ( $image_url == '' ) {
            $image_url = get_bloginfo ( 'stylesheet_directory' ) . '/img/no-attachment.gif';
        }
    }

    echo "background: url($image_url) $image_pos repeat;";
}

// Setup image meta on save
function image_setup( $postid ) {
    global $post;
    $post = get_post( $postid );

    $image_url = get_post_meta( $post->ID, 'image_url', true );
    $image_pos = get_post_meta( $post->ID, 'image_pos', true );

    if ( $image_url == '' ) {
        // find first post attachment or image w/in content
        $url = first_image_url( $postid );
        add_post_meta( $post->ID, 'image_url', $url );
    }

    if ( $image_pos == '' ) {
        add_post_meta( $post->ID, 'image_pos', 'center center' );
    }
}
add_action('publish_post', 'image_setup');
add_action('publish_page', 'image_setup');

// Find the first post image if one isn't set in meta
function first_image_url( $postid = null ) {
    global $post;

    if ( $postid ) {
        $post = get_post( $postid );
    }

    $image_size = is_search() ? 'medium' : 'large';

    // find an image attachment
    if ( $post_image = get_children( array(
        'post_parent' => get_the_ID(),
        'post_type' => 'attachment',
        'numberposts' => 1,
        'post_mime_type' => 'image'
       ) ) ) {
        foreach ( $post_image as $image ) {
            $url = wp_get_attachment_image_src( $image->ID, $image_size );
            $url = $url[0];
        }

    // find an image in the content
    } else {
        if ( !preg_match( '/<img ([^>]*)src=(\"|\')(.+?)(\2)([^>\/]*)\/*>/', $post->post_content, $matches ) ) {
            return '';
        }

        $url = $matches[3];
        if ( !$url = preg_replace( '/\?w\=[0-9]+/','', $url ) )
            return '';

        $url = clean_url( $url, 'raw' );
    }

    return $url;
}

?>
