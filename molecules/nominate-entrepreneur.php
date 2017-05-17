<section class="nominate-entrepreneur" style="background-image: url('<?php the_field('nominate_bg_img'); ?>');">
  <div class="container">
    <div class="row">
      <article class="col-md-5 match-height col-md-offset-1">
        <div class="article-container">
          
                    <?php
                        if( get_field('enable_limit_date')=='enabled' ){
                            $limit_date_field = get_field('form_limit_date');
                            date_default_timezone_set('US/Eastern');
                            $limit_date = DateTime::createFromFormat('d/m/Y g:i a', $limit_date_field);
                            $now = new DateTime();
                        }
                    ?>
                    <?php if( get_field('enable_limit_date')=='enabled' ? $now<$limit_date : true ):?>
                        <h2><?php the_field('nominate_title'); ?></h2>
                        <?php the_field('nominate_copy'); ?>
                    <?php else:?>
                         <h2>
                        <?php if( get_field('expired_time_footer_title') ):
                                the_field('expired_time_footer_title');
                        endif; ?></h2>
                        <?php the_field('expired_time_footer_body'); ?>
                    <?php endif;?>
                                        
          
        </div>
      </article>
      <div class="col-md-6 match-height form-container">
        <div class="newsletter-form">
          <form action="<?php echo admin_url('admin-ajax.php'); ?>" id="nominate-entrepeneur" method="post">
            <div class="form-group">
              <label for="full-name">I’m sharing with: *</label>
              <input type="text" name="full-name" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="email">Their email *</label>
              <input type="email" name="email" class="form-control" required>
            </div>
                                                <input type="hidden" name="action" value="nominee_entrepeneur"/>
            <button type="submit" class="btn">Share Now<i class="fa fa-paper-plane-o"></i></button>
                                                <button class="btn processing" disabled>Processing <i class="fa fa-refresh fa-spin" aria-hidden="true"></i></button>
          </form>

                                        <div class="thank-you-entrepeneur">
                                            <h3>Thank you!</h3>
                                            <p>
                                                We'll be in touch soon.
                                            </p>
                                        </div>
        </div>
      </div>
    </div>
  </div>
</section>
