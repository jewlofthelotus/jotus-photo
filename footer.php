    <footer>
        <div class="wrapper">
            <div class="left">
                <nav class="social">
                    <h3>Find me on the web...</h3>
                    <ul>
                        <li><a href="http://www.twitter.com/jewlofthelotus" title="Twitter"><i class="icon-twitter"></i></a></li>
                        <li><a href="http://www.facebook.com/jewlofthelotus" title="Facebook"><i class="icon-facebook"></i></a></li>
                        <li><a href="http://www.flickr.com/people/jewlofthelotus" title="Flickr"><i class="icon-flickr"></i></a></li>
                        <li><a href="http://www.youtube.com/user/jewlofthelotus" title="YouTube"><i class="icon-youtube"></i></a></li>
                        <li><a href="http://www.linkedin.com/in/jewlofthelotus" title="LinkedIn"><i class="icon-linkedin"></i></a></li>
                        <li><a href="http://www.github.com/jewlofthelotus" title="GitHub"><i class="icon-github"></i></a></li>
                    </ul>
                </nav>

                <div class="rss">
                    <h3>Follow me for-ev-errr...</h3>
                    <a href="<?php bloginfo('rss2_url') ?>" rel="alternate" type="application/rss+xml"><i class="icon-rss"></i></a>
                </div>

                <p class="copy">&copy;<?php print(date('Y')); ?> <a href="<?php echo get_option('home') ?>/">JewlOfTheLotus.com</a></p>
            </div>

            <div class="right">
                <div class="tags" class="widget">
                    <h3>What's your fancy?</h3>
                    <div class="tag-cloud">
                        <?php wp_tag_cloud( array( 'number' => 50 ) ); ?>
                    </div>
                </div>

                <div class="search">
                    <h3><label for="s">Looking for something specific?</label></h3>
                    <form role="search" method="get" id="searchform" action="<?php bloginfo('url') ?>">
                        <div>
                            <input name="s" type="text" value="" size="20" />
                            <input name="searchsubmit" type="submit" value="Go" />
                        </div>
                    </form>
                </div>

                <div class="devtroit">
                    <a href="http://devtroit.com/" target="_blank"><img src="/wp-content/uploads/2012/02/badge-medium.png" alt="devtroit" /></a>
                </div>
            </div>

            <div class="footer_widgets">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Widgets') ) : else : endif; ?>
            </div>
        </div>
    </footer>

    <script>
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-12297100-3']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
    
    <?php wp_footer(); ?>
</body>
</html>
