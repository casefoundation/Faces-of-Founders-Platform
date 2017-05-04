var scope = {};
var uploadSource;
var cslid = "manual";
var gridpaged = 1;
var stopGridAjax = false;
var fbImage;
var openURLInPopup = function(url, name, w, h) {
    if (typeof(w) == "undefined") {
        w = 575;
        h = 400;
    }
    if (typeof(h) == "undefined") {
        h = 400;
    }
    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url,  name || 'window' + Math.floor(Math.random() * 10000 + 1),
        'menubar=0,toolbar=0,status=0,scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    return newWindow;

}
function removeTwitterFilter() {
   jQuery('.founder-filters').slick('slickRemove',14);
   jQuery('.message-slider').slick('slickRemove',14);
}
jQuery( document ).on( 'click', '.upload-photo.tw', function(event) {
	event.preventDefault();
        var twitterWindow = openURLInPopup('about:blank', '_twitterAuth');
	jQuery.ajax({
		url : cslss.ajax_url,
		type : 'post',
		data : {
			action : 'twitter_auth'
		},
		success : function( response ) {
            
            switch (response.status) {
                case 'auth_required':
                    twitterWindow.location.href = response.location;
                    // Puts focus on the newWindow
                    if (window.focus) {
                        twitterWindow.focus();
                    }
                    break;
                case 'success':
                    handleUserInfo(response.user_info);
                    twitterWindow.close();
                    break;
                case 'error':
                default:
                    twitterWindow.close();
                    alert('An error occurred when authenticating with Twitter. Please try again');
                    break;
            }
		}, error: function (xhr, ajaxOptions, thrownError) {
            alert("There was an internal error. Please try again later." );
            twitterWindow.close();
        }
	});

});

jQuery( document ).on( 'submit', '#tweet_form', function(event) {
	event.preventDefault();
        var twitterWindow = openURLInPopup('about:blank', '_twitterAuth');
        
        var tweet_text = jQuery('#tweet_form [name="tweet_content"]').val();
        var tweet_image = jQuery('#tweet_form [name="tweet_image"]').val();
        
        jQuery('#tweet_form [type="submit"]').hide();
        jQuery('#tweet_form .processing').show();
        
	jQuery.ajax({
		url : cslss.ajax_url,
		type : 'post',
		data : {
			action : 'tweet_picture_auth',
                        tweet: tweet_text,
                        tweet_image: tweet_image
		},
		success : function( response ) {
            jQuery('#tweet_form [type="submit"], #tweet_form .processing').removeAttr('style');
            
            switch (response.status) {
                case 'auth_required':
                    twitterWindow.location.href = response.location;
                    // Puts focus on the newWindow
                    if (window.focus) {
                        twitterWindow.focus();
                    }
                    break;
                case 'success':
                    handleImageTweet(response.user_info);
                    twitterWindow.close();
                    break;
                case 'error':
                default:
                    twitterWindow.close();
                    alert('An error occurred when authenticating with Twitter. Please try again');
                    break;
            }
		}, error: function (xhr, ajaxOptions, thrownError) {
            alert("There was an internal error. Please try again later." );
            twitterWindow.close();
        }
	});

});

jQuery( document ).on( 'click', '.steps-container button.submit', function(event) {
    event.preventDefault();
    var userimage = jQuery('.user-image').attr('src');
    var overlay = jQuery('.message-slider label.slick-current input').val();
    
    
    var print_image = jQuery('.step-3 [name="print_photo"]').is(":checked");
    var newsletter = jQuery('.step-3 [name="sign_up_for_newsletter"]').is(":checked");
    var email = jQuery('.step-3 [name="email"]').val();
    var is_custom_text = jQuery('.founder-generated-photo [name="is_custom_text"]').val();
    var custom_text = jQuery('.founder-generated-photo [name="custom_text"]').val();
    var custom_text_font_size = jQuery('.founder-generated-photo [name="custom_text_font_size"]').val();
    
    var re = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;

    jQuery('.step-3 .error').removeClass('error');
    jQuery('.step-3 [name="email"]').attr('placeholder','Email address *');
    
    // IMAGE OFFSET CALCULATION
    var left_arr = jQuery('.image-imitation').css('left').split('px');
    var top_arr = jQuery('.image-imitation').css('top').split('px');
    var img_left_offset = left_arr[0];
    var img_top_offset = top_arr[0];
    
    if( re.test(email) && print_image){
    
        jQuery(this).addClass('generating').attr('disabled','disabled');

        jQuery.ajax({
            url : cslss.ajax_url,
            type : 'post',
            data : {
                action : 'generate_image',
                source : uploadSource,
                imageUrl : userimage,
                overlayUrl : overlay,
                nameid : cslid,
                print_image: print_image,
                newsletter: newsletter,
                email: email,
                is_custom_text: is_custom_text,
                custom_text: custom_text,
                custom_text_font_size: custom_text_font_size,
                img_left_offset: img_left_offset,
                img_top_offset: img_top_offset
            },
            success : function( response ) {
                var data_object = JSON.parse(response);

                jQuery('.steps-container button.submit').removeClass('generating').removeAttr('disabled');

                if(data_object.success) {
                    if(jQuery('.step-3 input[name="print_photo"]').is(":checked")){
                        jQuery('#create_story input[name="print_photo"]').val('yes');
                    }
                    jQuery('#create_story input[name="attachment_id"]').val(data_object.response.attachment_id);
                    jQuery('#create_story input[name="post_id"]').val(data_object.response.post_id);
                    jQuery('#create_story input[name="email"]').val(jQuery('.step-3 [name="email"]').val());
                    
                    jQuery('#create_story_step_2 input[name="post_id"]').val(data_object.response.post_id);

                    jQuery('.generator-box').addClass('generated');
                    refreshGrid();
                    jQuery('.share-face-of-founder img').data('id', data_object.response.attachment_id );
                    jQuery('.share-face-of-founder img, .tweet_image').attr('src', data_object.response.image_url);
                    jQuery('#tweet_form [name="tweet_image"]').val(data_object.response.image_url);
                    
                    fbImage = data_object.response.image_url;
                }
            }
        });
    
    } else {
        if(!re.test(email)){
            jQuery('.step-3 [name="email"]').addClass('error').val('').attr('placeholder','Email required');

        }
        if(!print_image){
            jQuery('.step-3 label[for="print"]').closest('.checkbox').addClass('error');
        }
    }
});

/* *****************************
 * Facebook Configuration
 * *****************************
 */
function login() {
  FB.login(function(response) {
    if (response.status === 'connected') {
        FB.api('/me', 'GET', {fields: 'first_name,last_name,name,id'}, function(response) {
        FB.api(
            "/"+response.id+"/picture?width=800&height=800",
            function (userImage) {
              if (userImage && !userImage.error) {
                jQuery(".founder-generated-photo .user-image").attr("src", userImage.data.url);
                controlStep(false, '.step-2');
                uploadSource = 'facebook';
                cslid = response.id;
              }
            }
        );
      });
      } else if (response.status === 'not_authorized') {
        
      } else {
        
      }
  });
}

jQuery( document ).on( 'click', '.upload-photo.fb', function(event) {
  event.preventDefault();
  login();
  removeTwitterFilter();
});
/***********************************
 * MAKE PHOTO PROFILE PICTURE
 */
var MakeProfilePicture = function (imageUrl, successCallback, deniedCallback, errorCallback){
    this.imageUrl = imageUrl;
    this.successCallback = successCallback;
    this.deniedCallback = deniedCallback;
    this.errorCallback = errorCallback;

    this.share();
};

MakeProfilePicture.prototype = {
    success : function ( data ) {
        if (typeof(this.successCallback) == 'function') {
            this.successCallback(data);
        }
    },
    denied : function ( response ) {
        if ( typeof(this.deniedCallback) == 'function') {
            this.deniedCallback(response);
        }
    },
    error : function ( response ) {
        if ( typeof(this.errorCallback)  == 'function') {
            this.errorCallback(response);
        } else {
            this.denied(response);
        }
    },
    share : function () {
        var self = this;
        FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
                self.checkForFbPermissions()
            } else {
                self.requestPublishActions();
            }
        });

    },
    checkForFbPermissions : function(dontRetry) {
        var self = this;
        var permissionsArray = ['publish_actions'];

        FB.api('/me/permissions', function(response) {
            if (!response || response.error) {
                self.error(response);
            } else {
                var fbPermissions = {};
                response.data.forEach(function(element){
                    fbPermissions[element.permission] = (element.status == 'granted');
                });
                var i;
                for (i = 0; i < permissionsArray.length; i++) {
                    if (true != fbPermissions[permissionsArray[i]]) {
                        console.log('Check Permissions denied');
                        console.log(response);
                        console.log(typeof(self.deniedCallback));
                        if (dontRetry) {
                            self.denied(response);
                        } else {
                            self.requestPublishActions();
                        }
                        return;
                    }
                }
                self.sharePhoto();
            }
        });
    },
    requestPublishActions : function ( ) {
        var self = this;
        FB.login(function(response) {
            if (response.status === 'connected') {
                // recheck permissions one more time to make sure that user authorized publish_actions
                // checkForFbPermissions calls sharePhoto if publish_actions is present
                self.checkForFbPermissions(true);
            } else  {
                self.denied(response);
            }
        }, {scope: 'publish_actions', auth_type : 'rerequest'});
    },
    sharePhoto : function () {
        var self = this;
        FB.api('/me/photos', 'post', {url: self.imageUrl, published: false}, function(response) {
            if (!response || response.error) {
                self.error(response);
            } else {
                self.success('https://m.facebook.com/photo.php?fbid='+response.id+'&prof=1');
            }
        });
    }
};
jQuery(document).on('click', '.profile-picture.make', function (event) {
    event.preventDefault();
    var makeProfilePicPopup = openURLInPopup('about:blank', '_makeProfilePicPopup',500,500);
    window.focus();
    new MakeProfilePicture(
        fbImage,
        // SUCCESS CALLBACK
        function (url) {
            console.log(url);
            makeProfilePicPopup.location.href = url;
            // Puts focus on the newWindow
            if (window.focus) {
                makeProfilePicPopup.focus();
            }
        },
        // DENIED PERMISSIONS CALLBACK
        function ( response ) {
            console.log ( 'Check Permissions denied');
            console.log ( response );
            makeProfilePicPopup.close();
            alert('Please provide permissions in order to share your photo as Profile picture.');
        },
        // ERROR CALLBACK
        function ( response ) {
            console.log ( 'Check Permissions error');
            console.log ( response );
            makeProfilePicPopup.close();
            alert('There was an error. Please try Again');
        }
    );
});
/* *****************************
* Upload local image
* *****************************
*/
function handleFileSelect(evt) {
var files = evt.target.files; // FileList object
var soportedFiles = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'];
var typeValidated = false;
for (var i = soportedFiles.length - 1; i >= 0; i--) {
  if (soportedFiles[i]==files[0].type) {
    typeValidated = true;
  }
}
if (typeValidated) {
  if (8388608 >= files[0].size) {
    var reader = new FileReader();

    // Closure to capture the file information.
    reader.onload = (function(theFile) {
        
      return function(e) {
        var image = new Image();
        image.src = e.target.result;

        image.onload = function() {
            // access image size here 
            var cont = jQuery('.image-container');
            jQuery(".founder-generated-photo .user-image").attr("src", this.src);
            
            var i_width = this.width;
            var i_height = this.height;
            if(scope.image_orientation>=5 && scope.image_orientation<=8){
                i_width = this.height;
                i_height = this.width;
            }
            var newSize = imageInside({
                containerWidth:cont.width(),
                containerHeight:cont.width(),
                imageWidth:i_width,
                imageHeight:i_height
            });
            
            jQuery(".founder-generated-photo .user-image, .image-imitation").css(newSize);
        }
        //jQuery(".founder-generated-photo .user-image").attr("src", e.target.result);
      };
    })(files[0]);
    
    // Read in the image file as a data URL.
    reader.readAsDataURL(files[0]);
    controlStep(false, '.step-2');
    uploadSource = 'manual';
    removeTwitterFilter();
    
    EXIF.getData(evt.target.files[0], function() {
        scope.image_orientation = this.exifdata.Orientation;
    });
    
  }
  else {
    alert('max file size: 8mb');
  }
}
else {
  alert('Invalid Type');
}
}
jQuery( document ).on( 'change', '#localImage', handleFileSelect);
scope.handleUserInfo = function(user_info){
	debugger;
    if(typeof user_info!='undefined'){
        if(typeof user_info.profile_image_url!='undefined'){            
            var profile_image_url = user_info.profile_image_url;
            var imagenormal = profile_image_url;
            var imageori = imagenormal.split('_normal');
            var imageurl = imageori[0]+imageori[1];
            jQuery(".founder-generated-photo .user-image").attr("src", imageurl);
            controlStep(false, '.step-2');
            uploadSource = 'twitter';
            cslid = user_info.user_id;
        }
    }

}
scope.handleImageTweet = function(user_info){
    	debugger;
    if(typeof user_info!='undefined'){
        jQuery('#tweet_form').hide();
        jQuery('.thanks-image-tweet').show();
        
        var tweet_urls = user_info.tweet_reply.entities.urls[0];
        jQuery('#tweet_url').attr('href', tweet_urls.expanded_url).html(tweet_urls.url)
        jQuery(window).resize();
    }
}
/* *****************************
* Upload local image
* *****************************
*/
function controlStep(status, step) {
    if (false === status) {
        jQuery( step ).removeClass('disabled');
    }
    else {
        jQuery( step ).addClass('disabled');
    }
}
scope.errorManager = function(error){
    if(typeof error!='undefined'){
        console.log(error.message);
        alert('Sorry, there was an error. Please try again');
    }
}

function handleUserInfo(o){
    scope.handleUserInfo(o);
}
function handleImageTweet(o){
    scope.handleImageTweet(o);
}

function errorManager(o){
    scope.errorManager(o);
}


/* *******************************
 * Refresh Grid function
 */
function refreshGrid() {

    jQuery.ajax({
        url : cslss.ajax_url,
        type : 'post',
        data : {
            action : 'refresh_image'
        },
        success : function( html ) {
            jQuery('.founders-grid-container').empty();
            jQuery('.founders-grid').slick('unslick');
            jQuery('.founders-grid-container').append(html);
            
            console.log('facts_count: '+facts_count);
            console.log('stories_count: '+stories_count);
            scope.facts_count = facts_count;
            scope.stories_count = stories_count;
            scope.minLoaded = 1;
            scope.totalLoaded = Math.ceil( (scope.facts_count+scope.stories_count)/ 36);
            scope.maxLoaded = Math.ceil( (scope.facts_count+scope.stories_count)/ 36);
            scope.scrollMin = 1;
            scope.scrollMax = 1;
            
            jQuery('.founders-grid').slick({
                initialSlide: 0,
                slidesToScroll: 1,
                slidesToShow: 5,
                rows: 3,
                variableWidth: false,
                autoplay: true,
                autoplaySpeed: 3000,
                responsive: [
                    {
                      breakpoint: 1296,
                      settings: {
                        slidesToShow: 4,
                      }
                    },
                    {
                      breakpoint: 1045,
                      settings: {
                        slidesToShow: 3,
                      }
                    },
                    {
                      breakpoint: 808,
                      settings: {
                        slidesToShow: 2,
                      }
                    },
                    {
                      breakpoint: 580,
                      settings: {
                        arrows: false,
                        slidesToShow: 2,
                      }
                    }
                ]
            });
            scope.rightMarginSlides = 0;
            
            scope.maxLoaded--;
            scope.latestPageLoaded = false;
            loadStoriesPage(scope.maxLoaded);
        }
    });
}
/* *******************************
 * Load more images
 */
    scope.latestPageLoaded = true;
    
 jQuery(document).on('beforeChange', '.founders-grid', function(event, slick, currentSlide, nextSlide){
     
    console.log('slickCount: '+slick.slideCount+' currentSlide: '+currentSlide+' nextSlide:'+nextSlide+', latestPageLoaded: '+scope.latestPageLoaded+' loading page: '+gridpaged);
     
    if(nextSlide>currentSlide){
        if(nextSlide==( Math.ceil((scope.minLoaded*36)/3)-10)  ){
            if (!stopGridAjax) {
                if(scope.latestPageLoaded){
                    scope.minLoaded++;
                    scope.latestPageLoaded = false;
                    jQuery('.slick-arrow.slick-next').attr('disabled', 'true');
                    loadStoriesPage(scope.minLoaded);
                    scope.minScrollFlag = true;
                }
            }
        }
    } else if(nextSlide<currentSlide){
        console.log((slick.slideCount-scope.rightMarginSlides+5));
        if(nextSlide<=(slick.slideCount-scope.rightMarginSlides+5)){
            if (!stopGridAjax) {
                if(scope.latestPageLoaded){
                    scope.maxLoaded--;
                    scope.latestPageLoaded = false;
                    jQuery('.slick-arrow.slick-prev').attr('disabled', 'true');
                    loadStoriesPage(scope.maxLoaded);
                    scope.currentScroll = slick.slideCount-nextSlide;
                    scope.maxScrollFlag = true;
                }
            }
        }
    }
});
function loadStoriesPage(stories_page){
    console.log('loadStoriesPage: '+stories_page);
    
                    jQuery.ajax({
                        url : cslss.ajax_url,
                        type : 'post',
                        data : {
                            action : 'load_more_images',
                            paged: stories_page
                        },
                        success : function( response ) {
                            var data_object = JSON.parse(response);
                            if (true == data_object.success) {
                                var i = 0
                                var row_index = 0;
                                var slideCount = Number(jQuery('.founders-grid').slick("getSlick").slideCount-1);
                                var nextSlide = Number(jQuery('.founders-grid').slick("getSlick").currentSlide);
                                scope.currentScroll = (slideCount+1)-nextSlide;
                                
                                while (i < data_object.images.length) {
                                    var image = '';
                                    var images_count = 0;
                                    for (var x = 3; x > 0; x--) {
                                        if(i<data_object.images.length){
                                            var image = image+'<div><div class="founder grid-item" data-id="'+data_object.images[i].id+'">'+data_object.images[i].image+'</div></div>';
                                            images_count++;
                                        }
                                        i++;
                                    }
                                    if(images_count==3){
                                        var slideImages = '<div class="grid-items-container">'+image+'</div>';
                                        console.log('Insert index: '+(Number(slideCount-scope.rightMarginSlides)+row_index));
                                        jQuery('.founders-grid').slick('slickAdd', slideImages, (slideCount-scope.rightMarginSlides)+row_index);
                                        row_index++;
                                    }
                                }
                                if(scope.maxLoaded==data_object.page_loaded){
                                    scope.rightMarginSlides+=row_index;
                                }
                                scope.latestPageLoaded = true;
                                console.log('Loaded Page: '+data_object.page_loaded);
                                
                                
                                
                                scope.scrollMin = scope.minLoaded;
                                if(scope.maxScrollFlag){
                                    scope.maxScrollFlag = false;
                                    var slideCount = Number(jQuery('.founders-grid').slick("getSlick").slideCount);
                                    console.log('scroll to: '+(slideCount-scope.currentScroll));
                                    jQuery('.founders-grid').slick('slickGoTo', slideCount-scope.currentScroll, true);
                                }
                                jQuery('.slick-arrow').removeAttr('disabled');
                            }
                            else {
                                stopGridAjax = true;
                            }   
                        }
                    });
}
/* *******************************
 * Regular Share functions
 */
jQuery(document).ready(function () {
    jQuery('.share-tools').find('[data-role="share"]').on('click',function(e){
        var dataType = jQuery(this).data('share-type');
        var shareUrl = jQuery(this).data('share-url');
        if ( typeof(cslSocialShares[dataType]) != 'undefined') {
            cslSocialShares[dataType](jQuery(this).data());
            e.preventDefault();
        } else if ( shareUrl != '') {
            cslSocialShares.openURLInPopup(shareUrl);
            e.preventDefault();
        }
        console.log('share');
    });
    refreshGrid();
});
jQuery(window).load(function () {
    
});
var cslSocialShares = {
    openURLInPopup : function(url, name, w, h) {
        if (typeof(w) == "undefined") {
            w = 575;
            h = 400;
        }
        if (typeof(h) == "undefined") {
            h = 400;
        }
        // Fixes dual-screen position                         Most browsers      Firefox
        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

        var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var left = ((width / 2) - (w / 2)) + dualScreenLeft;
        var top = ((height / 2) - (h / 2)) + dualScreenTop;
        var newWindow = window.open(url,  name || 'window' + Math.floor(Math.random() * 10000 + 1),
            'menubar=0,toolbar=0,status=0,scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        // Puts focus on the newWindow
        if (window.focus) {
            newWindow.focus();
        }
    }
    ,email : function ( data ) {
        window.location.href = data.shareUrl;
    }
    /*,facebook : function ( data ) {
        console.log(data);
        FB.ui({
            method: 'feed',
            link: data['refurl'] + '?i=' + jQuery('.share-face-of-founder img').data('id'),
            caption : "Faces Of Founders",
            description: data['description']
        }, function(response){});
    }*/
    ,"imageDownload" : function ( data ) {
        window.location.href = data.shareUrl + jQuery('.share-face-of-founder img').data('id');
    }
    ,"twitter" : function ( data ) {
        
        jQuery('#tweet_form, .thanks-image-tweet').removeAttr('style');
        jQuery('#twitter_share textarea').html(data.title).trigger('keydown');
        jQuery('#twitter_share').modal({
            show: true
        });
        jQuery('#twitter_share textarea').trigger('input');
        jQuery('#twitter_share').on('shown.bs.modal', function () {
            jQuery('#twitter_share textarea').trigger('input').focus();
        });
    }
};


/* ****
 * This function return an object of parameters
 * where the image should be to fit inside the
 * container.
 * @param   Object params
 * @param       Number containerWidth
 * @param       Number containerHeight
 * @param       Number imageWidth
 * @param       Number imageHeight
 * @return Object
 */
function imageInside(params){
    var returnO = {};
    var containerWidth = params.containerWidth;
    var containerHeight = params.containerHeight;
    var imageWidth = params.imageWidth;
    var imageHeight = params.imageHeight;

    // new width, height, top and left
    var newWidth, newHeight, newTop, newLeft;

    newHeight = containerWidth * ( imageHeight / imageWidth );

    if( newHeight < containerHeight){
        newHeight   = containerHeight;
        newWidth    = containerHeight * ( imageWidth / imageHeight );
        newLeft     = (containerWidth - newWidth)/2;
        newTop      = 0;
    } else {
        newWidth    = containerWidth;
        newTop      = (containerHeight - newHeight)/2;
        newLeft     = 0;
    }

    returnO = {
        width   : newWidth,
        height  : newHeight,
        top     : newTop,
        left    : newLeft
    }

    return returnO;
}