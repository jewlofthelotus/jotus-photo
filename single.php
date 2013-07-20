<?php get_header() ?>

<div class="wrapper main">
    <div class="content">
<?php the_post(); ?>

		<div class="post <?php jotl_post_class(); ?>">
            <div class="post-date">
                <span class="day"><?php the_time('d'); ?></span>
                <span class="month"><?php the_time('M'); ?></span>
            </div>

			<h2 class="post-title"><?php the_title(); ?></h2>

			<div class="post-content">
<?php the_content(); ?>
			</div>

			<div class="post-meta">
				<?php printf(__('Posted %1$s at %2$s in %3$s%4$s. <a href="%5$s" rel="alternate" type="application/rss+xml">Comments RSS</a>.', 'sandbox'),
					the_date('', '', '', false),
					get_the_time(),
					get_the_category_list(', '),
					get_the_tag_list(' '.__('and tagged').' ', ', ', ''),
					get_post_comments_feed_link() ) ?>

<?php if (('open' == $post-> comment_status)) : // Comments open ?>
				<?php printf(__('<a class="comment-link" href="#respond" title="Post a comment">Post a comment</a>.', 'sandbox')) ?>
<?php elseif (!('open' == $post-> comment_status)) : // Comments are closed ?>
				<?php _e('Comments are currently closed.') ?>
<?php endif; ?>

<?php edit_post_link(__('Edit', 'sandbox'), "<span class=\"edit-link\">", "</span>"); ?>
			</div>
		</div><!-- .post -->
	</div>

	<div class="nav-below">
		<div class="nav-previous"><?php previous_post_link('%link', '&laquo; %title', true) ?></div>
		<div class="nav-next"><?php next_post_link('%link', '%title &raquo;', true) ?></div>
	</div>

	<?php comments_template('', true); ?>
</div>

<?php get_footer() ?>
