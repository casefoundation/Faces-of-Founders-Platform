        <?php if(get_field('modal_generator_help_content', 'options')):?>
        <!-- Modal -->
        <div class="modal fade" id="generator_help" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php if(get_field('modal_generator_help_title', 'options')):?>
                    <h4 class="modal-title" id="myModalLabel">
                        <?php the_field('modal_generator_help_title', 'options');?>
                    </h4>
                <?php endif;?>
              </div>
              <div class="modal-body">
                <?php the_field('modal_generator_help_content', 'options');?>
              </div>

            </div>
          </div>
        </div>
        <?php endif;?>

        <?php if(get_field('awards_modal_content', 'options')):?>
        <!-- Modal -->
        <div class="modal fade" id="awards_help" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php if(get_field('modal_awards_title', 'options')):?>
                    <h4 class="modal-title" id="myModalLabel">
                        <?php the_field('modal_awards_title', 'options');?>
                    </h4>
                <?php endif;?>
              </div>
              <div class="modal-body">
                <?php the_field('awards_modal_content', 'options');?>
              </div>

            </div>
          </div>
        </div>
        <?php endif;?>
        
        <!-- Twitter Modal -->
        <div class="modal fade" id="twitter_share" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               
                    <h4 class="modal-title" id="myModalLabel">
                        Twitter Share
                    </h4>

              </div>
              <div class="modal-body">
                 
                  
                  <form action="<?php echo admin_url('admin-ajax.php'); ?>" class="story" id="tweet_form" method="post">
                                <img src="" class="tweet_image"/>
        <div class="form-group">
          <label for="tweet-content">Tweet content</label>
                                        <textarea class="form-control autoheight maxchars" data-max-chars="200" rows="1" id="tweet-content" name="tweet_content" required></textarea>
        </div>
                                <input type="hidden" name="action" value="tweet_image"/>
                                <input type="hidden" name="tweet_image" value=""/>

        <button type="submit" class="btn btn-primary">Tweet it! <i class="fa fa fa-twitter"></i></button>
                                <button class="btn btn-primary processing" disabled="">Tweeting<i class="fa fa-refresh fa-spin" aria-hidden="true"></i></button>
      
                    </form>
                  <div class="thanks-image-tweet">
                      <h3>Your tweet was sent!</h3>
                      <p>
                          Check your tweet here: <a href="#" id="tweet_url" target="_blank">Tweet URL</a>
                      </p>
                  </div>
              </div>

            </div>
          </div>
        </div>
        <script>
            var homepage_url = "<?php echo esc_url( home_url( '/' ) ); ?>";
        </script>
        <footer id="main-footer" class="footer" role="footer">
    <div class="container-fluid">
      <div class="row">
        <div class="footer-nav">
          <?php wp_nav_menu ([ 'theme_location' => 'footer']); ?>
          <ul class="social-media">
            <li><a href="<?php the_field('facebook', 'option'); ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
            <li><a href="<?php the_field('twitter', 'option'); ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
            <li><a href="<?php the_field('instagram', 'option'); ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
            <li><a href="mailto:<?php the_field('email', 'option'); ?>"><i class="fa fa-envelope-o"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer><!-- /footer -->
<?php wp_footer(); ?>
</body>
</html>
