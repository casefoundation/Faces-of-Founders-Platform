<?php
/* Template Name: Dashboard Judge */
get_header();
$current_user = wp_get_current_user();

?>

  <main id="main" class="main-content-container" role="main" data-judge="<?php echo $current_user->ID ?>">
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
          <li class="item-menu active"><a data-toggle="tab" href="#home">Dashboard</a></li>
          <li class="item-menu"><a data-toggle="tab" id="guideline" href="#menu2">Guidelines</a></li>
          <li class="item-menu"><a href="#" class="requests-more">Request More</a></li>
          <li class="item-menu"><a target="_top" href="mailto:<?php echo urlencode(get_option('admin_email')); ?>?subject=<?php echo urlencode(get_bloginfo('name')); ?>%20-%20Request%20Support&amp;body=Judge%20Name%3A%20<?php echo $current_user->first_name .' '.$current_user->last_name; ?>%0AJudge%20User%3A%20<?php echo $current_user->nickname; ?>%0AJudge%20email%20address%3A%20<?php echo $current_user->user_email; ?>">Support</a></li>
          <li class="item-menu"><a href="<?php echo wp_logout_url(); ?>">Log Out</a></li>
        </ul>
      </div>
      </div>
    </nav>
      </div>
    </div>
    <div class="tab-content">
      <?php
      get_template_part('molecules/dashboard', 'home');
      get_template_part('molecules/dashboard', 'guideline');
      ?>
    </div>
  </main><!-- /main -->

<?php
get_footer(); ?>
