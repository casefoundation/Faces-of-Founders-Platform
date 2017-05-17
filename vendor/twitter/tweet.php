<?php

/**
* update user status
* https://dev.twitter.com/rest/public/uploading-media-multiple-photos
*/

session_start();

include_once 'hybridauth/Hybrid/Endpoint.php';
include_once 'hybridauth/Hybrid/Auth.php';

$config = "hybridauth/config.php";
try{
    if (isset($_REQUEST['hauth_start']) || isset($_REQUEST['hauth_done']))
        {
            Hybrid_Endpoint::process();

        } else if(
                isset($_REQUEST['text']) && isset($_REQUEST['image']) ||
                isset($_SESSION['text']) && isset($_SESSION['image'])
                ) {
            if(isset($_REQUEST['text']) && isset($_REQUEST['image'])){
                $_SESSION['text'] = $_REQUEST['text'];
                $_SESSION['tweet_img'] = $_REQUEST['image'];
            }

            $text = $_SESSION['text'];
            $image = '../../'.urldecode($_SESSION['tweet_img']);

            $hybridauth = new Hybrid_Auth( $config );

            $twitter = $hybridauth->authenticate( "Twitter" );

            $twitter_status = array(
                "message" => $text,
                "image_path" => $image
            );
            $res = $twitter->setUserStatus( $twitter_status );

            ?>
            <script>
                 opener.successTweet();
                 window.close();
            </script>
            <?php
        }
   }
   catch( Exception $e ){
       ?>
            <script>
                 opener.failedTweet('<?php echo $e->getMessage();?>');
            </script>
            <?php
       $twitter->logout();
   }
