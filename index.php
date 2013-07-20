<?php get_header() ?>

<div class="wrapper main">
    <div class="content">
        <div class="post-list">
<?php while ( have_posts() ) : the_post() ?>
            <section class="post <?php jotus_post_class() ?>">
                <div class="post-date">
                    <span class="day"><?php the_time('d'); ?></span>
                    <span class="month"><?php the_time('M'); ?></span>
                </div>

                <a class="post-link" href="<?php the_permalink() ?>"></a>

                <span class="post-image" style="background:url('<?php the_post_image_url(is_search() ? 'medium' : 'large'); ?>') center center repeat">&nbsp;</span>

                <div class="post-content">
                    <h3><?php the_title() ?></h3>
                    <p><?php echo is_search() ? excerpt(35) : excerpt(55); ?></p>
                    <?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'jotus' ) . '&after=</div>') ?>
                </div>

<?php comments_template() ?>
            </section>
<?php endwhile ?>
        </div>
    </div>

    <nav class="nav-below">
        <div class="nav-previous"><?php next_posts_link(__('&laquo; Older posts', 'jotus')) ?></div>
        <div class="nav-next"><?php previous_posts_link(__('Newer posts &raquo;', 'jotus')) ?></div>
    </nav>
</div>

<?php get_footer() ?>
