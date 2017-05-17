<div class="section-intro">
    <div class="container">
        <?php
        if( get_field('enable_limit_date')=='enabled' ){
            $limit_date_field = get_field('form_limit_date');
            date_default_timezone_set('US/Eastern');
            $limit_date = DateTime::createFromFormat('d/m/Y g:i a', $limit_date_field);
            $now = new DateTime();
        }
        ?>
        <?php if( get_field('enable_limit_date')=='enabled' ? $now<$limit_date : true ):?>

            <?php if( get_field('intro_text') ):
                the_field('intro_text');
            endif; ?>

        <?php else:?>
            <?php if( get_field('expired_time_header_copy') ):
                the_field('expired_time_header_copy');
            endif; ?>
        <?php endif;?>
    </div>
</div>
<div class="photo-generator">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 generator-box">
        <div class="generator-tools">
          <h2 class="title">Create Your Photo!</h2>
          <div class="steps-container">
            <div class="step step-1">
              <div class="step-number"><span>step</span><strong>1</strong></div>
              <div class="step-action upload">
                <ul>
                  <li>Upload Via</li>
                  <li>
                    <button class="upload-photo fb">
                      <i class="fa fa-facebook"></i>
                    </button>
                  </li>
                  <li>
                    <button class="upload-photo tw">
                      <i class="fa fa-twitter"></i>
                    </button>
                  </li>
                  <li>
                    <div class="upload-photo file-input">
                      <input type="file" id="localImage" name="localImage">
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="step step-2 disabled">
              <div class="step-number"><span>step</span><strong>2</strong></div>
              <div class="step-action choose-message">
                <?php if( have_rows('overlays_section', 'option') ): ?>
                <div class="message-slider">
                  <?php while ( have_rows('overlays_section', 'option') ) : the_row(); ?>
                    <label>
                      <span><?php the_sub_field('overlay_title'); ?></span>
                      <input type="radio" name="selected-overlay" value="<?php the_sub_field('overlay_image'); ?>">
                    </label>
                  <?php endwhile; ?>
                </div>
                <?php endif; ?>
              </div>
            </div>
            <div class="founder-generated-photo">

              <div class="image-resizer">
                <div class="image-imitation"></div>
              </div>

              <?php if( have_rows('overlays_section', 'option') ): ?>
              <div class="founder-filters">
                <?php while ( have_rows('overlays_section', 'option') ) : the_row(); ?>
                  <img src="<?php the_sub_field('overlay_image'); ?>" alt="">
                <?php endwhile; ?>
              </div>
              <?php endif; ?>
                                                        <div class="image-container">
                                                            <img src="<?php bloginfo('stylesheet_directory'); ?>/src/img/CF-placeholder.png" class="user-image interact">
                                                        </div>

            </div>
            <div class="step step-3 disabled">
              <div class="step-number"><span>step</span><strong>3</strong></div>
              <div class="step-action">
                <div class="checkbox">
                  <input type="checkbox" id="print" name="print_photo" value="1">
                  <label for="print">I agree to the terms of use *</label>

                                                                        <!-- Button trigger modal -->
                                                                        <button type="button" data-toggle="modal" data-target="#generator_help">
                                                                            <i class="fa fa-info-circle"></i>
                                                                        </button>
                </div>
                                <?php if(true == get_field('show_newsletter_option', 'options')) : ?>
                <div class="checkbox">
                  <input type="checkbox" id="sigup-newsletter" name="sign_up_for_newsletter" value="1">
                  <label for="sigup-newsletter"><?php the_field('newsletter_text', 'options')?></label>
                </div>
                                <?php endif; ?>
                <input class="email" type="email" placeholder="Email address *" name="email">
                <button class="submit" type="submit">
                  <span class="generate-text">Let’s see it!</span>
                  <span class="generating-text">Generating <i class="fa fa-refresh fa-spin" aria-hidden="true"></i></span>
                </button>

              </div>
            </div>
          </div>
        </div>
        <div class="share-face-of-founder">
          <figure class="face-of-founder">
            <img src="<?php bloginfo('stylesheet_directory'); ?>/src/img/CF-placeholder.png" alt="">
          </figure>
          <div class="share-tools">
            <h2>Great Photo! Thanks for joining the movement.</h2>
            <h3>Now help spread the word.</h3>
            <p>Share your photo on</p>
            <ul>
              <?php $social_networks = [
                  'facebook' => ['caption' => get_bloginfo('name'),
                    'ref_url' => get_permalink(),
                    'description' => get_bloginfo('description')
                  ],
                  'twitter' => ['title' => get_bloginfo('description')],
                  //'instagram',
                  'linkedin' => [
                    'title' => get_bloginfo('name'),
                    'description' => get_bloginfo('description')
                  ],
                ]; ?>
              <?php foreach ($social_networks as $network => $params): ?>
              <li>
                <a <?php csl_html_attrs([
                  'id' => "share-$network",
                  'data-role' => 'share',
                  'data-share-type' => $network,
                  'data-share-url' => csl_set_social_shares_url($network,get_permalink(),$params),
                  'href' => csl_set_social_shares_url($network,get_permalink(),$params),
                  'data-caption' => @$params['caption'],
                  'data-refurl' => @$params['ref_url'],
                  'data-description' => @$params['description'],
                  'data-title' => @$params['title'],
                ]) ?>>
                  <i class="fa fa-<?php echo $network ?>"></i>
                </a>
              </li>
              <?php endforeach; ?>

            </ul>
            <p>and</p>
            <button class="btn profile-picture make"><i class="fa fa-facebook"></i>Make Profile Picture</button>
            <p>and</p>
            <a href="#founder-story" class="btn smooth">Share your Story with us</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><!-- /photo-generator -->
