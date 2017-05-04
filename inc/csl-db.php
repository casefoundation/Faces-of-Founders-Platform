<?php

global $csl_db_version;
$csl_db_version = '1.7';

/**
 * CSL Add a custom post type.
 *
 * Note: Post type registrations should not be hooked before the
 * {@see 'init'} action. Also, any taxonomy connections should be
 * registered via the `$taxonomies` argument to ensure consistency
 * when hooks such as {@see 'parse_query'} or {@see 'pre_get_posts'}
 * are used.
 *
 * Post types can support any number of built-in core features such
 * as meta boxes, custom fields, post thumbnails, post statuses,
 * comments, and more. See the `$supports` argument for a complete
 * list of supported features.
 *

 *
 * @param string $post_type                     Post type key. Must not exceed 20 characters and may
 *                                               only contain lowercase alphanumeric characters, dashes,
 *                                              and underscores. See sanitize_key().
 *
 * @param string $name                          Name of the post type shown in the menu. Usually plural.
 *
 * @param string $singular_name                 Name for one object of this post type.
 *
 * @param array|string $args {
 *     Array or string of arguments for registering a post type.
 *
 *     @type string      $label                 Name of the post type shown in the menu. Usually plural.
 *                                              Default is value of $labels['name'].
 *     @type array       $labels                An array of labels for this post type. If not set, post
 *                                              labels are inherited for non-hierarchical types and page
 *                                              labels for hierarchical ones. See get_post_type_labels() for a full
 *                                              list of supported labels.
 *     @type string      $description           A short descriptive summary of what the post type is.
 *                                              Default empty.
 *     @type bool        $public                Whether a post type is intended for use publicly either via
 *                                              the admin interface or by front-end users. While the default
 *                                              settings of $exclude_from_search, $publicly_queryable, $show_ui,
 *                                              and $show_in_nav_menus are inherited from public, each does not
 *                                              rely on this relationship and controls a very specific intention.
 *                                              Default false.
 *     @type bool        $hierarchical          Whether the post type is hierarchical (e.g. page). Default false.
 *     @type bool        $exclude_from_search   Whether to exclude posts with this post type from front end search
 *                                              results. Default is the opposite value of $public.
 *     @type bool        $publicly_queryable    Whether queries can be performed on the front end for the post type
 *                                              as part of parse_request(). Endpoints would include:
 *                                              * ?post_type={post_type_key}
 *                                              * ?{post_type_key}={single_post_slug}
 *                                              * ?{post_type_query_var}={single_post_slug}
 *                                              If not set, the default is inherited from $public.
 *     @type bool        $show_ui               Whether to generate and allow a UI for managing this post type in the
 *                                              admin. Default is value of $public.
 *     @type bool        $show_in_menu          Where to show the post type in the admin menu. To work, $show_ui
 *                                              must be true. If true, the post type is shown in its own top level
 *                                              menu. If false, no menu is shown. If a string of an existing top
 *                                              level menu (eg. 'tools.php' or 'edit.php?post_type=page'), the post
 *                                              type will be placed as a sub-menu of that.
 *                                              Default is value of $show_ui.
 *     @type bool        $show_in_nav_menus     Makes this post type available for selection in navigation menus.
 *                                              Default is value $public.
 *     @type bool        $show_in_admin_bar     Makes this post type available via the admin bar. Default is value
 *                                              of $show_in_menu.
 *     @type bool        $show_in_rest          Whether to add the post type route in the REST API 'wp/v2' namespace.
 *     @type string      $rest_base             To change the base url of REST API route. Default is $post_type.
 *     @type string      $rest_controller_class REST API Controller class name. Default is 'WP_REST_Posts_Controller'.
 *     @type int         $menu_position         The position in the menu order the post type should appear. To work,
 *                                              $show_in_menu must be true. Default null (at the bottom).
 *     @type string      $menu_icon             The url to the icon to be used for this menu. Pass a base64-encoded
 *                                              SVG using a data URI, which will be colored to match the color scheme
 *                                              -- this should begin with 'data:image/svg+xml;base64,'. Pass the name
 *                                              of a Dashicons helper class to use a font icon, e.g.
 *                                              'dashicons-chart-pie'. Pass 'none' to leave div.wp-menu-image empty
 *                                              so an icon can be added via CSS. Defaults to use the posts icon.
 *     @type string      $capability_type       The string to use to build the read, edit, and delete capabilities.
 *                                              May be passed as an array to allow for alternative plurals when using
 *                                              this argument as a base to construct the capabilities, e.g.
 *                                              array('story', 'stories'). Default 'post'.
 *     @type array       $capabilities          Array of capabilities for this post type. $capability_type is used
 *                                              as a base to construct capabilities by default.
 *                                              See get_post_type_capabilities().
 *     @type bool        $map_meta_cap          Whether to use the internal default meta capability handling.
 *                                              Default false.
 *     @type array       $supports              Core feature(s) the post type supports. Serves as an alias for calling
 *                                              add_post_type_support() directly. Core features include 'title',
 *                                              'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt',
 *                                              'page-attributes', 'thumbnail', 'custom-fields', and 'post-formats'.
 *                                              Additionally, the 'revisions' feature dictates whether the post type
 *                                              will store revisions, and the 'comments' feature dictates whether the
 *                                              comments count will show on the edit screen. Defaults is an array
 *                                              containing 'title' and 'editor'.
 *     @type callable    $register_meta_box_cb  Provide a callback function that sets up the meta boxes for the
 *                                              edit form. Do remove_meta_box() and add_meta_box() calls in the
 *                                              callback. Default null.
 *     @type array       $taxonomies            An array of taxonomy identifiers that will be registered for the
 *                                              post type. Taxonomies can be registered later with register_taxonomy()
 *                                              or register_taxonomy_for_object_type().
 *                                              Default empty array.
 *     @type bool|string $has_archive           Whether there should be post type archives, or if a string, the
 *                                              archive slug to use. Will generate the proper rewrite rules if
 *                                              $rewrite is enabled. Default false.
 *     @type bool|array  $rewrite              {
 *         Triggers the handling of rewrites for this post type. To prevent rewrite, set to false.
 *         Defaults to true, using $post_type as slug. To specify rewrite rules, an array can be
 *         passed with any of these keys:
 *
 *         @type string $slug       Customize the permastruct slug. Defaults to $post_type key.
 *         @type bool   $with_front Whether the permastruct should be prepended with WP_Rewrite::$front.
 *                                  Default true.
 *         @type bool   $feeds      Whether the feed permastruct should be built for this post type.
 *                                  Default is value of $has_archive.
 *         @type bool   $pages      Whether the permastruct should provide for pagination. Default true.
 *         @type const  $ep_mask    Endpoint mask to assign. If not specified and permalink_epmask is set,
 *                                  inherits from $permalink_epmask. If not specified and permalink_epmask
 *                                  is not set, defaults to EP_PERMALINK.
 *     }
 *     @type string|bool $query_var             Sets the query_var key for this post type. Defaults to $post_type
 *                                              key. If false, a post type cannot be loaded at
 *                                              ?{query_var}={post_slug}. If specified as a string, the query
 *                                              ?{query_var_string}={post_slug} will be valid.
 *     @type bool        $can_export            Whether to allow this post type to be exported. Default true.
 *     @type bool        $delete_with_user      Whether to delete posts of this type when deleting a user. If true,
 *                                              posts of this type belonging to the user will be moved to trash
 *                                              when then user is deleted. If false, posts of this type belonging
 *                                              to the user will *not* be trashed or deleted. If not set (the default),
 *                                              posts are trashed if post_type_supports('author'). Otherwise posts
 *                                              are not trashed or deleted. Default null.
 *     @type bool        $_builtin              FOR INTERNAL USE ONLY! True if this post type is a native or
 *                                              "built-in" post_type. Default false.
 *     @type string      $_edit_link            FOR INTERNAL USE ONLY! URL segment to use for edit link of
 *                                              this post type. Default 'post.php?post=%d'.
 * }
 * @return WP_Post_Type|WP_Error The registered post type object, or an error object.
 */
function csl_add_cpt($singular_name, $plural_name, $args = []) {
    $default_args = array(
        "labels" => array(
            "name" => $plural_name,
            "singular_name" => $singular_name,
        ),
        "description" => "",
        "public" => true,
        "show_ui" => true,
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "query_var" => true,
        "supports" => array("title", "editor"),
    );
    $real_args = array_merge( $default_args, $args);
    $post_type = sanitize_title($singular_name);
    register_post_type($post_type, $real_args);
}

//CREATE DATA BASE
add_action( 'init', 'csl_create_db' );
function csl_create_db() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    global $csl_db_version;
    $charset_collate = $wpdb-> get_charset_collate();
    $table_story = $prefix . 'csl_story_reviews';
    $table_judge = $prefix . 'csl_judge_requests';
    $sql = [];
    if (get_option('csl_db_version')!= $csl_db_version) {
        $sql[] = "CREATE TABLE $table_story (
            ID bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
            story_id int(11) DEFAULT NULL,
            judge_id int(11) DEFAULT NULL,
            answer_1 smallint(11) DEFAULT NULL,
            answer_2 smallint(11) DEFAULT NULL,
            answer_3 smallint(11) DEFAULT NULL,
            answer_4 smallint(11) DEFAULT NULL,
            comment text DEFAULT NULL,
            status tinyint(1) DEFAULT NULL,
            in_progress tinyint(1) DEFAULT NULL,
            recused tinyint(1) DEFAULT NULL,
            recused_handled tinyint(1) DEFAULT NULL,
            date_recused varchar(400) DEFAULT NULL,
            sent tinyint(1) DEFAULT 0,
            created datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Created Datetime',
            updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated Datetime',
            PRIMARY KEY  (ID)
        ) $charset_collate;";

            $sql[] = "CREATE TABLE $table_judge (
            ID bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
            judge_id int(11) DEFAULT NULL,
            story_requests int(11) DEFAULT NULL,
            request_date varchar(400) DEFAULT NULL,
            request_status tinyint(1) DEFAULT NULL,
            created datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Created Datetime',
            updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Updated Datetime',
            PRIMARY KEY  (ID)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        add_option('csl_db_version', $csl_db_version);
    }
}

//Create required pages
add_action( 'after_switch_theme', 'csl_create_pages' );
function csl_create_pages() {
    if ( ! get_option('fof_pages_created') ){
        wp_insert_post( array(
            'post_title'    => wp_strip_all_tags( 'Review Story' ),
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_author'   => 1,
            'page_template' => 'templates/dashboard-single-history.php'
        ));
        wp_insert_post( array(
            'post_title'    => wp_strip_all_tags( 'Dashboard Admin' ),
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_author'   => 1,
            'page_template' => 'templates/dashboard.php'
        ));
        wp_insert_post( array(
            'post_title'    => wp_strip_all_tags( 'Dashboard Judge' ),
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_author'   => 1,
            'page_template' => 'templates/dashboard-judge.php'
        ));
        wp_insert_post( array(
            'post_title'    => wp_strip_all_tags( 'Login' ),
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_author'   => 1,
            'page_template' => 'templates/login.php'
        ));
        add_option('fof_pages_created', true);
    }
}

add_action('init','csl_add_custom_post_types');
function csl_add_custom_post_types(){
    csl_add_cpt("Story","Stories", array(
        "menu_icon" => "dashicons-media-text",
    ));
    csl_add_cpt("Fact","Facts", array(
        "menu_icon" => "dashicons-welcome-add-page",
    ));
}



add_action( 'wp_ajax_nopriv_available_judges', 'available_judges' );
add_action( 'wp_ajax_available_judges', 'available_judges' );

function available_judges() {
    global $wpdb;
    $story = $_POST['story'];
    $judges = $wpdb->get_results(
        "
        SELECT r.judge_id, count(case when r.status = 0 and r.recused=0 then 1 else null end) as unreviewed
        FROM wp_csl_story_reviews r
        GROUP BY r.judge_id
        HAVING r.judge_id NOT IN (
           SELECT s.judge_id
           FROM wp_csl_story_reviews s
           WHERE s.story_id = $story
        )
        "
    ); ?>
    <option value="">Select a judge</option>
    <?php foreach ($judges as $judge_item) {
        $judge = get_user_by('id', $judge_item->judge_id); ?>
        <option value="<?php echo $judge_item->judge_id ?>"><?php echo $judge->user_firstname . ' ' . $judge->user_lastname . ' (' . $judge_item->unreviewed . ' unreviewed)'?></option>
    <?php }
    wp_die();
}

add_action( 'wp_ajax_nopriv_available_judges_for_judgeid', 'available_judges_for_judgeid' );
add_action( 'wp_ajax_available_judges_for_judgeid', 'available_judges_for_judgeid' );

function available_judges_for_judgeid() {
    global $wpdb;
    $judgeid = $_POST['judge'];
    $judges = $wpdb->get_results( $wpdb->prepare(
        "
        SELECT r.judge_id, count(case when r.status = 0 and r.recused=0 and r.in_progress=0 then 1 else null end) as unreviewed
        FROM wp_csl_story_reviews r
        WHERE r.story_id NOT IN (
           SELECT s.story_id
           FROM wp_csl_story_reviews s
           WHERE s.judge_id = %d
        )
        GROUP BY r.judge_id
        "
    ,$judgeid));
    error_log ( "Available Judges: " . print_r($wpdb->last_query, true));
    foreach ($judges as $judge_item) {
        $judge = get_user_by('id', $judge_item->judge_id); ?>
        <option value="<?php echo $judge_item->judge_id ?>"><?php echo $judge->user_firstname . ' ' . $judge->user_lastname . ' (' . $judge_item->unreviewed . ' unreviewed)'?></option>
    <?php }
    wp_die();
}

add_action( 'wp_ajax_nopriv_reassign_stories_from_other_judges', 'reassign_stories_from_other_judges' );
add_action( 'wp_ajax_reassign_stories_from_other_judges', 'reassign_stories_from_other_judges' );

function reassign_stories_from_other_judges() {
    global $wpdb;
    $judgeid = intval($_POST['target_judge']);
    $reassign_judges = $_POST['reassign_judges'];
    $number_of_stories = intval($_POST['number_of_stories']);
    $row = $_POST['row'];

    $judges_sql_params = implode(',',array_fill(0,count($reassign_judges),'%d')); // %d,%d,%d,%d

    $stories = $wpdb->get_results( $wpdb->prepare(
        "
        SELECT r.judge_id, r.ID, r.story_id
        FROM {$wpdb->prefix}csl_story_reviews r
        WHERE r.story_id NOT IN (
           SELECT s.story_id
           FROM wp_csl_story_reviews s
           WHERE s.judge_id = %d OR r.status != 0 OR r.recused != 0 OR  r.in_progress != 0
        ) and r.judge_id IN ($judges_sql_params)
        ORDER BY r.judge_id
        "
        ,array_merge([$judgeid], $reassign_judges)
    ));

    error_log ( "Stories to reassign: " . print_r($wpdb->last_query, true));

    $stories_to_reassign = [];
    $previous_judge = false;
    $judge_unassigned_count = 0;
    foreach ($stories as $story) {
        if ($previous_judge !== $story->judge_id){
            $previous_judge = $story->judge_id;
            $judge_unassigned_count = 0;
        }
        if ($judge_unassigned_count < $number_of_stories && !isset($stories_to_reassign[$story->story_id])) {
            $stories_to_reassign[$story->story_id] = $story->ID;
            $judge_unassigned_count++;
        } else {
            continue;
        }
    }
    $stories_to_reassign = array_values($stories_to_reassign); //cleanup $story_id keys
    $stories_sql_param = implode(',',array_fill(0,count($stories_to_reassign),'%d'));
    $result = $wpdb->query($wpdb->prepare(
        "
        UPDATE {$wpdb->prefix}csl_story_reviews
        SET judge_id = %d, sent = 0
        WHERE ID IN ($stories_sql_param)
        "
    ,array_merge([$judgeid],$stories_to_reassign)));
    error_log ( "Update reassigned stories: " . print_r($wpdb->last_query, true));
    $row_updated = $wpdb->update(
        "{$wpdb->prefix}csl_judge_requests"
        , [ "request_status" => 1 ]
        , [ "ID" => $row ]
    );
    $send_notification = notify_judge($judgeid);

    wp_send_json([
       'status' => ($row_updated !== false && $result !== false) ? 'success' : 'error',
        'rows' => $result,
        'updated_request'=> $row_updated,
        'email_notification' => $send_notification
    ]);
    wp_die();
}
function notify_judge($judgeid){
    $judge_info = get_userdata($judgeid);
    $to = $judge_info->user_email;
    $sitename = get_bloginfo('name');
    $subject = "[$sitename] Your request for more stories was processed.";
    $message = "
        <p>Hello,</p>
        <br/>
        <p>$sitename processed your request for more stories. Please go to your dashboard and see the new stories for review.</p>
    ";
    $headers = array('Content-Type: text/html; charset=UTF-8');
    return wp_mail($to, $subject, $message, $headers);
}

add_action( 'wp_ajax_nopriv_get_recused_stories', 'get_recused_stories' );
add_action( 'wp_ajax_get_recused_stories', 'get_recused_stories' );

function get_recused_stories() {
    global $wpdb;
    $judge = $_POST['judge'];
    $recused_stories = $wpdb->get_results(
        "
        SELECT count(*) as recused_stories
        FROM wp_csl_story_reviews r
        WHERE r.story_id
        NOT IN (
            SELECT r.story_id
            FROM wp_csl_story_reviews r
            WHERE r.judge_id = $judge
        )
        AND r.recused = 1 AND r.recused_handled = 0
        "
    );
    echo $recused_stories[0]->recused_stories;
    wp_die();
}

add_action( 'wp_ajax_nopriv_reassign_recused_story_to_judge', 'reassign_recused_story_to_judge' );
add_action( 'wp_ajax_reassign_recused_story_to_judge', 'reassign_recused_story_to_judge' );

function reassign_recused_story_to_judge() {
    global $wpdb;
    $judge = $_POST['judge'];
    $recuses = $_POST['recuses'];
    $row = $_POST['row'];
    $prefix = $wpdb->prefix;
    $table_name = $prefix . "csl_story_reviews";
    $inserted_id = 0;
    $recused_stories = $wpdb->get_results(
        "
        SELECT *
        FROM wp_csl_story_reviews r
        WHERE r.story_id
        NOT IN (
            SELECT r.story_id
            FROM wp_csl_story_reviews r
            WHERE r.judge_id = $judge
        )
        AND r.recused = 1 AND r.recused_handled = 0
        "
    );
    for ($i = 0; $i < $recuses; $i++) {
        $data = [
            'story_id' => $recused_stories[$i]->story_id,
            'judge_id'=> $judge,
            'answer_1' => NULL,
            'answer_2' => NULL,
            'answer_3' => NULL,
            'answer_4' => NULL,
            'comment' => '',
            'status' => '0',
            'in_progress' => '0',
            'recused' => '0',
            'recused_handled' => '0',
            'date_recused' => '',
            'sent' => '0',
            'created' => date_i18n( 'Y-m-d H:i:s' )
        ];
        if ( is_int( $_id = $wpdb->insert( $table_name, $data ) ) ) {
            $inserted_id++;
            $status = [ "recused_handled" => 1 ];
            $where_clause = [ "ID" => $recused_stories[$i]->ID ];
            if ( !$wpdb->update( $table_name, $status, $where_clause ) ) {
                echo "false";
                return false;
            }

        }
        else {
            echo "false";
            return false;
        }
    }
    if ($inserted_id==$recuses) {
        $table_name = $prefix . "csl_judge_requests";
        $status = [ "request_status" => 1 ];
        $where_clause = [ "ID" => $row ];
        if ( $wpdb->update( $table_name, $status, $where_clause ) ) {
            echo "success";
        }
    }
    wp_die();
}

add_action( 'wp_ajax_nopriv_story_requests', 'story_requests' );
add_action( 'wp_ajax_story_requests', 'story_requests' );

function story_requests() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix . "csl_judge_requests";
    $judge = $_POST['judge'];
    $story_requests = $_POST['story_requests'];
    $data = [
        "judge_id" => $judge,
        "story_requests" => $story_requests,
        "request_date" => time(),
        "request_status" => 0,
        "created" => date_i18n( 'Y-m-d H:i:s' )
    ];
    if ( is_int( $_id = $wpdb->insert( $table_name, $data) ) ) {
        echo "success";
    }
    else {
        echo "false";
    }
    wp_die();
}
add_action( 'wp_ajax_nopriv_reassign_recused_story', 'reassign_recused_story' );
add_action( 'wp_ajax_reassign_recused_story', 'reassign_recused_story' );

function reassign_recused_story() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix . "csl_story_reviews";
    $judge = $_POST['judge'];
    $story = $_POST['story'];
    $rowID = $_POST['row'];
    $data = [
        'story_id' => $story,
        'judge_id'=> $judge,
        'answer_1' => NULL,
        'answer_2' => NULL,
        'answer_3' => NULL,
        'answer_4' => NULL,
        'comment' => '',
        'status' => '0',
        'in_progress' => '0',
        'recused' => '0',
        'recused_handled' => '0',
        'date_recused' => '',
        'sent' => '0',
        'created' => date_i18n( 'Y-m-d H:i:s' )
    ];
    if ( is_int( $_id = $wpdb->insert( $table_name, $data) ) ) {
        $status = [ "recused_handled" => 1 ];
        $where_clause = [ "ID" => $rowID ];
        if ( $wpdb->update( $table_name, $status, $where_clause ) ) {
            echo "success";
        }
        else {
            echo "false";
        }
    }
    else {
        echo "false";
    }
    wp_die();
}

add_action( 'wp_ajax_nopriv_save_answer', 'save_answer' );
add_action( 'wp_ajax_save_answer', 'save_answer' );

function save_answer() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix . "csl_story_reviews";
    $review = $_POST['review'];
    $answer = $_POST['answer'];
    $value = $_POST['value'];
    $data = [ "answer_".$answer => $value, "in_progress" => 1 ];
    $where_clause = [ "ID" => $review ];
    if ( $wpdb->update( $table_name, $data, $where_clause ) ) {
        echo "The specified rows was updated.";
    }
    else {
        echo "false";
    }
    wp_die();
}

add_action( 'wp_ajax_nopriv_complete_review', 'complete_review' );
add_action( 'wp_ajax_complete_review', 'complete_review' );

function complete_review() {
    global $wpdb;
    $prefix = $wpdb->prefix;

    $table_name = $prefix . "csl_story_reviews";
    $review = $_POST['review'];
    $comment = $_POST['comment'];
    $answer1 = $_POST['answer1'];
    $answer2 = $_POST['answer2'];
    $answer3 = $_POST['answer3'];
    $answer4 = $_POST['answer4'];
    $data = [ "comment" => $comment, "answer_1" => $answer1, "answer_2" => $answer2, "answer_3" => $answer3, "answer_4" => $answer4, "in_progress" => 0, "status" => 1];
    $where_clause = [ "ID" => $review ];
    if ( false !== $wpdb->update( $table_name, $data, $where_clause ) ) {
        echo "The specified rows was updated.";
    }
    else {
        echo "false";
    }
    wp_die();
}

/**
 * SAVE FOR LATER
 */

add_action( 'wp_ajax_nopriv_save_for_later', 'save_for_later' );
add_action( 'wp_ajax_save_for_later', 'save_for_later' );
function save_for_later() {
    global $wpdb;
    $prefix = $wpdb->prefix;

    $table_name = $prefix . "csl_story_reviews";
    $review = $_POST['review'];
    $comment = $_POST['comment'];
    $answer1 = $_POST['answer1'];
    $answer2 = $_POST['answer2'];
    $answer3 = $_POST['answer3'];
    $answer4 = $_POST['answer4'];
    $completed = $wpdb->get_results( $wpdb->prepare(
        "
        SELECT count(*) as completed
        FROM wp_csl_story_reviews r
        WHERE r.status = 1 AND r.ID = %d
        "
        ,$review));
    if ($completed[0]->completed == "0") {
        $data = [ "comment" => $comment, "answer_1" => $answer1, "answer_2" => $answer2, "answer_3" => $answer3, "answer_4" => $answer4, "in_progress" => 1, "status" => 0];
    }
    else {
        $data = [ "comment" => $comment, "answer_1" => $answer1, "answer_2" => $answer2, "answer_3" => $answer3, "answer_4" => $answer4, "in_progress" => 0, "status" => 1];
    }

    $where_clause = [ "ID" => $review ];
    if ( false !== $wpdb->update( $table_name, $data, $where_clause ) ) {
        wp_send_json([
            'status' => 'success',
        ]);
    }
    else {
        wp_send_json([
            'status' => 'false',
            'completed' => $completed[0]->completed,
            'row' => $completed[0],
            'info' => $data
        ]);
    }
    wp_die();
}

/**
 * DOWNLOAD CSV
 */
add_action( 'wp_ajax_nopriv_download_csv', 'download_csv' );
add_action( 'wp_ajax_download_csv', 'download_csv' );
function download_csv() {
    global $wpdb;

    $upload_dir = wp_upload_dir();
    $csl_dirname = $upload_dir['basedir'].'/csl-temp';
    if ( ! file_exists( $csl_dirname ) ) {
        wp_mkdir_p( $csl_dirname );
    }
    $file_name = uniqid('export_fof_').'.csv';
    $full_name = $csl_dirname . '/'. $file_name;
    $file = fopen($full_name,"w");
    /**
     *
    story_problem_solve
     * story_biggest_obstacle
     * story_entrepeneurship_important
     *
     *
     */
    $query = $wpdb->get_results(
        "
        SELECT
            r.story_id as 'Story ID',
            u.display_name as 'Judge Name',
            p.post_title as 'Author Name',
            m9.meta_value as 'Author Gender',
            m10.meta_value as 'Author Ethnicity',
            m11.meta_value as 'Author Age',
            m7.meta_value as 'Author Email',
            m8.meta_value as 'Bussiness Name',
            m12.meta_value as 'Sector',
            m13.meta_value as 'Funding',
            m4.meta_value as 'City',
            m5.meta_value as 'State',
            m6.meta_value as 'Country',
            m1.meta_value as 'Answer 1',
            m2.meta_value as 'Answer 2',
            m3.meta_value as 'Answer 3',
            r.answer_1 as 'Score 1',
            r.answer_2 as 'Score 2',
            r.answer_3 as 'Score 3',
            r.answer_4 as 'Score 4',
            (r.answer_1 + r.answer_2 + r.answer_3 + r.answer_4)/4 as Average,
            r.comment as Comment
        FROM {$wpdb->prefix}csl_story_reviews r
        INNER JOIN {$wpdb->users} u
        ON r.judge_id = u.ID
        INNER JOIN {$wpdb->posts} p
        ON r.story_id = p.ID
        INNER JOIN {$wpdb->postmeta} m1
        ON m1.post_id = r.story_id AND m1.meta_key = 'story_problem_solve'
        INNER JOIN {$wpdb->postmeta} m2
        ON m2.post_id = r.story_id AND m2.meta_key = 'story_biggest_obstacle'
        INNER JOIN {$wpdb->postmeta} m3
        ON m3.post_id = r.story_id AND m3.meta_key = 'story_entrepeneurship_important'
        LEFT JOIN {$wpdb->postmeta} m4
        ON m4.post_id = r.story_id AND m4.meta_key = 'story_city'
        LEFT JOIN {$wpdb->postmeta} m5
        ON m5.post_id = r.story_id AND m5.meta_key = 'story_state'
        LEFT JOIN {$wpdb->postmeta} m6
        ON m6.post_id = r.story_id AND m6.meta_key = 'story_country'
        LEFT JOIN {$wpdb->postmeta} m7
        ON m7.post_id = r.story_id AND m7.meta_key = 'story_email'
        LEFT JOIN {$wpdb->postmeta} m8
        ON m8.post_id = r.story_id AND m8.meta_key = 'startup_name'
        LEFT JOIN {$wpdb->postmeta} m9
        ON m9.post_id = r.story_id AND m9.meta_key = 'story_gender'
        LEFT JOIN {$wpdb->postmeta} m10
        ON m10.post_id = r.story_id AND m10.meta_key = 'story_ethnicy'
        LEFT JOIN {$wpdb->postmeta} m11
        ON m11.post_id = r.story_id AND m11.meta_key = 'story_age'
        LEFT JOIN {$wpdb->postmeta} m12
        ON m12.post_id = r.story_id AND m12.meta_key = 'story_sector'
        LEFT JOIN {$wpdb->postmeta} m13
        ON m13.post_id = r.story_id AND m13.meta_key = 'story_funding'
        WHERE r.status= 1 AND r.recused != 1;
        ", ARRAY_A );
    $HeadingsArray=array();
    foreach($query[0] as $name => $value){
        $HeadingsArray[]=$name;
    }
    fputcsv($file,$HeadingsArray);
    foreach($query as $row){
        $valuesArray=array();
        foreach($row as $name => $value){
            $valuesArray[]=str_replace(['\\',"\n"],['',' '],$value);
        }
        fputcsv($file,$valuesArray);
    }
    fclose($file);
    wp_send_json([
        'status' => 'success',
        'query' => $query,
        'heading' => $HeadingsArray,
        'file' => get_bloginfo('url') . '/wp-content/uploads/csl-temp/' . $file_name
    ]);
    wp_die();
}

add_action( 'wp_ajax_nopriv_final_review_completed', 'final_review_completed' );
add_action( 'wp_ajax_final_review_completed', 'final_review_completed' );

function final_review_completed() {
    global $wpdb;
    $prefix = $wpdb->prefix;

    $table_name = $prefix . "csl_story_reviews";
    $judge = $_POST['judge'];
    $data = ["sent" => 1];
    $where_clause = [ "judge_id" => $judge ];
    $row_updated = $wpdb->update( $table_name, $data, $where_clause );
    wp_send_json([
        'status' => ($row_updated !== false) ? 'success' : 'error'
    ]);
    wp_die();
}

add_action( 'wp_ajax_nopriv_final_review_incomplete', 'final_review_incomplete' );
add_action( 'wp_ajax_final_review_incomplete', 'final_review_incomplete' );

function final_review_incomplete() {
    global $wpdb;
    $prefix = $wpdb->prefix;

    $table_name = $prefix . "csl_story_reviews";
    $judge = $_POST['judge'];
    $data_recused = ["in_progress" => 0, "recused" => 1, "date_recused" => time()];
    $where_clause_recused = [ "judge_id" => $judge, "status" => 0, "recused" => 0 ];
    $data = ["sent" => 1];
    $where_clause = [ "judge_id" => $judge ];
    $row_updated = $wpdb->update( $table_name, $data, $where_clause );
    $row_recused = $wpdb->update( $table_name, $data_recused, $where_clause_recused );

    wp_send_json([
        'status' => ($row_recused !== false && $row_updated !== false) ? 'success' : 'error',
        'recused' => $row_recused,
        'updated' => $row_updated
    ]);
    wp_die();
}

add_action( 'wp_ajax_nopriv_recuse_review', 'recuse_review' );
add_action( 'wp_ajax_recuse_review', 'recuse_review' );

function recuse_review() {
    global $wpdb;
    $prefix = $wpdb->prefix;

    $table_name = $prefix . "csl_story_reviews";
    $review = $_POST['review'];
    $data = [ "date_recused" => time(), "in_progress" => 0, "recused" => 1, "status" => 0];
    $where_clause = [ "ID" => $review ];
    if ( $wpdb->update( $table_name, $data, $where_clause ) ) {
        echo "The specified rows was updated.";
    }
    else {
        echo "false";
    }
    wp_die();
}

add_action( 'wp_ajax_nopriv_update_data', 'update_data' );
add_action( 'wp_ajax_update_data', 'update_data' );

function update_data() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix . "csl_story_reviews";
    $target = $_POST['target'];
    switch ($target) {
        case 'Judges':
            csl_update_judges();
            break;
        case 'Submissions':
            csl_update_submissions();
            break;
        case 'Requests':
            csl_update_requests();
            break;
        case 'Recuses':
            csl_update_recuses();
            break;
    }
    wp_die();
}

add_action( 'wp_ajax_nopriv_autoassing_stories', 'autoassing_stories' );
add_action( 'wp_ajax_autoassing_stories', 'autoassing_stories' );

function autoassing_stories() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix . "csl_story_reviews";
    $blogusers = get_users('role=judge');
    $stories = get_posts(array(
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
    ));
    $nostories = count($stories);
    $judges = count($blogusers);
    $reviews = intval($_POST['noreview']);
    $assigned = ($nostories/$judges)*$reviews;
    $count_judge = 0;
    $wpdb->query('START TRANSACTION');
    $success = true;
    for ($i = 0; $i < $reviews; $i++) {

        for ($x = 0; $x < $nostories; $x++) {

            if ($x >= (($assigned*($count_judge+1))-($nostories*$i))) {
                $count_judge++;
            }
            $data = [
                'story_id' => $stories[$x]->ID,
                'judge_id'=> $blogusers[$count_judge]->ID,
                'answer_1' => NULL,
                'answer_2' => NULL,
                'answer_3' => NULL,
                'answer_4' => NULL,
                'comment' => '',
                'status' => '0',
                'in_progress' => '0',
                'recused' => '0',
                'recused_handled' => '0',
                'date_recused' => '',
                'sent' => '0',
                'created' => date_i18n( 'Y-m-d H:i:s' )
            ];
            $result = $wpdb->insert( $table_name, $data );
            if (!$result) {
                $success = false;
                break;
            }
        }
    }
    if ($success) {
        $wpdb->query('COMMIT');
    } else {
        $wpdb->query('ROLLBACK');
    }

    csl_update_judges();
}

function csl_update_judges() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix . "csl_story_reviews";
    $judges = $wpdb->get_results(
        "
        SELECT r.judge_id, COUNT(*) as assigned, SUM(r.status) as completed, SUM(r.recused) as recused, SUM(r.in_progress) as in_progress
        FROM {$wpdb->prefix}csl_story_reviews r
        GROUP BY r.judge_id
        "
    );
    foreach ( $judges as $judge ) {
        $user = get_user_by('id', $judge->judge_id);
        $incomplete = $judge->assigned - $judge->completed - $judge->recused - $judge->in_progress;
        ?>
        <tr <?php if ($incomplete!=0) {echo 'class="incomplete-row"';} else {echo 'class="completed-row"';} ?>>
            <td><?php echo $user->user_firstname .' '. $user->user_lastname;?></td>
            <td><?php echo $judge->assigned; ?></td>
            <td><?php echo $judge->completed;?></td>
            <td><?php echo $judge->in_progress;?></td>
            <td><?php echo $incomplete; ?></td>
            <td><?php echo $judge->recused; ?></td>
            <td><?php if ($user->user_last_login!="") { echo date("M jS, g:ia", $user->user_last_login); } ?></td>
        </tr>
    <?php }
    wp_die();
}
function csl_update_submissions() {
    global $wpdb;
    $submission = $wpdb->get_results(
        "
        SELECT
        r.story_id,
        COUNT(*) as judges,
        SUM(r.status) as reviewed,
        (AVG(r.answer_1) + AVG(r.answer_2) + AVG(r.answer_3) + AVG(r.answer_4))/4 as average_score
        FROM {$wpdb->prefix}csl_story_reviews r
        WHERE r.ID
        NOT IN (
            SELECT r.ID
            FROM {$wpdb->prefix}csl_story_reviews r
            WHERE r.recused_handled = 1 AND r.recused = 1
        )
        GROUP BY r.story_id
        "
    );
    foreach ($submission as $sub_row){?>
        <tr <?php if ($sub_row->judges != $sub_row->reviewed) {echo 'class="incomplete-row"';} else {echo 'class="completed-row"';} ?>>
            <td><?php the_field('story_full_name', $sub_row->story_id) ?></td>
            <td><?php echo $sub_row->judges ?></td>
            <td><?php echo $sub_row->reviewed ?></td>
            <td><?php echo round($sub_row->average_score, 2); ?></td>
        </tr>
    <?php }
    wp_die();
}
function csl_update_requests() {
    global $wpdb;
    $requests = $wpdb->get_results(
        "
        SELECT *
        FROM (SELECT
        r.judge_id,
        COUNT(*) as assigned,
        SUM(r.status) as completed,
        SUM(r.recused) as recused
        FROM {$wpdb->prefix}csl_story_reviews r
        GROUP BY r.judge_id ) as s INNER JOIN {$wpdb->prefix}csl_judge_requests req ON s.judge_id = req.judge_id;
                        "
    );
    foreach ($requests as $req_row) {
        $judge = get_user_by('id', $req_row->judge_id);
        $incomplete = $req_row->assigned - $req_row->completed - $req_row->recused;
        ?>
        <tr data-id="<?php echo $req_row->ID ?>" data-requests="<?php echo $req_row->story_requests ?>" data-judgeid="<?php echo $req_row->judge_id ?>" data-judge="<?php echo $judge->user_firstname . ' ' . $judge->user_lastname; ?>" class="reassign-row <?php if ($req_row->request_status == 0) {echo 'incomplete-row';} else {echo 'completed-row';} ?>">
            <td><?php echo $judge->user_firstname . ' ' . $judge->user_lastname; ?></td>
            <td><?php echo $judge->user_email ?></td>
            <td><?php echo $req_row->completed ?></td>
            <td><?php echo $incomplete ?></td>
            <td><?php echo $req_row->recused ?></td>
            <td><?php echo $req_row->assigned ?></td>
            <td><?php echo $req_row->story_requests ?></td>
        </tr>
    <?php }
    wp_die();
}
function csl_update_recuses() {
    global $wpdb;
    $recused = $wpdb->get_results(
        "
        SELECT *
        FROM {$wpdb->prefix}csl_story_reviews
        WHERE recused = 1
        "
    );
    foreach ($recused as $recused_row) {
        $judge = get_user_by('id', $recused_row->judge_id);
        ?>
        <tr data-id="<?php echo $recused_row->ID; ?>" data-judge="<?php echo $judge->user_firstname . " " . $judge->user_lastname ?>" data-judgeid="<?php echo $recused_row->judge_id ?>" data-author="<?php the_field('story_full_name', $recused_row->story_id); ?>" data-storyid="<?php echo $recused_row->story_id; ?>" class="recused-row <?php if ($recused_row->recused_handled != 1) {echo 'incomplete-row';} else {echo 'completed-row';} ?>" >
            <td><?php echo $judge->user_firstname . " " . $judge->user_lastname ?></td>
            <td><?php the_field('story_full_name', $recused_row->story_id); ?></td>
            <td><?php echo date("M jS, g:ia", $recused_row->date_recused); ?></td>
        </tr>
    <?php }
    wp_die();
}
