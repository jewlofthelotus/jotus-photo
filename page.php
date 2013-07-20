<?php get_header() ?>

<div class="wrapper main">
    <div class="content">
<?php the_post() ?>
            <div class="post <?php jotus_post_class() ?>">
                <h2 class="post-title"><?php the_title(); ?></h2>

                <div class="post-content">
<?php the_content() ?>
                </div>

                <div class="post-meta">
<?php if (('open' == $post-> comment_status)) : // Comments open ?>
                <?php printf(__('<a class="comment-link" href="#respond" title="Post a comment">Post a comment</a>', 'jotus')) ?>
<?php elseif (!('open' == $post-> comment_status)) : // Comments are closed ?>
                <?php _e('Comments are currently closed.') ?>
<?php endif; ?>

<?php edit_post_link(__('Edit', 'jotus'),'<span class="edit-link">','</span>') ?>
                </div>
            </div>
        </div>

    <?php comments_template('', true); ?>
</div>

<?php get_footer() ?>
