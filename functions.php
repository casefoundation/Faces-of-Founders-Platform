<?php
if ( ! function_exists( 'inclusive_entrepreneurship_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function inclusive_entrepreneurship_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on inclusive-entrepreneurship, use a find and replace
	 * to change 'inclusive-entrepreneurship' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'inclusive-entrepreneurship', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
        //add_image_size('open_graph', 1200, 630);
        add_image_size('story-full', 2100, 2100, true);
        add_image_size('story-preview', 500, 500, true);
        add_image_size('story-medium', 300, 300, true);

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'inclusive-entrepreneurship' ),
        'footer' => 'Footer Menu',
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'inclusive_entrepreneurship_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'inclusive_entrepreneurship_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function inclusive_entrepreneurship_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'inclusive_entrepreneurship_content_width', 640 );
}
add_action( 'after_setup_theme', 'inclusive_entrepreneurship_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function inclusive_entrepreneurship_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'inclusive-entrepreneurship' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'inclusive-entrepreneurship' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'inclusive_entrepreneurship_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function inclusive_entrepreneurship_scripts() {

    $styles_version = "0.5.2";
    $scripts_version = "1.8.4";

    /****************
     * STYLES
     * ****************
     */

    // VENDORS
    wp_enqueue_style('bootstrap', get_template_directory_uri() . "/css/bootstrap.css", array(), '3.3.5', 'all');
    wp_enqueue_style('font-awesome', get_template_directory_uri() . "/css/font-awesome.min.css", array(), '4.6.3', 'all');
    wp_enqueue_style('slick-css', get_template_directory_uri() . "/src/slick/slick.css", array(), '1.6.0', 'all');
    wp_enqueue_style('sweetalert-css', get_template_directory_uri() . "/css/sweetalert.css", array(), '1.1.3', 'all');

    // IN HOUSE
    wp_enqueue_style('ie-csl-theme', get_template_directory_uri() . "/css/styles.css", array('bootstrap','font-awesome'), $styles_version, 'all');

    /* ****************
     * SCRIPTS
     * ****************
     */

    // VENDORS
    wp_register_script('modernizr', get_template_directory_uri() . '/src/js/lib/modernizr-2.8.3-respond-1.4.2.min.js', array(), '2.8.3'); // Modernizr
    wp_enqueue_script('modernizr'); // Enqueue it!

    wp_register_script('bootstrap', get_template_directory_uri() . '/src/js/lib/bootstrap.min.js', array('jquery'), '3.3.5'); // Bootstrap
    wp_enqueue_script('bootstrap'); // Enqueue it!

    wp_register_script('matchHeight', get_template_directory_uri() . '/src/js/lib/jquery.matchHeight.js', array('jquery'), '1.0.0'); // Match Height
    wp_enqueue_script('matchHeight'); // Enqueue it!

    wp_register_script('slick-slider', get_template_directory_uri() . '/src/slick/slick.min.js', array('jquery'), '1.6.0'); // Slick
    wp_enqueue_script('slick-slider'); // Enqueue it!

    wp_register_script('validate', get_template_directory_uri() . '/js/lib/jquery.validate.min.js', array('jquery'), '1.15.1'); // Validate
    wp_enqueue_script('validate'); // Enqueue it!

    wp_register_script('form', get_template_directory_uri() . '/js/lib/jquery.form.min.js', array('jquery'), '3.51.0'); // Validate
    wp_enqueue_script('form'); // Enqueue it!

    wp_register_script('jquery-ui', get_template_directory_uri() . '/js/lib/jquery-ui.min.js', array('jquery'), '1.2.6'); // jQuery UI
    wp_enqueue_script('jquery-ui'); // Enqueue it!

    wp_register_script('exif', get_template_directory_uri() . '/js/lib/exif.js', array('jquery'), '1.2.6'); // jQuery UI
    wp_enqueue_script('exif'); // Enqueue it!

		wp_register_script('tablesorter', get_template_directory_uri() . '/js/jquery.tablesorter.min.js', array('jquery'), '2.0.5'); // jQuery UI
    wp_enqueue_script('tablesorter'); // Enqueue it!

		wp_register_script('sweetalert-js', get_template_directory_uri() . '/js/sweetalert.min.js', array('jquery'), '1.1.3'); // jQuery UI
    wp_enqueue_script('sweetalert-js'); // Enqueue it!

	// IN HOUSE
    wp_register_script('ie-csl-utils', get_template_directory_uri() . '/src/js/lib/utils.js', array('jquery'), '1.0.0'); // Utils
    wp_enqueue_script('ie-csl-utils'); // Enqueue it!

    wp_register_script('ie-csl-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), $scripts_version); // Custom scripts
    wp_enqueue_script('ie-csl-scripts'); // Enqueue it!


	wp_enqueue_script( 'inclusive-entrepreneurship-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'inclusive_entrepreneurship_scripts' );


add_action('init', 'ie_remove_editor_to_story');
function ie_remove_editor_to_story() {
    remove_post_type_support('story', 'editor' );
}

/**
 * Options Page.
 */
if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Site Options',
		'menu_title'	=> 'Site Options',
		'menu_slug' 	=> 'site-options',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

}

function csl_html_attrs($attr_array, $print = true) {
    $attributes = '';

    foreach ($attr_array as $attr => $value) {
        $value = esc_attr($value);
        if ((string) $value === '')
            continue;

        $attributes .= " $attr=\"$value\"";
    }
    if ($print) {
        echo $attributes;
    } else {
        return $attributes;
    }
}


function wpseo_remove_yoast_og_image (  ) {
    $attachment_id = @$_REQUEST['i'];

    $image = wp_get_attachment_image_src($attachment_id,'story-full');
    $url = add_query_arg(['i' => $attachment_id], get_bloginfo('url'));
    if (is_array($image) && @$image[0]){

        remove_action('wpseo_opengraph', array($GLOBALS['wpseo_og'],'image'),30);
        remove_action( 'wpseo_opengraph', array( $GLOBALS['wpseo_og'], 'url' ), 12 );
        echo <<<EOL
        <meta property="og:url" content="$url" />
        <meta property="og:image" content="{$image[0]}" />
        <meta property="og:width" content="{$image[1]}" />
        <meta property="og:height" content="{$image[2]}" />
EOL;
    }


}
add_action( 'wpseo_head', 'wpseo_remove_yoast_og_image', 20 );

function download_attachments_rewrite_rule() {
    add_rewrite_rule('^download/(.+)', 'index.php?download_id=$matches[1]', 'top');
    add_rewrite_tag('%download_id%', '(.+)');
}
add_action('init', 'download_attachments_rewrite_rule');


function download_attachments_template_redirect()
{
    global $wp_query;
    if ( @$wp_query->query_vars['download_id']) {
        $url = wp_get_attachment_url( $wp_query->query_vars['download_id']);
        if ($url) {
            header("Content-Type: application/octet-stream");
            header("Content-Transfer-Encoding: Binary");
            header('Content-disposition: attachment; filename=profile-picture.jpg');
            readfile($url);
            die();
        }
    }
}

/**
 * CUSTOM ClASS FOR BODY CLASS.
 */
add_filter('body_class','role_class_names');
function role_class_names($classes) {
		global $current_user;
		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);
    $classes[] = $user_role;
    return $classes;
}


add_action( 'template_redirect', 'download_attachments_template_redirect' );



/**
 * Widget for Stories.
 */
add_action('wp_dashboard_setup', 'stories_dashboard_widgets');

function stories_dashboard_widgets() {
global $wp_meta_boxes;

wp_add_dashboard_widget('custom_help_widget', 'Stories Info', 'stories_stats');
}

function stories_stats() {

    $stories_query = new WP_Query(
    array(
        'post_type' => 'story',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query'    => array(
            'relation'      => 'AND',
            array(
                'key'       => 'story_problem_solve',
                'value'     => '',
                'compare'   => '!='
            ),
            array(
                'key'       => 'story_biggest_obstacle',
                'value'     => '',
                'compare'   => '!='
            ),
            array(
                'key'       => 'story_entrepeneurship_important',
                'value'     => '',
                'compare'   => '!='
            )
        )
    ) );
    $stories_count = $stories_query->post_count;
    wp_reset_postdata();

    $image_query = new WP_Query(
    array(
        'post_type' => 'story',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query'    => array(
            'relation'      => 'AND',
            array(
                'key'       => 'story_image',
                'value'     => '',
                'compare'   => '!='
            )
        )
    ) );
    $image_count = $image_query->post_count;
    wp_reset_postdata();

    $twitter_query = new WP_Query(
    array(
        'post_type' => 'story',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'story_category',
                'field'    => 'slug',
                'terms'    => 'submission-pulled-via-tweet',
            ),
        ),
    ) );
    $twitter_count = $twitter_query->post_count;
    wp_reset_postdata();

    $last_image = new WP_Query(
    array(
        'post_type' => 'story',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'meta_query'    => array(
            'relation'      => 'AND',
            array(
                'key'       => 'story_image',
                'value'     => '',
                'compare'   => '!='
            )
        )
    ) );
    wp_reset_postdata();
?>
    <p><span class="dashicons dashicons-admin-post"></span> Stories: <strong><a href="<?php bloginfo('url') ?>/wp-admin/edit.php?s&post_status=all&post_type=story&story-filter=stories"><?php echo $stories_count; ?></a></strong></p>
    <p><span class="dashicons dashicons-format-image"></span> Images: <strong><a href="<?php bloginfo('url') ?>/wp-admin/edit.php?s&post_status=all&post_type=story&story-filter=images"><?php echo $image_count; ?></a></strong></p>
    <p><span class="dashicons dashicons-twitter"></span> Via Twitter: <strong><a href="<?php bloginfo('url') ?>/wp-admin/edit.php?s&post_status=all&post_type=story&story-filter=via-twitter"><?php echo $twitter_count; ?></a></strong></p>
    <hr>
    <h3>Last Generated Image</h3>
    <?php
        while ( $last_image->have_posts() ) {
            $last_image->the_post();
            if ($image = get_field('story_image')) {
                echo wp_get_attachment_image( $image, 'large', "", array( "style" => 'max-width: 100%; height: auto;' ) );
            }
        }
    ?>
<?php
}

add_action( 'restrict_manage_posts', 'stories_admin_posts_filter' );

function stories_admin_posts_filter(){
    $type = 'post';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }

    //only add filter to post type you want
    if ('story' == $type){
        //change this to the list of values you want to show
        //in 'label' => 'value' format
        $values = array(
            'Images' => 'images',
            'Stories' => 'stories',
            'Via Twitter' => 'via-twitter',
        );
        ?>
        <select name="story-filter">
        <option value="">All entries</option>
        <?php
            $current_v = isset($_GET['story-filter'])? $_GET['story-filter']:'';
            foreach ($values as $label => $value) {
                printf
                    (
                        '<option value="%s"%s>%s</option>',
                        $value,
                        $value == $current_v? ' selected="selected"':'',
                        $label
                    );
                }
        ?>
        </select>
        <?php
    }
}


add_filter( 'pre_get_posts', 'csl_posts_filter' );
/**
 * if submitted filter by post meta
 *
 * make sure to change META_KEY to the actual meta key
 * and POST_TYPE to the name of your custom post type
 *
 * @return Void
 */
function csl_posts_filter( $query ){
    global $pagenow, $post_type;

    if ( 'story' == $post_type && is_admin() && $pagenow=='edit.php' && isset($_GET['story-filter']) && $_GET['story-filter'] != '') {
        if ('images' == $_GET['story-filter']) {
            $add_query = array(
                'relation'      => 'AND',
                array(
                    'key'       => 'story_image',
                    'value'     => '',
                    'compare'   => '!='
                )
            );
            $query->set('meta_query', $add_query);
            $query->set('post_status', 'publish');

        }
        elseif ('stories' == $_GET['story-filter']) {
            $add_query = array(
                'relation'      => 'AND',
                array(
                    'key'       => 'story_problem_solve',
                    'value'     => '',
                    'compare'   => '!='
                ),
                array(
                    'key'       => 'story_biggest_obstacle',
                    'value'     => '',
                    'compare'   => '!='
                ),
                array(
                    'key'       => 'story_entrepeneurship_important',
                    'value'     => '',
                    'compare'   => '!='
                )
            );
            $query->set('meta_query', $add_query);
        }
        elseif ('via-twitter' == $_GET['story-filter']) {
            $add_query = array(
                    array(
                        'taxonomy' => 'story_category',
                        'field'    => 'slug',
                        'terms'    => 'submission-pulled-via-tweet',
                    ),
                );
            $query->query_vars['tax_query'] = $add_query;
        }
    }
}

add_action('wp_login','user_last_login', 0, 2);
function user_last_login($login, $user) {
	$user = get_user_by('login',$login);
	$now = time();
	update_usermeta( $user->ID, 'user_last_login', $now );
}
add_action( 'init', 'csl_create_judge_role' );
function csl_create_judge_role() {
    if (get_role( 'judge' ) == null) {
        add_role(
            'judge',
            __( 'Judge' ),
            array(
                'read'         => true,  // true allows this capability
                'edit_posts'   => false,
                'delete_posts' => false, // Use false to explicitly deny
            )
        );
    }
}
add_action( 'profile_update', 'custom_profile_redirect', 12 );
function custom_profile_redirect() {
    if ( current_user_can( 'judge' ) ) {
        wp_redirect( home_url('/dashboard-judge')  );
        exit;
    }
}

/**
 * Hide admin bar to judges.
 */
if ( ! current_user_can('administrator') ) {
    add_filter( 'show_admin_bar', '__return_false' );
}

function csl_check_required_plugins() {
    if( !class_exists('acf_pro') ):
    echo '<div class="notice notice-error is-dismissible"><p>This site requires the <strong>PRO</strong> version of <strong>Advanced Custom Fields</strong>, please install and activate it.</p></div>';
    endif;
    if( !class_exists('LoginWithAjax') ):
    echo '<div class="notice notice-error is-dismissible"><p>This site requires <strong>Login With Ajax</strong> plugin, please install and activate it.</p></div>';
    endif;
}
add_action( 'admin_notices', 'csl_check_required_plugins' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Custom ajax functions handlers.
 */
require get_template_directory() . '/inc/ie-ajax.php';

/**
 * Custom Fields.
 */
require get_template_directory() . '/inc/advanced-custom-fields.php';

/**
 * CSL Social Share.
 */
require get_template_directory() . '/inc/csl-social-share.php';

/**
 * Custom tables for WP Databases.
 */
require get_template_directory() . '/inc/csl-db.php';
