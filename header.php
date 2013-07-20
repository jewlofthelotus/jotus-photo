<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php bloginfo('name'); if ( is_404() ) : _e(' &raquo; ', 'sandbox'); _e('Not Found', 'sandbox'); elseif ( is_home() ) : _e(' &raquo; ', 'sandbox'); bloginfo('description'); else : wp_title(); endif; ?></title>
    <meta name="description" content="<?php bloginfo('description') ?>" />

    <link href='http://fonts.googleapis.com/css?family=Lily+Script+One|Open+Sans+Condensed:700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
    <link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('RSS feed', 'sandbox'); ?>" />
    <link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('Comments RSS feed', 'sandbox'); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />

    <?php wp_head() ?>
</head>

<body class="<?php jotl_body_class() ?>">
    <header>
        <div class="wrapper">
            <h1><a href="<?php echo get_option('home') ?>/">Jewl Of The Lotus</a></h1>

            <nav>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/about">About</a></li>
                    <li><a href="/category/photography">Photography</a></li>
                    <li><a href="/category/cooking">Food</a></li>
                    <li><a href="/category/travel">Travel</a></li>
                    <li><a href="/category/portraits">Portraits</a></li>
                </ul>
            </nav>

            <h4><?php bloginfo('description') ?></h4>
        </div>
    </header>
