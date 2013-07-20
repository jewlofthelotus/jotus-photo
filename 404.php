<?php get_header() ?>

<div class="wrapper main">
    <div class="content">
        <div class="post error404">
            <h2 class="post-title"><?php _e('Page Not Found', 'jotus') ?></h2>

            <div class="post-content">
                <p><?php _e('Sorry! The page you\'re looking for is lost somewhere out in the abyss!', 'jotus') ?></p>
                <p><?php _e('Maybe try browsing through some tags or trying a keyword search below.', 'jotus') ?></p>
            </div>

            <div class="footer_widgets">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('404 Widgets') ) : else : endif; ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer() ?>
