<?php

add_action( 'wp_enqueue_scripts', 'csl_social_share_enqueue_scripts' );
function csl_social_share_enqueue_scripts() {

	wp_enqueue_script( 'csl_ss', get_template_directory_uri() . '/js/cslsocialshare.js', array('jquery'), '1.8.8' );

	wp_localize_script( 'csl_ss', 'cslss', array(
		'ajax_url' => admin_url( 'admin-ajax.php' )
	));
}

add_action( 'wp_ajax_nopriv_twitter_auth', 'twitter_auth' );
add_action( 'wp_ajax_twitter_auth', 'twitter_auth' );

function twitter_auth()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
	require_once __DIR__ . '/vendor/codebird.php';
    $tw_key = get_field('twitter_consumer_key', 'options');
    $tw_secret = get_field('twitter_consumer_secret', 'options');
	\Codebird\Codebird::setConsumerKey($tw_key, $tw_secret);
    $cb = \Codebird\Codebird::getInstance();

    if (!@$_SESSION['oauth_token']) {
        // get the request token
        $reply = $cb->oauth_requestToken([
            'oauth_callback' => add_query_arg(['from_twitter' => 1,'action'=> 'twitter_auth'],admin_url( 'admin-ajax.php' )),
        ]);

        // store the token
        $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
        $_SESSION['oauth_token'] = $reply->oauth_token;
        $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
        $_SESSION['oauth_verify'] = true;

        // redirect to auth website
        $auth_url = $cb->oauth_authorize();

        $response = [
            'status' => 'auth_required',
            'location' => $auth_url,
        ];
        if (!@$_GET['from_twitter']) :
            wp_send_json($response);
        else :
            header('Location: ' . $auth_url);
            wp_die();
        endif;

    } elseif (@$_GET['oauth_verifier'] && @$_SESSION['oauth_verify']) {
        // verify the token
        $cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        unset($_SESSION['oauth_verify']);


        // get the access token
        $reply = $cb->oauth_accessToken([
            'oauth_verifier' => $_GET['oauth_verifier'],
        ]);

        // store the token (which is different from the request token!)
        $_SESSION['oauth_token'] = $reply->oauth_token;
        $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
        header('Location:'. add_query_arg(['from_twitter' => @$_GET['from_twitter'],'action'=> 'twitter_auth'],admin_url( 'admin-ajax.php' )));
        wp_die();
    }


    if (@$_SESSION['oauth_token'] && @$_SESSION['oauth_token_secret']) {
        $cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        $user_info = $cb->account_verifyCredentials();
    } else {
        $user_info = null;
    }

    if ($user_info &&  $user_info->id) {
        $result = ['status' => 'success', 'user_info' => [
            'user_id' =>  $user_info->id,
            'user_name' =>  $user_info->screen_name,
            'profile_image_url' =>  $user_info->profile_image_url,
        ]];
    } else {
        $result = ['status' => 'error'];
        unset ($_SESSION['oauth_token'],$_SESSION['oauth_token_secret']);
    }
    if (!@$_GET['from_twitter']) :
        wp_send_json($result);
    else : ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Twitter Auth</title>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
            <script>
                var handleUserInfoParams = <?php echo json_encode(@$result['user_info'] ?: false); ?>;

                if(handleUserInfoParams){
                    window.opener.handleUserInfo(handleUserInfoParams);
                }
                else {
                    window.opener.errorManager({message: "Authentication error with twitter."});
                }
                window.close();
            </script>
        </head>
        <body>
        </body>
        </html>
        <?php
    endif;
    wp_die();
}

add_action( 'wp_ajax_nopriv_tweet_picture_auth', 'tweet_picture_auth' );
add_action( 'wp_ajax_tweet_picture_auth', 'tweet_picture_auth' );
function tweet_picture_auth(){


    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
	require_once __DIR__ . '/vendor/codebird.php';
    $tw_key = get_field('twitter_consumer_key', 'options');
    $tw_secret = get_field('twitter_consumer_secret', 'options');
    \Codebird\Codebird::setConsumerKey($tw_key, $tw_secret);
    $cb = \Codebird\Codebird::getInstance();

    if (!@$_SESSION['oauth_token']) {
        // get the request token
        $reply = $cb->oauth_requestToken([
            'oauth_callback' => add_query_arg(['from_twitter' => 1,'action'=> 'tweet_picture_auth'],admin_url( 'admin-ajax.php' )),
        ]);

        // store the token
        $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
        $_SESSION['oauth_token'] = $reply->oauth_token;
        $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
        $_SESSION['oauth_verify'] = true;

        $_SESSION['tweet'] = @$_REQUEST['tweet'];
        $_SESSION['tweet_image'] = @$_REQUEST['tweet_image'];

        // redirect to auth website
        $auth_url = $cb->oauth_authorize();

        $response = [
            'status' => 'auth_required',
            'location' => $auth_url,
        ];
        if (!@$_GET['from_twitter']) :
            wp_send_json($response);
        else :
            header('Location: ' . $auth_url);
            wp_die();
        endif;

    } elseif (@$_GET['oauth_verifier'] && @$_SESSION['oauth_verify']) {
        // verify the token
        $cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        unset($_SESSION['oauth_verify']);


        // get the access token
        $reply = $cb->oauth_accessToken([
            'oauth_verifier' => $_GET['oauth_verifier'],
        ]);

        // store the token (which is different from the request token!)
        $_SESSION['oauth_token'] = $reply->oauth_token;
        $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
        header('Location:'. add_query_arg(['from_twitter' => @$_GET['from_twitter'],'action'=> 'tweet_picture_auth'],admin_url( 'admin-ajax.php' )));
        wp_die();
    }


    if (@$_SESSION['oauth_token'] && @$_SESSION['oauth_token_secret']) {
        $cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        $user_info = $cb->account_verifyCredentials();

        if (@$_SESSION['tweet_image'] && @$_SESSION['tweet']) {
            $reply = $cb->media_upload(array(
                'media' => @$_SESSION['tweet_image']
            ));
            if(@$reply->media_id_string){
                // send Tweet with these medias
                $reply = $cb->statuses_update([
                  'status' => @$_SESSION['tweet'],
                  'media_ids' => $reply->media_id_string
                ]);

                if(@$reply->id){
                    $tweet_reply = $reply;
                } else {
                    $user_info = null;
                }
            } else {
                $user_info = null;
            }
            unset ($_SESSION['tweet_image'],$_SESSION['tweet']);
        } else {
            $user_info = null;
        }
    } else {
        $user_info = null;
    }

    if ($user_info &&  $user_info->id) {
        $result = ['status' => 'success', 'user_info' => [
            'user_id' =>  $user_info->id,
            'user_name' =>  $user_info->screen_name,
            'profile_image_url' =>  $user_info->profile_image_url,
        ]];

        if(isset($tweet_reply)){
            $result['user_info']['tweet_reply'] = $tweet_reply;
        }

    } else {
        $result = ['status' => 'error'];
        unset ($_SESSION['oauth_token'],$_SESSION['oauth_token_secret']);
    }
    if (!@$_GET['from_twitter']) :
        wp_send_json($result);
    else : ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Twitter Auth</title>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
            <script>
                var handleUserInfoParams = <?php echo json_encode(@$result['user_info'] ?: false); ?>;

                if(handleUserInfoParams){
                    window.opener.handleImageTweet(handleUserInfoParams);
                }
                else {
                    window.opener.errorManager({message: "Authentication error with twitter."});
                }
                window.close();
            </script>
        </head>
        <body>
        </body>
        </html>
        <?php
    endif;
    wp_die();
}


/* *******************************
 * Refresh Grid
 */
add_action( 'wp_ajax_nopriv_refresh_image', 'refresh_image' );
add_action( 'wp_ajax_refresh_image', 'refresh_image' );

function refresh_image()
{ ?>
    <div class="founders-grid">
        <?php

            $facts_params = array(
                'post_type' => 'fact',
                'post_status' => 'publish',
                'orderby'=>'menu_order date',
                'posts_per_page' => -1,
                'meta_query' => array(array('key' => '_thumbnail_id')),
                'paged' => 1
            );

            $facts_query = new WP_Query( $facts_params );
            $facts_total_count = $facts_query->post_count;

            $facts_params['posts_per_page'] = 4;
            $facts_query = new WP_Query( $facts_params );


        // STORIES
            $stories_params = array(
                'post_type' => 'story',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby'=>'menu_order date',
                'paged' => 1,
                'meta_query'    => array(
                    'relation'      => 'AND',
                    array(
                        'key'       => 'story_image',
                        'value'     => '',
                        'compare'   => '!='
                    )
                )
            );
            /*
             * GET COUNT BY DB QUERY TO IMPROVE PERFOMANCE
             */

            global $wpdb;

            $sql = "SELECT count(DISTINCT pm.post_id)
            FROM $wpdb->postmeta pm
            JOIN $wpdb->posts p ON (p.ID = pm.post_id)
            WHERE pm.meta_key = 'story_image'
            AND pm.meta_value <> ''
            AND p.post_type = 'story'
            AND p.post_status = 'publish'
            ";

            $count = $wpdb->get_var($sql);
            $stories_count = $count;

            /*
             * *****
             */

            $stories_params['posts_per_page'] = 36-$facts_query->post_count;
            $the_query = new WP_Query( $stories_params );

        $facts_count = 0;
        $total_posts = [];
        for($i=0,$j=0;$i<count($the_query->posts);$i){
            if(count($total_posts)>0 && (count($total_posts)+5)%9==0 && isset($facts_query->posts[$j])){
                $total_posts[] = $facts_query->posts[$j];
                $j++;
            } else {
                $total_posts[] = $the_query->posts[$i];
                $i++;
            }
        }

        ?>
        <?php if ( count($total_posts)>0 ) :
            $count=0;
            ?>
            <?php foreach($total_posts as $post_ob):
                global $post;
                $post = $post_ob;
                setup_postdata( $post );
                ?>

                <?php if ($image = get_field('story_image', $post_ob->ID)):$count++; ?>
                    <div class="founder grid-item" data-id="<?php echo $post_ob->ID;?>">
                        <?php echo wp_get_attachment_image( $image, 'story-medium', $post_ob->ID); ?>
                    </div>
                <?php elseif(has_post_thumbnail($post_ob->ID)):$count++;?>
                    <div class="founder grid-item">
                        <?php echo get_the_post_thumbnail($post_ob->ID, 'story-medium' ); ?>
                    </div>
                <?php endif ?>
            <?php endforeach; ?>

            <?php wp_reset_postdata(); ?>
        <?php endif; ?>





        ?>
    </div>
    <script>
        var facts_count = <?php echo $facts_total_count;?>;
        var stories_count = <?php echo $stories_count;?>;
    </script>
<?php
    wp_die();
 }
/* *****************************
* Load more images
* *****************************
*/
add_action( 'wp_ajax_nopriv_load_more_images', 'load_more_images' );
add_action( 'wp_ajax_load_more_images', 'load_more_images' );
function load_more_images()
{
    $paged = $_POST['paged'];

    // FACTS
    $facts_params = array(
        'post_type' => 'fact',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby'=>'menu_order date'
    );
    $facts_query = new WP_Query($facts_params);
    $total_facts_count = $facts_query->post_count;

    $facts_offset = 4*($paged-1) > $facts_query->post_count ? $facts_query->post_count : 4*($paged-1);

    $facts_params['posts_per_page'] = 4;
    $facts_params['paged'] = $paged;
    $facts_query = new WP_Query($facts_params);

    $stories_param = array(
            'post_type' => 'story',
            'posts_per_page' => 36-$facts_query->post_count,
            'post_status' => 'publish',
            'orderby'=>'menu_order date',
            'offset' => $facts_offset+36*($paged-1),
            'meta_query'    => array(
                'relation'      => 'AND',
                array(
                    'key'       => 'story_image',
                    'value'     => '',
                    'compare'   => '!='
                )
            )
        );

    $the_query = new WP_Query( $stories_param );

        $facts_count = 0;
        $total_posts = [];
        for($i=0,$j=0;$i<count($the_query->posts);$i){
            if(count($total_posts)>0 && (count($total_posts)+5)%9==0 && isset($facts_query->posts[$j])){
                $total_posts[] = $facts_query->posts[$j];
                $j++;
            } else {
                $total_posts[] = $the_query->posts[$i];
                $i++;
            }
        }


    if ( count($total_posts)>0 ) {
        $count=0;
        $images = array();
        foreach($total_posts as $post_ob){
            global $post;
            $post = $post_ob;
            setup_postdata( $post );


            if ($image = get_field('story_image', $post_ob->ID)) {
                $count++;
                $images[] = ['image'=>wp_get_attachment_image( $image, 'story-medium' ), 'id'=>$post_ob->ID];
            } else if(has_post_thumbnail($post_ob->ID)){
                $count++;
                $images[] = ['image'=>get_the_post_thumbnail($post_ob->ID, 'story-medium' )];

            }
        }


        echo json_encode(['success'=>true, 'images'=> $images, 'page_loaded'=>(int)$paged, 'count'=>$count]);
    }
    else {
        echo json_encode(['success'=>false, 'message ' => 'no posts']);
    }

    wp_die();
}
/* *****************************
* Generate Image
* *****************************
*/
add_action( 'wp_ajax_nopriv_generate_image', 'generate_image' );
add_action( 'wp_ajax_generate_image', 'generate_image' );
function generate_image()
{
    $filename = compositeImage();

    if ($filename) {
        $att_id = uploadImage($filename);
        $att_info = wp_get_attachment_image_src($att_id, 'story-preview');

        if(isset($_REQUEST['newsletter'])){
            if($_REQUEST['newsletter']=="true"){
                if(isset($_REQUEST['email'])){
                    $result = subscribeEmail($_REQUEST['email']);
                }
            }
        }
        if(!isset($_REQUEST['email']) || (isset($_REQUEST['email'])?empty($_REQUEST['email']):false)){
            $post_title = uniqid('image_');
        } else {
            $post_title = $_REQUEST['email'];
        }
                    // Create post object
                    $post_params = array(
                      'post_title'    => wp_strip_all_tags( $post_title ),
                      'post_content'  => '',
                      'post_status'   => 'publish',
                      'post_author'   => 1,
                      'post_type'     => 'story'
                    );

                    // Insert the post into the database
                    $post_id = wp_insert_post( $post_params );

                    if(isset($_REQUEST['email'])){
                        update_field('story_email', $_REQUEST['email'], $post_id);
                    }
                    update_field('story_image', $att_id , $post_id);

                    if(isset($_REQUEST['print_image'])){
                        if($_REQUEST['print_image']=="true"){
                            update_field('print_photo', 'yes' , $post_id);
                        }
                    }

        echo json_encode(['success'=>true, 'response'=> ['attachment_id'=>$att_id, 'image_url'=>$att_info[0], 'post_id'=>@$post_id], 'mailchimp'=>@$result]);
    }
    else {
        echo json_encode(['success'=>false, 'message'=> 'there was an error creating the image']);
    }

    wp_die();
}
function subscribeEmail($email){

        require_once dirname(__FILE__) . "/vendor/Mailchimp.php";
				require get_stylesheet_directory() . '/mailchimpconfig.php';

            $MailChimp = new MailChimp($mailchimp_api_key);


            $result = $MailChimp->call('lists/subscribe', array(
                'id'                => $mailchimp_list_id,
                'email'             => array('email'=>$email),
                'double_optin'      => false,
                'update_existing'   => true,
                'send_welcome'      => true
            ));

            return $result;
}
function compositeImage()
{
    $overlay_url = $_POST['overlayUrl'];
    $image_url = $_POST['imageUrl'];
    $source = $_POST['source'];


    $is_custom_text = @$_POST['is_custom_text'];
    $custom_text = stripslashes_deep( @$_POST['custom_text'] );
    $custom_text_font_size = @$_POST['custom_text_font_size'];

    $img_left_offset = @$_POST['img_left_offset'];
    $img_top_offset = @$_POST['img_top_offset'];

    if ('manual' == $source) {
        $cleanBase64 = str_replace("data:image/jpeg;base64,", "", $image_url);
        $cleanBase64 = str_replace("data:image/png;base64,", "", $cleanBase64);
        $cleanBase64 = str_replace("data:image/gif;base64,", "", $cleanBase64);

        $imageBlob = base64_decode($cleanBase64);
        $img = new \Imagick();
        $img->readImageBlob($imageBlob);

        $img->setImageFormat('jpg');
        $img->setCompressionQuality(100);

        switch ($img->getImageOrientation()) {
            case Imagick::ORIENTATION_TOPLEFT:
                break;
            case Imagick::ORIENTATION_TOPRIGHT:
                $img->flopImage();
                break;
            case Imagick::ORIENTATION_BOTTOMRIGHT:
                $img->rotateImage("#000", 180);
                break;
            case Imagick::ORIENTATION_BOTTOMLEFT:
                $img->flopImage();
                $img->rotateImage("#000", 180);
                break;
            case Imagick::ORIENTATION_LEFTTOP:
                $img->flopImage();
                $img->rotateImage("#000", -90);
                break;
            case Imagick::ORIENTATION_RIGHTTOP:
                $img->rotateImage("#000", 90);
                break;
            case Imagick::ORIENTATION_RIGHTBOTTOM:
                $img->flopImage();
                $img->rotateImage("#000", 90);
                break;
            case Imagick::ORIENTATION_LEFTBOTTOM:
                $img->rotateImage("#000", -90);
                break;
            default: // Invalid orientation
                break;
        }
        $img->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);

        $overlayHandle = fopen($overlay_url, 'rb');
        $overlay = new \Imagick();
        $overlay->readImageFile($overlayHandle);

        $imageWidth = $img->getImageWidth();
        $imageHeight = $img->getImageHeight();
        if ($imageWidth > $imageHeight) {
            /*$startX = ($imageWidth - $imageHeight)/2;*/
            $startX = !empty($img_left_offset)? ((-$img_left_offset/415)*$imageHeight) : (($imageWidth - $imageHeight)/2);
            $img->cropImage($imageHeight, $imageHeight, $startX, 0);

        }
        elseif ($imageWidth < $imageHeight) {
            //$startY = ($imageHeight - $imageWidth)/2;
            $startY = !empty($img_top_offset)? ((-$img_top_offset/415)*$imageWidth) : (($imageHeight - $imageWidth)/2);
            $img->cropImage($imageWidth, $imageWidth, 0, $startY);
        }

        $img->resizeimage(
            $overlay->getImageWidth(),
            $overlay->getImageHeight(),
            \Imagick::FILTER_LANCZOS,
            1
        );
        $img->compositeImage($overlay, \Imagick::COMPOSITE_ATOP, 0, 0);

        if($is_custom_text=='1'){
                // GENERATE TEXT
                $text_img = new \Imagick();
                $text_img->newPseudoImage($overlay->getImageWidth(), $overlay->getImageHeight()*(125/415), "gradient:rgba(0,0,0,0)-rgba(0,0,0,0)");

                $draw = new \ImagickDraw();
                $draw->setFillColor('rgb(255, 255, 255)');
                $draw->setFontSize($overlay->getImageHeight()*($custom_text_font_size/415));
                $draw->setGravity(\Imagick::GRAVITY_CENTER);
                $custom_text = str_replace('\n', "\n", $custom_text);

                // NORMALIZE TEXT TO AVOID VERY LONG TEXT WITHOUT NEWSPACES

                $font_analysis = $text_img->queryFontMetrics($draw, $custom_text);
                $words = explode(' ', $custom_text);
                $normalized_custom_text = "";
                foreach($words as $word){
                    $separation = $normalized_custom_text!=''?' ':'';
                    $font_analysis = $text_img->queryFontMetrics($draw, $normalized_custom_text.$separation.$word);

                    if($font_analysis['textWidth']<$overlay->getImageWidth()){
                        $normalized_custom_text.=$separation.$word;
                    } else {
                        $normalized_custom_text.="\n".$separation.$word;
                    }
                }

                $text_img->annotateimage($draw, 0, 0, 0, $normalized_custom_text);

                $img->compositeImage($text_img, \Imagick::COMPOSITE_ATOP, 0,$overlay->getImageHeight()*((415-135)/415) );
        }

        // IMAGE RESIZE FOR OPEN GRAPH
        /*
        $aspect_ratio = 1.9047619;
        $ogWidth = $overlay->getImageWidth()*$aspect_ratio;

        $rectangular_img =  clone $img;
        $rectangular_img->resizeimage(
                $ogWidth,
                $overlay->getImageWidth(),
                \Imagick::FILTER_LANCZOS,
                1
            );

        $img->resizeimage(
                $ogWidth,
                $ogWidth,
                \Imagick::FILTER_LANCZOS,
                1
            );

        $rectangular_img->compositeImage($img, \Imagick::COMPOSITE_ATOP,0,($overlay->getImageWidth()-$ogWidth)/2);
        $rectangular_img->blurImage(50,80);

        $img->resizeimage(
                $overlay->getImageWidth(),
                $overlay->getImageWidth(),
                \Imagick::FILTER_LANCZOS,
                1
            );
        $rectangular_img->compositeImage($img, \Imagick::COMPOSITE_ATOP, ($ogWidth-$overlay->getImageWidth())/2, 0);

        $img->resizeimage(
                $ogWidth, $overlay->getImageWidth(),
                \Imagick::FILTER_LANCZOS,
                1
            );
        $img->compositeImage($rectangular_img, \Imagick::COMPOSITE_ATOP, 0, 0);
        */
        $overlay->clear();
        $upload_dir = wp_upload_dir();
        $csl_dirname = $upload_dir['basedir'].'/csl-temp';
        if ( ! file_exists( $csl_dirname ) ) {
            wp_mkdir_p( $csl_dirname );
        }
        $filepath = $upload_dir['basedir'] . '/csl-temp/'.uniqid('fof_').'.jpg';
        file_put_contents( $filepath, $img->getImageBlob());
        return $filepath;
    }
    else {
        if (8388608 >= getFileSize($image_url)) {
            $nameid = $_POST['nameid'];


            $userHandle = fopen($image_url, 'rb');
            $img = new \Imagick();
            $img->readImageFile($userHandle);

            $img->setImageFormat('jpg');
            $img->setCompressionQuality(100);

            $overlayHandle = fopen($overlay_url, 'rb');
            $overlay = new \Imagick();
            $overlay->readImageFile($overlayHandle);

            $imageWidth = $img->getImageWidth();
            $imageHeight = $img->getImageHeight();
            if ($imageWidth > $imageHeight) {
                $startX = ($imageWidth - $imageHeight)/2;
                $img->cropImage($imageHeight, $imageHeight, $startX, 0);

            }
            elseif ($imageWidth < $imageHeight) {
                $startY = ($imageHeight - $imageWidth)/2;
                $img->cropImage($imageWidth, $imageWidth, 0, $startY);
            }

            $img->resizeimage(
                $overlay->getImageWidth(),
                $overlay->getImageHeight(),
                \Imagick::FILTER_LANCZOS,
                1
            );
            $img->compositeImage($overlay, \Imagick::COMPOSITE_ATOP, 0, 0);

            if($is_custom_text=='1'){
                // GENERATE TEXT
                $text_img = new \Imagick();
                $text_img->newPseudoImage($overlay->getImageWidth(), $overlay->getImageHeight()*(125/415), "gradient:rgba(0,0,0,0)-rgba(0,0,0,0)");

                $draw = new \ImagickDraw();
                $draw->setFillColor('rgb(255, 255, 255)');
                $draw->setFontSize($overlay->getImageHeight()*($custom_text_font_size/415));
                $draw->setGravity(\Imagick::GRAVITY_CENTER);
                $custom_text = str_replace('\n', "\n", $custom_text);

                // NORMALIZE TEXT TO AVOID VERY LONG TEXT WITHOUT NEWSPACES

                $font_analysis = $text_img->queryFontMetrics($draw, $custom_text);
                $words = explode(' ', $custom_text);
                $normalized_custom_text = "";
                foreach($words as $word){
                    $separation = $normalized_custom_text!=''?' ':'';
                    $font_analysis = $text_img->queryFontMetrics($draw, $normalized_custom_text.$separation.$word);

                    if($font_analysis['textWidth']<$overlay->getImageWidth()){
                        $normalized_custom_text.=$separation.$word;
                    } else {
                        $normalized_custom_text.="\n".$separation.$word;
                    }
                }

                $text_img->annotateimage($draw, 0, 0, 0, $normalized_custom_text);

                $img->compositeImage($text_img, \Imagick::COMPOSITE_ATOP, 0,$overlay->getImageHeight()*((415-135)/415) );
        }



            $overlay->clear();
            $upload_dir = wp_upload_dir();
            $csl_dirname = $upload_dir['basedir'].'/csl-temp';
            if ( ! file_exists( $csl_dirname ) ) {
                wp_mkdir_p( $csl_dirname );
            }
            $filepath = $upload_dir['basedir'] . '/csl-temp/' .uniqid('fof_'). '.jpg';
            file_put_contents( $filepath, $img->getImageBlob());
            return $filepath;
        }
        else {
            return false;
        }
    }
}
function uploadImage($filename)
{

    // Check the type of file. We'll use this as the 'post_mime_type'.
    $filetype = wp_check_filetype( basename( $filename ), null );

    // Get the path to the upload directory.
    $wp_upload_dir = wp_upload_dir();

    $relative = str_replace($wp_upload_dir['basedir'].DIRECTORY_SEPARATOR,'',$filename);

    $guid = wp_upload_dir()['baseurl'] . '/'
        . implode('/', array_map('rawurlencode', explode('/', dirname($relative))))
        . '/' . rawurlencode(basename($filename));
    // Prepare an array of post data for the attachment.
    $attachment = array(
        'guid'           => $guid,
        'post_mime_type' => $filetype['type'],
        'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
        'post_content'   => '',
        'post_status'    => 'publish'
    );

    // Insert the attachment.
    $attach_id = wp_insert_attachment( $attachment, $filename );

    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    // Generate the metadata for the attachment, and update the database record.
    $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
    wp_update_attachment_metadata( $attach_id, $attach_data );
    /*$files_to_delete  = [
        $filename,
        str_replace(".jpg", "-150x150.jpg", $filename),
        str_replace(".jpg", "-300x300.jpg", $filename),
        str_replace(".jpg", "-768x768.jpg", $filename),
        str_replace(".jpg", "-1024x1024.jpg", $filename)
    ];

    foreach ($files_to_delete as $file) {
        if (file_exists($file)) {
            unlink($file);
        }
    }*/

    return $attach_id;
}
function savePledgeImage ( $image , $filepath) {
    file_put_contents( $filepath, $image->getImageBlob());
}
function getFileSize($url){

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_NOBODY, TRUE);

    $data = curl_exec($ch);
    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

    curl_close($ch);
    return $size;
}



/**
 * Social Share function, generates a url for sharing
 */

function csl_set_social_shares_url($network_type, $link, $params = []) {
    switch ($network_type) {
        case 'facebook':
            $share_url = 'http://www.facebook.com/sharer.php?u=' . rawurlencode($link);
            break;
        case 'twitter':

            $share_url = 'http://twitter.com/intent/tweet/?' . http_build_query(array(
                    'url' =>$link,
                    'text' =>@$params['title'] ,
                ),null,'&',PHP_QUERY_RFC3986);
            break;
        case 'google-plus':
            $share_url = 'https://plus.google.com/share?url=' . rawurlencode($link);
            break;
        case 'linkedin' :
            $share_url = 'http://www.linkedin.com/shareArticle?mini=true&' . http_build_query(array(
                    'url' => $link,
                    'title' => @$params['title'],
                    'summary' => @$params['description'],
                    'source' => get_bloginfo('name'),
                ),null,'&',PHP_QUERY_RFC3986);
            break;
        case 'pinterest':
            $share_url = 'https://www.pinterest.com/pin/create/button/?' . http_build_query(array(
                    'description' => @$params['description'],
                    'media' => @$params['image_rl'],
                ),null,'&',PHP_QUERY_RFC3986);
            break;
        case 'email':
            $stripped_description = strip_tags (@$params['description']);
            $share_url = 'mailto:?' . http_build_query(array(
                    'Subject' =>  'Check this link from ' . get_bloginfo('name'),
                    'body' => @"{$params['title']} \r\n\r\n {$stripped_description} \r\n\r\nSource: {$link}",
                ),null,'&',PHP_QUERY_RFC3986);
            break;

    }

    return $share_url;
}



function get_tweets(){
	$judging_fields = inclusive_entrepreneurship_get_judging_fields();
    require_once __DIR__ . '/vendor/codebird.php';
    $tw_key = get_field('twitter_consumer_key', 'options');
    $tw_secret = get_field('twitter_consumer_secret', 'options');
    \Codebird\Codebird::setConsumerKey($tw_key, $tw_secret);

    $ACCESS_TOKEN = get_field('twitter_access_token', 'options');
    $ACCESS_TOKEN_SECRET = get_field('twitter_access_secret', 'options');

    $cb = \Codebird\Codebird::getInstance();
    $cb->setToken($ACCESS_TOKEN, $ACCESS_TOKEN_SECRET);

    //retrieve posts
    $q = get_field('twitter_query', 'options');
    //max_id

    //Make the REST call
    $data = $cb->search_tweets('q='.$q.'&count='.get_field('twitter_number', 'options').'&filter=images&include_entities=true&tweet_mode=extended&since_id='.get_field('twitter_latest_id', 'options'), true);

    update_field('twitter_timestamp', date('d/m/Y H:i:s'), 'options');

    $pulled_items = 0;
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $starttime = $mtime;

    if(count($data->statuses)>0){
        update_field('twitter_latest_id', $data->statuses[0]->id, 'options');

        foreach($data->statuses as $status){
            if(isset($status->entities->media)){
                if(count($status->entities->media) > 0){
                    $image_info = $status->entities->media[0];

                    $media_url = $image_info->media_url;

                    $fulltext = $status->full_text;
                    $status_id = $status->id;
                    $status_url = $status->full_text;

                    $user = $status->user;

                    $screen_name = $user->screen_name;
                    $full_name = @$user->name;


                    $post_params = array(
                      'post_title'    => wp_strip_all_tags( !empty($full_name)?$full_name:$screen_name ),
                      'post_content'  => $fulltext,
                      'post_status'   => 'pending',
                      'post_author'   => 1,
                      'post_type'     => 'story'
                    );

                    // Insert the post into the database
                    $post_id = wp_insert_post( $post_params );

                    wp_set_post_terms($post_id, array(3), 'story_category');

                    if(!empty($full_name)){
                        update_field('story_full_name', $full_name, $post_id);
                    }
                    update_field($judging_fields[0]['field_name'], $fulltext, $post_id);

                    if(get_field('twitter_default_filter', 'options')){
                        $_POST['overlayUrl'] = get_field('twitter_default_filter', 'options');
                    }

                    $image = file_get_contents($media_url);
                    $_POST['imageUrl'] = base64_encode($image);
                    $_POST['source'] = 'manual';

                    $filename = compositeImage();

                    if ($filename) {
                        $att_id = uploadImage($filename);
                        $att_info = wp_get_attachment_image_src($att_id, 'full');

                        update_field('story_image', $att_id , $post_id);
                    }


                    $pulled_items++;
                }

            }
        }
    }

    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $endtime = $mtime;
    $totaltime = ($endtime - $starttime);

    update_field('twitter_pull_duration', sprintf('%02d:%02d:%02d', ($totaltime/3600),($totaltime/60%60), $totaltime%60), 'options');
    update_field('twitter_number_pulled', $pulled_items, 'options');

    wp_die();
}
function ie_cron_schedules($schedules){
    if(!isset($schedules["5min"])){
        $schedules["5min"] = array(
            'interval' => 5*60,
            'display' => __('Once every 5 minutes'));
    }
    return $schedules;
}
add_filter('cron_schedules','ie_cron_schedules');

if (! wp_next_scheduled ( 'get_tweets_cron' )) {
    wp_schedule_event(time(),'5min', 'get_tweets_cron');
}
add_action('get_tweets_cron', 'get_tweets');

/**
 * Admin columns for Stories Page.
 */

// GET IMAGE
function CSL_get_featured_image($post_ID) {
    $col_image = get_field('story_image', $post_ID);
    if ($col_image) {
        return  wp_get_attachment_image( $col_image, 'thumbnail', "", array( "style" => 'max-width: 100%; height: auto;' ) );
    }
}
// ADD NEW COLUMN
function CSL_columns_head($defaults) {
    $defaults['story_image'] = 'Image';
    $defaults['menu_order'] = 'Order';

    return $defaults;
}
// SHOW THE FEATURED IMAGE
function CSL_columns_content($column_name, $post_ID) {
    if ($column_name == 'story_image') {
        $story_image = CSL_get_featured_image($post_ID);
        if ($story_image) {
            echo $story_image;
        }
    }
    elseif ($column_name == 'menu_order') {
        echo get_post_field( 'menu_order', $post_ID);
    }
}
function CSL_columns_sorting($columns) {
    $columns['menu_order'] = 'menu_order';
    return $columns;
}

add_filter('manage_posts_columns', 'CSL_columns_head');
add_action('manage_posts_custom_column', 'CSL_columns_content', 10, 2);
add_filter('manage_edit-story_sortable_columns', 'CSL_columns_sorting');
