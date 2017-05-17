<!DOCTYPE html>
<html <?php language_attributes(); ?> class="ie-csl-theme">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="shortcut icon" href="<?php echo get_template_directory_uri();?>/src/img/favicon.ico" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <script>
    window.fbAsyncInit = function() {
      FB.init({
        appId      : '<?php the_field('facebook_app_id','options') ?>',
        xfbml      : true,
        version    : 'v2.7',
        status   : true
      });
    };

    (function(d, s, id){
       var js, fjs = d.getElementsByTagName(s)[0];
       if (d.getElementById(id)) {return;}
       js = d.createElement(s); js.id = id;
       js.src = "//connect.facebook.net/en_US/sdk.js";
       fjs.parentNode.insertBefore(js, fjs);
     }(document, 'script', 'facebook-jssdk'));
  </script>
  <header id="main-header" class="header" role="header">
    <?php
    if ( is_front_page() ) {
      get_template_part( 'organisms/header', 'frontpage' );
    } else {
      get_template_part( 'organisms/header', 'inner' );
    }
    ?>
  </header><!-- /header -->
