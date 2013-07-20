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

// Post Attachment image function. Image URL for CSS Background.
function the_post_image_url($size='medium') {
	global $post;
	$linkedimgurl = get_post_meta ($post->ID, 'image_url', true);

	if ( $linkedimgurl ) {

		echo $linkedimgurl;

	} elseif ( $images = get_children(array(
		'post_parent' => get_the_ID(),
		'post_type' => 'attachment',
		'numberposts' => 1,
		'post_mime_type' => 'image',)))
	{
		foreach( $images as $image ) {
			$attachmenturl=wp_get_attachment_image_src($image->ID, $size);
			$attachmenturl=$attachmenturl[0];
			$attachmentimage=wp_get_attachment_image( $image->ID, $size );

			echo ''.$attachmenturl.'';
		}

	} else {
		echo '' . get_bloginfo ( 'stylesheet_directory' ) . '/img/no-attachment.gif';
	}
}

// Post Attachment image function. Direct link to file.
function the_post_image($size='thumbnail') {

    global $post;
    $linkedimgtag = get_post_meta ($post->ID, 'image_tag', true);

    if ( $images = get_children(array(
        'post_parent' => get_the_ID(),
        'post_type' => 'attachment',
        'numberposts' => 1,
        'post_mime_type' => 'image',)))
        {
        foreach( $images as $image ) {
            $attachmenturl=wp_get_attachment_url($image->ID);
            $attachmentimage=wp_get_attachment_image( $image->ID, $size );

            echo ''.$attachmentimage.'';
        }

    } elseif ( $linkedimgtag ) {

        echo $linkedimgtag;

    } elseif ( $linkedimgtag && $images = get_children(array(
        'post_parent' => get_the_ID(),
        'post_type' => 'attachment',
        'numberposts' => 1,
        'post_mime_type' => 'image',)))
        {
        foreach( $images as $image ) {
            $attachmenturl=wp_get_attachment_url($image->ID);
            $attachmentimage=wp_get_attachment_image( $image->ID, $size );

            echo ''.$attachmentimage.'';
        }

    } else {
        echo '<img src="' . get_bloginfo ( 'stylesheet_directory' ) . '/img/no-attachment-large.gif" />';
    }
}

// Setup Images for Attachment functions
function image_setup($postid) {
	global $post;
	$post = get_post($postid);

	// get url
	if ( !preg_match('/<img ([^>]*)src=(\"|\')(.+?)(\2)([^>\/]*)\/*>/', $post->post_content, $matches) ) {
		return false;
	}

	// url setup
	$post->image_url = $matches[3];
	if ( !$post->image_url = preg_replace('/\?w\=[0-9]+/','', $post->image_url) )
		return false;

	$post->image_url = clean_url( $post->image_url, 'raw' );

	delete_post_meta($post->ID, 'image_url');
	delete_post_meta($post->ID, 'image_tag');

	add_post_meta($post->ID, 'image_url', $post->image_url);
	add_post_meta($post->ID, 'image_tag', '<img src="'.$post->image_url.'" />');
}
add_action('publish_post', 'image_setup');
add_action('publish_page', 'image_setup');

?>
