<section class="hero" <?php if( get_field('hero_background') ): ?>style="background-image: url('<?php the_field('hero_background');?>');"<?php endif; ?>>
  <div class="container">
    <div class="row">
      <div class="wrapper">
        <div class="wrapper-inner">
              <h1 class="title">
                <span class="title-text">
                  <?php if(get_field("hero_title")):?>
                      <?php the_field("hero_title");?>
                  <?php else:?>
                      <?php the_title(); ?>
                  <?php endif;?>
                </span>
                <?php if (is_page_template('templates/dashboard.php') || is_page_template('templates/dashboard-judge.php') || is_page_template('templates/dashboard-single-history.php')): ?>
                  <?php
                    global $wpdb;
                    $prefix = $wpdb->prefix;
                  	$table_name = $prefix . "csl_story_reviews";
                  	$current_user = wp_get_current_user();
                  	$stories = $wpdb->get_results(
                        "
                        SELECT ID
                        FROM $table_name r
                        WHERE r.status = 0
                        AND r.recused = 0
                        AND r.judge_id = $current_user->ID;
                        "
                    );
                   ?>
                  <div class="welcome-msj">Welcome <span class="user"><?php echo $current_user->user_firstname . ' ' . $current_user->user_lastname; ?></span></div>
                  <div class="welcome-msj-judge">
                    <div>Welcome <span class="user"><?php echo $current_user->user_firstname . ' ' . $current_user->user_lastname; ?></span></div>
                    <div class="pending-msj">You have <span><?php echo count($stories) ?> Stories</span> to review by <span><?php the_field('deadline_for_review', 'options') ?></span></div>
                  </div>
                <?php endif; ?>
              </h1>
        </div>
      </div>
    </div>
  </div>
  <div class="section-devider"></div>
</section>
<a href="<?php bloginfo('home'); ?>" class="btn back">
  <i class="fa fa-angle-left"></i>
  <span class="hidden-xs"> Back to<br>Homepage</span>
</a>
