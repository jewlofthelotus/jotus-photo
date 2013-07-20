<?php

function jotl_widgets_init() {
    register_sidebar( array(
        'name' => 'Footer Widgets',
        'id' => 'footer_widgets',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h4>',
        'after_title' => '</h4>',
    ) );
}
add_action( 'widgets_init', 'jotl_widgets_init' );

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


function jotl_body_class( $print = true ) {
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

function jotl_post_class( $print = true ) {
	global $post, $sandbox_post_alt;

	$c = array("p$sandbox_post_alt");
	$c = join(' ', apply_filters('post_class', $c));

    ++$sandbox_post_alt;

	return $print ? print($c) : $c;
}

// Define the num val for 'alt' classes (in post DIV and comment LI)
$sandbox_post_alt = 1;

// Generates semantic classes for each comment LI element
function sandbox_comment_class( $print = true ) {
	global $comment, $post, $sandbox_comment_alt;

	// Collects the comment type (comment, trackback),
	$c = array($comment->comment_type);

	// Counts trackbacks (t[n]) or comments (c[n])
	if ($comment->comment_type == 'trackback') {
		$c[] = "t$sandbox_comment_alt";
	} else {
		$c[] = "c$sandbox_comment_alt";
	}

	// If the comment author has an id (registered), then print the log in name
	if ( $comment->user_id > 0 ) {
		$user = get_userdata($comment->user_id);

		// For all registered users, 'byuser'; to specificy the registered user, 'commentauthor+[log in name]'
		$c[] = "byuser comment-author-" . sanitize_title_with_dashes(strtolower($user->user_login));
		// For comment authors who are the author of the post
		if ( $comment->user_id === $post->post_author )
			$c[] = 'bypostauthor';
	}

	// If it's the other to the every, then add 'alt' class; collects time- and date-based classes
	sandbox_date_classes(mysql2date('U', $comment->comment_date), $c, 'c-');
	if ( ++$sandbox_comment_alt % 2 )
		$c[] = 'alt';

	// Separates classes with a single space, collates classes for comment LI
	$c = join(' ', apply_filters('comment_class', $c));

	// Tada again!
	return $print ? print($c) : $c;
}

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

//Setup Images for Attachment functions
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

// Post Attachment image function for Attachment Pages.
function the_attachment_image($size='large') {
	$attachmenturl=wp_get_attachment_url($image->ID);
	$attachmentimage=wp_get_attachment_image( $image->ID, $size );

	echo ''.$attachmentimage.'';
}

// Post Attachment image function for Attachment Pages.
function link_to_attachment($size='large') {
	if ( $attachs = get_children(array(
		'post_parent' => get_the_ID(),
		'post_type' => 'attachment',
		'numberposts' => 1,
		'post_mime_type' => 'image',)))
	{
		foreach( $attachs as $attach ) {
			$attachmentlink=get_attachment_link($attach->ID);

			echo '<a href="' . $attachmentlink . '">View EXIF Data</a>';
		}
	}
}

// Grab EXIF Data from Attachments http://www.walkernews.net/2009/04/13/turn-on-wordpress-feature-to-display-photo-exif-data-and-iptc-information/
function grab_exif_data() {
	$imgmeta = wp_get_attachment_metadata($id);

	/*
	// Convert the shutter speed retrieve from database to fraction DOES NOT WORK ON THE LIVE SERVER FOR SOME REASON >:-|
	if ((1 / $imgmeta['image_meta']['shutter_speed']) > 1) {
		if ((number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1)) == 1.3
		or number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 1.5
		or number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 1.6
		or number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 2.5) {
			$pshutter = "1/" . number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1, '.', '') . " second";
		} else {
			$pshutter = "1/" . number_format((1 / $imgmeta['image_meta']['shutter_speed']), 0, '.', '') . " second";
		}

	} else {
		$pshutter = $imgmeta['image_meta']['shutter_speed'] . " seconds";
	}*/

	// Start to display EXIF and IPTC data of digital photograph
	echo "<ul><li<span class=\"exif-title\">Date Taken:</span> " . date("d-M-Y H:i:s", $imgmeta['image_meta']['created_timestamp'])."</li>";
	echo "<li<span class=\"exif-title\">Copyright:</span> " . $imgmeta['image_meta']['copyright']."</li>";
	echo "<li<span class=\"exif-title\">Credit:</span> " . $imgmeta['image_meta']['credit']."</li>";
	echo "<li<span class=\"exif-title\">Title:</span> " . $imgmeta['image_meta']['title']."</li>";
	echo "<li<span class=\"exif-title\">Caption:</span> " . $imgmeta['image_meta']['caption']."</li>";
	echo "<li<span class=\"exif-title\">Camera:</span> " . $imgmeta['image_meta']['camera']."</li>";
	echo "<li<span class=\"exif-title\">Focal Length:</span> " . $imgmeta['image_meta']['focal_length']."mm</li>";
	echo "<li<span class=\"exif-title\">Aperture:</span> f/" . $imgmeta['image_meta']['aperture']."</li>";
	echo "<li<span class=\"exif-title\">ISO:</span> " . $imgmeta['image_meta']['iso']."</li>";
	// echo "<li<span class=\"exif-title\">Shutter Speed:</span> " . $pshutter . "</li></ul>";
}
// add_action('exif_data','grab_exif_data');

// Removes 'p' tags from excerpts.
remove_filter('the_excerpt', 'wpautop');

// Fixes Next and Previous ATTACHMENT links
function ps_previous_image_link( $f ) {
    $i = ps_adjacent_image_link( true );
	if ( $i ) {
		echo str_replace("%link", $i, $f);
	}
}

// Next ATTACHMENT link
function ps_next_image_link( $f ) {
    $i = ps_adjacent_image_link( false );
	if ( $i ) {
		echo str_replace("%link", $i, $f);
	}
}

// Previous ATTACHMENT link
function ps_adjacent_image_link($prev = true) {
    global $post;
    $post = get_post($post);
    $attachments = array_values(get_children(Array('post_parent' => $post->post_parent,
	      'post_type' => 'attachment',
	      'post_mime_type' => 'image',
	      'orderby' => 'menu_order ASC, ID ASC')));

    foreach ( $attachments as $k => $attachment ) {
        if ( $attachment->ID == $post->ID ) {
            break;
		}
	}

    $k = $prev ? $k - 1 : $k + 1;

    if ( isset($attachments[$k]) ) {
        return wp_get_attachment_link($attachments[$k]->ID, 'thumbnail', true);
	}
	else {
		return false;
	}
}

// Overides default FULL size images size
$GLOBALS['content_width'] = 800;

add_filter('the_content_rss', 'do_shortcode');

// Produces an avatar image with the hCard-compliant photo class
function commenter_link() {
	$commenter = get_comment_author_link();
	if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
		$commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
	} else {
		$commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
	}
	$avatar_email = get_comment_author_email();
	$avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, 50 ) );
	echo $avatar . ' <span class="fn n">' . $commenter . '</span>';
} // end commenter_link

?>
