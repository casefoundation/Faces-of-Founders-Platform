<section class="primary-partners">
  <div class="container">
    <div class="row">
        <h2><?php the_field('primary_partners_title'); ?></h2>

          <?php if( have_rows('primary_partners_items') ): ?>

            <ul class="partners-container">
            <?php while( have_rows('primary_partners_items') ): the_row();
              // vars
              $image = get_sub_field('image');
              $title = get_sub_field('title');
              $content = get_sub_field('description');
              $facebook = get_sub_field('facebook_url');
              $twitter = get_sub_field('twitter_url');
              $url = get_sub_field('external_url');
              ?>
              <li class="col-md-3 col-sm-6 col-xs-12 match-height">
                <div class="partner-item">
                  <div class="container-image">
                    <div class="wrapper">
                      <div class="wrapper-inner">
                        <img src="<?php echo $image; ?>">
                      </div>
                    </div>
                  </div>
                  <h3><?php echo $title; ?></h3>
                  <?php echo $content; ?>
                  <div class="social-items">
                    <ul>
                      <?php if( $facebook ): ?>
                        <li><a href="<?php echo $facebook; ?>" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                      <?php endif; ?>
                      <?php if( $twitter ): ?>
                        <li><a href="<?php echo $twitter; ?>" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                      <?php endif; ?>
                      <?php if( $url ): ?>
                        <li><a href="<?php echo $url; ?>" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a></li>
                      <?php endif; ?>
                    </ul>
                  </div>
                </div>
              </li>
            <?php endwhile; ?>
            </ul>

          <?php endif; ?>

    </div>
  </div>
  <div class="section-devider"></div>
</section>
