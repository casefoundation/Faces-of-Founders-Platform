<?php
/* Template Name: Single history */
get_header();

global $wpdb;
$prefix = $wpdb->prefix;
$table_name = $prefix . "csl_story_reviews";
$current_user = wp_get_current_user();
$story_id = $_GET['story_id'];
$judge = $_GET['judge'];
$data = $wpdb->get_results(
    "
    SELECT *
    FROM $table_name
    WHERE judge_id = $judge
    AND story_id = $story_id
    "
);
$access = false;
$info = ['Not enough <br>information <br>to decide', 'Very Weak', 'Needs <br> Improvement', 'Satisfactory', 'Good', 'Excellent'];
if(current_user_can('administrator')||$data[0]->judge_id==$current_user->ID ) {
  $access=true;
}
$judging_fields = inclusive_entrepreneurship_get_judging_fields();
?>
  <?php if (count($data)==1&&$access==true): ?>
  <main id="main" class="main-content-container" role="main" data-review="<?php echo $data[0]->ID; ?>" data-judge="<?php echo $current_user->ID ?>">
    <div class="sidebar-menu" id="sidebar">
      <div class="sidebar-container">
        <h2 class="hidden-xs">Main Menu</h2>
        <nav class="navbar navbar-default dash-nav" role="navigation">
          <div class="container-fluid">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse-1">
              <ul class="nav nav-tabs menu">
                <li class="item-menu"><a href="<?php bloginfo('url') ?>/dashboard-judge/">Dashboard</a></li>
                <li class="item-menu active"><a data-toggle="tab" href="#home">Review</a></li>
                <li class="item-menu"><a data-toggle="tab" href="#menu2">Guidelines</a></li>
                <li class="item-menu"><a href="#" class="requests-more">Request More</a></li>
                <li class="item-menu"><a target="_top" href="mailto:<?php echo get_option( 'admin_email' ) ?>?subject=<?php echo urlencode(get_bloginfo('name')); ?>%20-%20Request%20Support&amp;body=Judge%20Name%3A%20<?php echo $current_user->first_name .' '.$current_user->last_name; ?>%0AJudge%20User%3A%20<?php echo $current_user->nickname; ?>%0AJudge%20email%20address%3A%20<?php echo $current_user->user_email; ?>">Support</a></li>
                          <li class="item-menu"><a href="<?php echo wp_logout_url(); ?>">Log Out</a></li>
              </ul>
            </div>
          </div>
        </nav>
      </div>
    </div>
    <div class="tab-content">
      <div id="home" class="table-item fade in active">
        <div class="container-single-history">
          <div class="container-history-item">
            <div class="data-part left">
              <h3 class="name"><?php the_field('story_full_name', $story_id); ?></h3>
              <span class="city"><?php the_field('story_city', $story_id); ?>, <?php the_field('story_state', $story_id); ?>, <?php the_field('story_country', $story_id); ?></span>
              <span class="email"><?php the_field('story_email', $story_id); ?></span>
              <div class="container-btn">
                  <a href="#" class="btn-recuse-review">Recuse Review <i class="fa fa-spinner fa-pulse fa-fw"></i></a>
              </div>
            </div>
            <div class="data-part right">
              <?php if (get_field('story_gender', $story_id)!= ""): ?>
              <div class="data-item">
                <strong>Gender: </strong><?php the_field('story_gender', $story_id); ?>
              </div>
              <?php endif; ?>
              <?php if (get_field('story_ethnicy', $story_id)!= ""): ?>
              <div class="data-item">
                <strong>Ethnicity: </strong><?php the_field('story_ethnicy', $story_id); ?>
              </div>
              <?php endif; ?>
              <?php if (get_field('story_age', $story_id)!= ""): ?>
              <div class="data-item">
                <strong>Age: </strong><?php the_field('story_age', $story_id); ?>
              </div>
              <?php endif; ?>
              <?php if (get_field('story_sector', $story_id)!= ""): ?>
              <div class="data-item">
                <strong>Sector: </strong><?php the_field('story_sector', $story_id); ?>
              </div>
              <?php endif; ?>
              <?php if (get_field('story_funding', $story_id)!= ""): ?>
              <div class="data-item">
                <strong>Funding: </strong><?php the_field('story_funding', $story_id); ?>
              </div>
              <?php endif; ?>
            </div>
          </div>

          <div class="panel-group" id="accordion">
            <div class="history story-1">
              <div class="cont-history">
                <h3 data-question="Q1" ><?php echo $judging_fields[0]['field_question']; ?></h3>
                <p data-answer="A1"><?php the_field($judging_fields[0]['field_name'], $story_id); ?></p>
              </div>
            <div class="panel">
              <div class="panel-heading">
                  <h4 class="panel-title">
                    Judging Guideline
                      <a data-toggle="collapse" href="#accordion-1" aria-expanded="true" class=""></a>
                  </h4>
              </div>
              <div id="accordion-1" class="panel-collapse collapse in" aria-expanded="true">
                  <div class="panel-body">
                    <p><?php echo $judging_fields[0]['field_judging_guidance']; ?></p>
                  </div>
              </div>
              <div class="score-panel">
                <h4>Score this answer:</h4>
                <div class="score-checkbox" data-answer="1">
                  <?php

                  for ($i=0; $i <= 5; $i++) { ?>
                    <div class="check-item">
                      <input id="a_1_<?php echo $i; ?>" type="radio" name="check" value="<?php echo $i; ?>" <?php if ($data[0]->answer_1==$i&&is_null($data[0]->answer_1)==false) {echo "checked";} ?> >
                      <label for="a_1_<?php echo $i; ?>">
                        <span class="score"><?php echo $i; ?></span>
                        <span class="info-content">
                          <?php echo $info[$i]; ?>
                        </span>
                      </label>
                    </div>
                  <?php }?>
                </div>
              </div>
          </div>
            </div>
            <div class="history story-2">
              <div class="cont-history">
                <h3 data-question="Q2"><?php echo $judging_fields[1]['field_question']; ?></h3>
                <p data-answer="A2" ><?php the_field($judging_fields[1]['field_name'], $story_id); ?></p>
              </div>
            <div class="panel">
              <div class="panel-heading">
                  <h4 class="panel-title">
                    Judging Guideline
                      <a data-toggle="collapse" href="#accordion-2" aria-expanded="true" class=""></a>
                  </h4>
              </div>
              <div id="accordion-2" class="panel-collapse collapse in" aria-expanded="true">
                  <div class="panel-body">
                    <p><?php echo $judging_fields[1]['field_judging_guidance']; ?></p>
                  </div>
              </div>
              <div class="score-panel">
                <h4>Score this answer:</h4>
                <div class="score-checkbox" data-answer="2">
                  <?php
                  for ($i=0; $i <= 5; $i++) { ?>
                    <div class="check-item">
                      <input id="a_2_<?php echo $i; ?>" type="radio" name="check2" value="<?php echo $i; ?>" <?php if ($data[0]->answer_2==$i&&is_null($data[0]->answer_2)==false) {echo "checked";} ?> >
                      <label for="a_2_<?php echo $i; ?>">
                        <span class="score"><?php echo $i; ?></span>
                        <span class="info-content">
                          <?php echo $info[$i]; ?>
                        </span>
                      </label>
                    </div>
                  <?php }?>
                </div>
              </div>
          </div>
            </div>
            <div class="history story-3">
              <div class="cont-history">
                <h3 data-question="Q3"><?php echo $judging_fields[2]['field_question']; ?></h3>
                <p data-answer="A3"><?php the_field($judging_fields[2]['field_name'], $story_id); ?></p>
              </div>
            <div class="panel">
              <div class="panel-heading">
                  <h4 class="panel-title">
                    Judging Guideline
                      <a data-toggle="collapse" href="#accordion-3" aria-expanded="true" class=""></a>
                  </h4>
              </div>
              <div id="accordion-3" class="panel-collapse collapse in" aria-expanded="true">
                  <div class="panel-body">
                    <p><?php echo $judging_fields[2]['field_judging_guidance']; ?></p>

                  </div>
              </div>
              <div class="score-panel">
                <h4>Score this answer:</h4>
                <div class="score-checkbox" data-answer="3">
                  <?php
                  for ($i=0; $i <= 5; $i++) { ?>
                    <div class="check-item">
                      <input id="a_3_<?php echo $i; ?>" type="radio" name="check3" value="<?php echo $i; ?>" <?php if ($data[0]->answer_3==$i&&is_null($data[0]->answer_3)==false) {echo "checked";} ?> >
                      <label for="a_3_<?php echo $i; ?>">
                        <span class="score"><?php echo $i; ?></span>
                        <span class="info-content">
                          <?php echo $info[$i]; ?>
                        </span>
                      </label>
                    </div>
                  <?php }?>
                </div>
              </div>
              </div>
            </div>
            <div class="history story-4">
              <div class="cont-history">
                <h3 data-question="C">Please provide comments and overall rating for this submission:</h3>
              </div>
              <div class="panel">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    Judging Guideline
                    <a data-toggle="collapse" href="#accordion-3" aria-expanded="true" class=""></a>
                  </h4>
                </div>
                <div id="accordion-3" class="panel-collapse collapse in" aria-expanded="true">
                  <div class="panel-body">
                    <p><?php echo inclusive_entrepreneurship_get_judging_final_guidance(); ?></p>
                  </div>
                </div>
                <div class="score-panel">
                  <h4>Score this answer:</h4>
                  <div class="score-checkbox" data-answer="4">
                    <?php
                    for ($i=0; $i <= 5; $i++) { ?>
                      <div class="check-item">
                        <input id="a_4_<?php echo $i; ?>" type="radio" name="check4" value="<?php echo $i; ?>" <?php if ($data[0]->answer_4==$i&&is_null($data[0]->answer_4)==false) {echo "checked";} ?> >
                        <label for="a_4_<?php echo $i; ?>">
                          <span class="score"><?php echo $i; ?></span>
                          <span class="info-content">
                          <?php echo $info[$i]; ?>
                        </span>
                        </label>
                      </div>
                    <?php }?>
                  </div>
                </div>
              </div>
            </div>
            <div class="text-area-container">
              <textarea name="name" rows="8" cols="40" placeholder="Write your comment here."><?php echo stripslashes($data[0]->comment); ?></textarea>
            </div>
            <div class="container-btn">
                            <a class="save-for-later" href="#">Save for Later <i class="fa fa-floppy-o" aria-hidden="true"></i></a>
              <a class="complete-review" href="#">Submit Review <i class="fa fa-check" aria-hidden="true"></i></a>
            </div>
                </div>
        </div>
      </div>
      <?php get_template_part('molecules/dashboard', 'guideline'); ?>
    </div>
  </main><!-- /main -->
  <?php else: ?>
  <div class="not-found">
    <h1><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Page Not Found</h1>
  </div>
  <?php endif; ?>
<?php
get_footer(); ?>
