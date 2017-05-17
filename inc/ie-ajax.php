<?php
add_action('wp_ajax_create_story', 'ajax_create_story');
add_action('wp_ajax_nopriv_create_story', 'ajax_create_story');

function ajax_create_story(){
  $judging_fields = inclusive_entrepreneurship_get_judging_fields();

    $map_fields = [
        'full-name'                     => 'story_full_name',
        'email'                         => 'story_email',
        'your-business'                 => $judging_fields[0]['field_name'],
        'biggest-obstacle'              => $judging_fields[1]['field_name'],
        'diversifying-entrepreneurship' => $judging_fields[2]['field_name'],
        'print_photo'                   => 'print_photo'
    ];

    if( !isset($_REQUEST['post_id']) ||
        !(get_post_type(@$_REQUEST['post_id'])=='story')
    ){
        // Create post object
        $post_params = array(
          'post_title'    => wp_strip_all_tags( $_REQUEST['full-name'] ),
          'post_content'  => '',
          'post_status'   => 'publish',
          'post_author'   => 1,
          'post_type'     => 'story'
        );

        // Insert the post into the database
        $post_id = wp_insert_post( $post_params );
    } else {
        $post_id = $_REQUEST['post_id'];

         $my_post = array(
            'ID'           => $post_id,
            'post_title'   => wp_strip_all_tags( $_REQUEST['full-name'] ),
        );
        wp_update_post( $my_post );
    }

        foreach($map_fields as $valid_value => $field_key){
            if(isset($_REQUEST[$valid_value])) {
                update_field($field_key, $_REQUEST[$valid_value], $post_id);
            }
        }
        if(isset($_REQUEST['attachment_id'])) {
            $attach_id = $_REQUEST['attachment_id'];
            update_field('story_image', $attach_id , $post_id);
        }

        echo json_encode(['success'=>true, 'response'=>['post_id'=>$post_id]]);

    wp_die();
}

add_action('wp_ajax_create_story_step_2', 'ajax_create_story_step_2');
add_action('wp_ajax_nopriv_create_story_step_2', 'ajax_create_story_step_2');

function ajax_create_story_step_2(){
    $allow_fields = [
        'startup_name',
        'story_city',
        'story_state',
        'story_country',
        'story_gender',
        'story_ethnicy',
        'story_age',
        'story_sector',
        'story_funding'
    ];

    if( !isset($_REQUEST['post_id']) ||
        !(get_post_type(@$_REQUEST['post_id'])=='story')
    ){
        echo json_encode(['success'=>false, 'message'=>'The parameter post_id is missing or invalid']);
    } else {
        $post_id = $_REQUEST['post_id'];

        foreach($allow_fields as $field_key){
            if(isset($_REQUEST[$field_key])) {
                update_field($field_key, $_REQUEST[$field_key], $post_id);
            }
        }
        echo json_encode(['success'=>true]);
    }

    wp_die();
}
