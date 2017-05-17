<section class="partners">
  <div class="container">
    <div class="row">
        <h2><?php the_field('partners_title'); ?></h2>

          <?php if( have_rows('partners') ): ?>

            <ul class="partners-container">
            <?php while( have_rows('partners') ): the_row();
              // vars
              $image = get_sub_field('partner_logo');
              $url = get_sub_field('partner_url');
              ?>
              <li class="col-md-3 col-sm-6 col-xs-12 match-height">
                  <a href="<?php echo $url; ?>" target="_blank" class="partner-item">
                    <div class="wrapper">
                      <div class="wrapper-inner">
                        <img src="<?php echo $image; ?>">
                      </div>
                    </div>
                  </a>
              </li>
            <?php endwhile; ?>
            </ul>

          <?php endif; ?>

    </div>
  </div>
</section>
