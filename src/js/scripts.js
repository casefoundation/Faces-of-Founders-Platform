(function ($, root, undefined) {
    var judgename;
    var judgeID;
    var noRequests;
    var rowID;
    jQuery(document).ready(function () {

    'use strict';

    $('.table-item table').tablesorter({
      sortList: [[0,0]]
    });
    if (localStorage.judgeSorting != undefined) {
        var sort = localStorage.judgeSorting .split(',');
        $('.table-item table').trigger("update");
        $('.table-item table').trigger("sorton", [[sort]]);
    }

    /* *****************************
     * MATCH HEIGHT CLASS
     * *****************************
     */
    $(window).load(function () {

        $('.footer-col').matchHeight({
            byRow: true,
            property: 'min-height',
            target: null,
            remove: false
        });

        $('.match-height').matchHeight({
            byRow: true,
            property: 'height',
            target: null,
            remove: false
        });

    });
    function animateScroll(target, onComplete) {
        $('html, body').animate({
            scrollTop: target,
                        time: 800
        }, onComplete);
    }
    /* *****************************
     * READ FULL GUIDELINE
     * *****************************
     */
     $('.read-full').click(function(event) {
       event.preventDefault();
       $('.item-menu #guideline').click();
     });

    $('.page-template-dashboard-judge table tbody tr').click(function() {
        window.location = $(this).find('.btn-edit').attr('href');
    });

    $('.page-template-dashboard-judge table th').click(function() {
        var des = 0;
        if($(this).hasClass('headerSortUp')) {
            des = 1;
        }
        switch($(this).html()) {
            case "Name":
                localStorage.judgeSorting = "0,"+des;
                break;
            case "Startup":
                localStorage.judgeSorting = "1,"+des;
                break;
            case "Status":
                localStorage.judgeSorting = "2,"+des;
                break;
            case "Q1":
                localStorage.judgeSorting = "3,"+des;
                break;
            case "Q2":
                localStorage.judgeSorting = "4,"+des;
                break;
            case "Q3":
                localStorage.judgeSorting = "5,"+des;
                break;
            case "Q4":
                localStorage.judgeSorting = "6,"+des;
                break;
            case "Average":
                localStorage.judgeSorting = "7,"+des;
                break;
        }
    });

    /* *****************************
     * DASHBOARD FILTER
     * *****************************
     */
     $('.dash-filter a').click(function(event){
       event.preventDefault();
       $(this).parents('.dash-filter').find('a').removeClass('active');
       var filter = $(this).data('filter');
       switch (filter) {
        case 'all':
          $(this).parents('.table-item').find('tr').removeClass('hidden');
          $(this).addClass('active');
          break;
        case 'complete':
          $(this).parents('.table-item').find('tr').removeClass('hidden');
          $(this).parents('.table-item').find('.incomplete-row').addClass('hidden');
          $(this).addClass('active');
          break;
        case 'incomplete':
          $(this).parents('.table-item').find('tr').removeClass('hidden');
          $(this).parents('.table-item').find('.completed-row').addClass('hidden');
          $(this).addClass('active');
          break;

       }
     });
        /* *****************************
         * DOWNLOAD CSV
         * *****************************
         */
        $('.download-csv').click(function(event){
            event.preventDefault();
            jQuery.ajax({
                url : cslss.ajax_url,
                type : 'post',
                data : {
                    action : 'download_csv'
                },
                success : function( data ) {
                    if (data.status == "success") {
                        console.log(data);
                        var dlif = $('<iframe/>',{'src':data.file}).hide();
                        //Append the iFrame to the context
                        $('body').append(dlif);
                    }
                }
            });
        });
    /* *****************************
     * AUTOASSING STORIES
     * *****************************
     */
    $('.autoassing-btn').click(function(event){
      event.preventDefault();
      $('.main-content-container').addClass('getting-data');
      var review = $('input.no-review').val();
    	jQuery.ajax({
	        url : cslss.ajax_url,
	        type : 'post',
	        data : {
	            action : 'autoassing_stories',
              noreview : review
	        },
	        success : function( html ) {
              $('.judges').empty();
              $('.judges').append(html);
              $('.dashboard-container').removeClass('hidden');
              $('.no-reviews').addClass('hidden');
              $('.main-content-container').removeClass('getting-data');
	        }
	    });
    });
    /* *****************************
     * UPDATE DATA
     * *****************************
     */
    $('.page-template-dashboard .sidebar-menu a:not(.download-csv)').click(function(){
        var target = $(this).html();
        var href = $(this).attr('href');
        jQuery.ajax({
            url : cslss.ajax_url,
            type : 'post',
            data : {
                action : 'update_data',
                target : target
            },
            success : function( html ) {
                $(href+' table tbody').empty();
                $(href+' table tbody').append(html);
                $(href+' .dash-filter a[data-filter="all"] span').html('('+($(href+' table tr').length-1)+')');
                $(href+' .dash-filter a[data-filter="complete"] span').html('('+$(href+' table .completed-row').length+')');
                $(href+' .dash-filter a[data-filter="incomplete"] span').html('('+$(href+' table .incomplete-row').length+')');
                $('.table-item table').trigger("update");
                $('.table-item table').trigger("sorton",[[0,0]]);
            }
        });
    });
    /* *****************************
     * SAVE ANSWER
     * *****************************
     */
     $('.score-checkbox label').click(function(){
       var answerValue = $(this).find('.score').html();
       var noAnswer = $(this).parents('.score-checkbox').data('answer');
       var IDreview = $('.main-content-container').data('review');
       jQuery.ajax({
  	        url : cslss.ajax_url,
  	        type : 'post',
  	        data : {
  	            action : 'save_answer',
                review : IDreview,
                answer : noAnswer,
                value : answerValue
  	        },
  	        success : function( data ) {
                console.log(data);
  	        }
  	    });
     });
     /* *****************************
      * COMPLETEREVIEW
      * *****************************
      */
      $('.complete-review').click(function(event){
        event.preventDefault();
        var comment = $('.text-area-container textarea').val();
        var IDreview = $('.main-content-container').data('review');
        var answer = jQuery('.check-item input:checked');
        if (answer.length==4) {
          jQuery.ajax({
     	        url : cslss.ajax_url,
     	        type : 'post',
     	        data : {
     	            action : 'complete_review',
                    review : IDreview,
                    answer1 : answer[0].value,
                    answer2 : answer[1].value,
                    answer3 : answer[2].value,
                    answer4 : answer[3].value,
                   comment : comment
     	        },
     	        success : function( data ) {
                   console.log(data);
                   if (data!="false") {
                     var url = window.location.origin+"/dashboard-judge";
                     window.location=url;
                   }
                   else {
                     alert('unknown error occurred please try again');
                   }
     	        }
     	    });
        }
        else {
          swal({
            title: "Please rate all the answers",
            type:  "error"
          });
        }

      });

      /* *****************************
      * SAVE FOR LATER
      * *****************************
      */
      $('.save-for-later').click(function(event){
        event.preventDefault();
        var comment = $('.text-area-container textarea').val();
        var IDreview = $('.main-content-container').data('review');
        var answer1 = jQuery('.story-1 .check-item input:checked').val();
          var answer2 = jQuery('.story-2 .check-item input:checked').val();
          var answer3 = jQuery('.story-3 .check-item input:checked').val();
          var answer4 = jQuery('.story-4 .check-item input:checked').val();

          jQuery.ajax({
                url : cslss.ajax_url,
                type : 'post',
                data : {
                    action : 'save_for_later',
                    review : IDreview,
                    answer1 : answer1,
                    answer2 : answer2,
                    answer3 : answer3,
                    answer4 : answer4,
                   comment : comment
                },
                success : function( data ) {
                   console.log(data);
                   if (data.status == "success") {
                       var url = window.location.origin+"/dashboard-judge";
                       window.location=url;
                   }
                   else {
                       swal({
                           title: "unknown error occurred please try again",
                           type:  "error"
                       });
                   }

                }
            });

      });
      /* *****************************
       * RECUSE_REVIEW
       * *****************************
       */
       $('.btn-recuse-review').click(function(event){
         event.preventDefault();
         var IDreview = $('.main-content-container').data('review');
          swal({
            title: "Are you sure you want to recuse this review?",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            confirmButtonText: "Yes, Recuse"
          },
          function(){
            $(this).addClass('loading');
            jQuery.ajax({
              url : cslss.ajax_url,
              type : 'post',
              data : {
                  action : 'recuse_review',
                  review : IDreview
              },
              success : function( data ) {
                console.log(data);
                if (data!="false") {
                  var url = window.location.origin+"/dashboard-judge";
                  window.location=url;
                }
                else {
                  swal("unknown error occurred", "Please try again", "error");
                }
              }
            });
          });
       });
    $('.requests-more').click(function(event){
      event.preventDefault();
      swal({
        title: "Thanks for your help!",
        text: "Enter the number of stories you want to request:",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputType: "number",
        customClass: 'custom-input',
        showLoaderOnConfirm: true,
      },
      function(inputValue){
        var IDjudge = $('.main-content-container').data('judge');
        if (inputValue === false) return false;
          if (inputValue <= 0) {
              swal.showInputError("Must be greater than 0");
              return false
          }
        if (inputValue === "") {
          swal.showInputError("This field is required");
          return false
        }
        else {
          jQuery.ajax({
            url : cslss.ajax_url,
            type : 'post',
            data : {
                action : 'story_requests',
                judge : IDjudge,
                story_requests : inputValue
            },
            success : function( data ) {

              if (data!="false") {
                swal("Your request has been submitted");
              }
              else {
                swal("unknown error occurred", "Please try again", "error");
              }
            }
          });
        }
        
      });
      
        setTimeout(function(){
            $('.sweet-alert input[type="number"]').on('input',function(e){
                console.log($(this).val());
                if($(this).val()<=0){
                   $(this).val(1); 
                }
            });
        }, 100);
    });
    
    //***FIXED WHEN SCROLL***///
      $(function($) {
        function fixDiv() {
          var $cache = $('#sidebar');
          if ($(window).scrollTop() > 256)
            $cache.css({
              'position': 'fixed',
              'top': '0px',
              'left': '0px',
            });
          else
            $cache.css({
              'position': 'absolute',
              'top': '0',
              'left': '0'
            });
        }
        $(window).scroll(fixDiv);
        fixDiv();
      });
      $(document).on('click','.navbar-collapse',function(e) {
          if( $(e.target).is('a') ) {
              $(this).collapse('hide');
          }
      });

    $( document ).on( 'click', '.reassign-row.incomplete-row', function(event) {
        judgeID = $(this).data('judgeid');
        judgename = $(this).data('judge');
        noRequests = $(this).data('requests');
        rowID = $(this).data('id');

        $('#reassign-modal .requests').html(noRequests);
        $('#reassign-modal .judge').html(judgename);
        $('#reassign-modal').modal('show');

    });
    $('.recused-stories-btn').click(function (event) {
        event.preventDefault();
        $('#recused-stories-modal .requests').html(noRequests);
        $('#recused-stories-modal .judge').html(judgename);
        jQuery.ajax({
            url : cslss.ajax_url,
            type : 'post',
            data : {
                action : 'get_recused_stories',
                judge : judgeID
            },
            success : function( data ) {

                if (parseInt(data)>0) {
                    $('#recused-stories-modal .recused').html(data);
                    $('#recused-stories-modal input').attr('max', data);
                    $('#reassign-modal').modal('hide');
                    setTimeout(function () {
                        $('#recused-stories-modal').modal('show');
                    },100);
                }
                else {
                    swal({
                        title: "There are no recused stories that can be assigned to this judge",
                        type:  "error"
                    });
                }
            }
        });
    });
    $('.from-other-judges').click(function (event) {
        event.preventDefault();
        $('#other-judges-modal .requests').html(noRequests);
        $('#other-judges-modal .judge').html(judgename);
        jQuery.ajax({
            url : cslss.ajax_url,
            type : 'post',
            data : {
                action : 'available_judges_for_judgeid',
                judge : judgeID
            },
            success : function( html ) {

                if (html!="false") {
                    $('#other-judges-modal select').html(html);
                    $('#reassign-modal').modal('hide');
                    setTimeout(function () {
                        $('#other-judges-modal').modal('show');
                    },100);
                }
                else {
                    swal("unknown error occurred", "Please try again", "error");
                }
            }
        });
    });

    /* ************************************
     * REASSIGN STORIES FROM OTHER JUDGES
     * ************************************
     */
    $('.reassign-stories-other-judges').click(function (event) {
        event.preventDefault();
        var reassignJudges = $('#other-judges-modal select').val();
        var numberOfStories = $('#other-judges-modal input').val();
        if(reassignJudges.length > 0) {
            $('#other-judges-modal').addClass('loading');
            jQuery.ajax({
                url : cslss.ajax_url,
                type : 'post',
                data : {
                    action : 'reassign_stories_from_other_judges',
                    target_judge : judgeID,
                    reassign_judges : reassignJudges,
                    number_of_stories : numberOfStories,
                    row : rowID
                },
                success : function( data ) {
                    console.log(data);
                    if (data.status == "success") {
                        $('#tbl-requests table tr[data-id="'+rowID+'"]').removeClass('incomplete-row');
                        $('#tbl-requests table tr[data-id="'+rowID+'"]').addClass('completed-row');
                        $('#tbl-requests .dash-filter a[data-filter="all"] span').html('('+($('#tbl-requests table tr').length-1)+')');
                        $('#tbl-requests .dash-filter a[data-filter="complete"] span').html('('+$('#tbl-requests table .completed-row').length+')');
                        $('#tbl-requests .dash-filter a[data-filter="incomplete"] span').html('('+$('#tbl-requests table .incomplete-row').length+')');
                        $('#other-judges-modal').removeClass('loading');
                        $('#other-judges-modal').modal('hide');
                    }
                    else {
                        swal("unknown error occurred", "Please try again", "error");
                    }
                }
            });
        }
    });

    /* ************************************
     * REASSIGN RECUSED STORIES TO A JUDGE
     * ************************************
     */
    $('.reassign-recused-stories').click(function (event) {
        event.preventDefault();
        var noRecuses = $('#recused-stories-modal input').val();
        if(noRecuses > 0) {
            $('#recused-stories-modal').addClass('loading');
            jQuery.ajax({
                url : cslss.ajax_url,
                type : 'post',
                data : {
                    action : 'reassign_recused_story_to_judge',
                    judge : judgeID,
                    recuses : noRecuses,
                    row : rowID
                },
                success : function( data ) {

                    if (data!="false") {
                        $('#tbl-requests table tr[data-id="'+rowID+'"]').removeClass('incomplete-row');
                        $('#tbl-requests table tr[data-id="'+rowID+'"]').addClass('completed-row');
                        $('#tbl-requests .dash-filter a[data-filter="all"] span').html('('+($('#tbl-requests table tr').length-1)+')');
                        $('#tbl-requests .dash-filter a[data-filter="complete"] span').html('('+$('#tbl-requests table .completed-row').length+')');
                        $('#tbl-requests .dash-filter a[data-filter="incomplete"] span').html('('+$('#tbl-requests table .incomplete-row').length+')');
                        $('#recused-stories-modal').removeClass('loading');
                        $('#recused-stories-modal').modal('hide');
                    }
                    else {
                        swal("unknown error occurred", "Please try again", "error");
                    }
                }
            });
        }
    });

    /* *****************************
     * OPEN RECUSED MODAL
     * *****************************
     */
    $( document ).on( 'click', '.recused-row.incomplete-row', function(event) {
        var storyID = $(this).data('storyid');
        var rowID = $(this).data('id');
        $('#recused-modal .author').html($(this).data('author'));
        $('#recused-modal .judge').html($(this).data('judge'));
        $('#recused-modal').data('story', storyID);
        $('#recused-modal').data('id', rowID);
        jQuery.ajax({
            url : cslss.ajax_url,
            type : 'post',
            data : {
                action : 'available_judges',
                story : storyID
            },
            success : function( html ) {

                if (html!="false") {
                    $('#recused-modal #judges-list').html(html);
                    $('#recused-modal').modal('show');
                }
                else {
                    console.log("unknown error occurred, Please try again");
                }
            }
        });

    });
    /* *****************************
     * REASSINGN RECUSED STORIES
     * *****************************
     */
    $('.reassign-recuses').click(function (event) {
        event.preventDefault();
        var judgeID = $('#judges-list').val();
        var storyID = $('#recused-modal').data('story');
        var rowID = $('#recused-modal').data('id');
        if(judgeID != "") {
            $('#recused-modal').addClass('loading');
            jQuery.ajax({
                url : cslss.ajax_url,
                type : 'post',
                data : {
                    action : 'reassign_recused_story',
                    judge : judgeID,
                    story : storyID,
                    row : rowID
                },
                success : function( data ) {

                    if (data!="false") {
                        $('#tbl-recuses table tr[data-id="'+rowID+'"]').removeClass('incomplete-row');
                        $('#tbl-recuses table tr[data-id="'+rowID+'"]').addClass('completed-row');
                        $('#tbl-recuses .dash-filter a[data-filter="all"] span').html('('+($('#tbl-recuses table tr').length-1)+')');
                        $('#tbl-recuses .dash-filter a[data-filter="complete"] span').html('('+$('#tbl-recuses table .completed-row').length+')');
                        $('#tbl-recuses .dash-filter a[data-filter="incomplete"] span').html('('+$('#tbl-recuses table .incomplete-row').length+')');
                        $('#recused-modal').removeClass('loading');
                        $('#recused-modal').modal('hide');
                    }
                    else {
                        console.log("unknown error occurred, Please try again");
                    }
                }
            });
        }
    });

    /* *****************************
     * Final Review
     * *****************************
     */
    $('.final-review').click(function (event) {
        event.preventDefault();
        var unreview = parseInt($('.dash-filter a[data-filter="incomplete"] span').html());
        var judgeID = $(this).data('judge');
        if (unreview > 0) {
            swal({
                title: "You have "+unreview+" unreviewed stories",
                text: "Do you want to recuse the stories?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                customClass: "final-popup",
                showLoaderOnConfirm: true
            },
            function(){
                jQuery.ajax({
                    url : cslss.ajax_url,
                    type : 'post',
                    data : {
                        action : 'final_review_incomplete',
                        judge : judgeID
                    },
                    success : function( data ) {

                        if (data.status == "success") {
                            swal({
                                title: "Thanks!",
                                text: "Your reviews have been submitted"
                            },
                            function(){
                                location.reload();
                            });
                        }
                        else {
                            console.log("Error: /n recused: "+data.recused+" updated: "+data.updated);
                            swal({
                                title: "Unknown error occurred",
                                text: "Please try again",
                                type:  "error"
                            });
                        }
                    }
                });
            });
        }
        else {
            if (unreview == 0) {
                jQuery.ajax({
                    url : cslss.ajax_url,
                    type : 'post',
                    data : {
                        action : 'final_review_completed',
                        judge : judgeID
                    },
                    success : function( data ) {

                        if (data.status == "success") {
                            swal({
                                title: "Your submissions have been sent!",
                                text: "Do you want to request more stories for review?",
                                showCancelButton: true,
                                confirmButtonText: "Yes",
                                cancelButtonText: "No",
                                closeOnConfirm: false
                            },
                            function(){
                                $('.requests-more').click();
                            });
                        }
                        else {
                            console.log("unknown error occurred, Please try again");
                            swal({
                                title: "Unknown error occurred",
                                text: "Please try again",
                                type:  "error"
                            });
                        }
                    }
                });
            }
        }
    });
    /* *****************************
     * CSL SLIDER
     * *****************************
     */
    $('.message-slider').slick({
        asNavFor: '.founder-filters',
        slidesToScroll: 1,
        slidesToShow: 1,
        responsive: [
            {
              breakpoint: 768,
              settings: {
                arrows: false,
                draggable: false
              }
            }
        ]
    });
    $('.founder-filters').slick({
        arrows: false,
        asNavFor: '.message-slider',
        fade: true,
        slidesToScroll: 1,
        slidesToShow: 1,
        draggable: false,
        responsive: [
            {
              breakpoint: 768,
              settings: {
                arrows: true,
              }
            }
        ]
    });

    $('.founders-grid').slick({
        initialSlide: 33,
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

    jQuery( ".image-imitation" ).draggable({
        drag: function( event, ui ) {
            var cont = $('.image-resizer');
            var img = $('.image-imitation');
            var img_target = $('.user-image');

            if(ui.position.left>0){
                ui.position.left = 0;
            }
            if(ui.position.top>0){
                ui.position.top = 0;
            }

            if(ui.position.left+img.width()<cont.width()){
                ui.position.left = cont.width()-img.width();
            }
            if(ui.position.top+img.height()<cont.height()){
                ui.position.top = cont.height()-img.height();
            }

            img_target.css(ui.position);
        }
    });

    $('#sigup-newsletter').click(function(){
        if (this.checked) {
            $('input.email').prop('required',true);
        }
        else {
          $('input.email').prop('required',false);
        }
    })
    $('.message-slider').on('afterChange', function(event, slick, currentSlide){
      if (0 == currentSlide) {
        controlStep(true, '.step-3');
      }
      else {
        $( '.message-slider label[data-slick-index="'+currentSlide+'"] input[type="radio"]' ).prop( 'checked', true );
        controlStep(false, '.step-3');
        $('.founder-filters').slick("goTo", currentSlide);
      }
    });

    /* *****************************
     * VALIDATE CREATE STORY FORM
     * *****************************
     */
    var confirmOnPageExit = function (e)
    {

        var message = 'Are you sure you want to navigate away from this page?';
        if ($('.story textarea[name="your-business"]').val()!=""||$('.story textarea[name="biggest-obstacle"]').val()!=""||$('.story textarea[name="diversifying-entrepreneurship"]').val()!=""||$('.story input[name="full-name"]').val()!=""||$('.story input[name="email"]').val()!="") {
            // If we haven't been passed the event get the window.event
            e = e || window.event;
            // For IE6-8 and Firefox prior to version 4
            if (e)
            {
                e.returnValue = message;
            }

            // For Chrome, Safari, IE8+ and Opera 12+
            return message;
        }
    };
    if ($('body').hasClass('home') && (isMobile.iOS()||isMobile.iOS_iPad()) && $('.story textarea[name="your-business"]').length>0) {
        $('a').click(function(e){
            var href = jQuery(this).attr('href');
            var target = jQuery(this).attr('target');

            var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
            '(\\#[-a-z\\d_]*)?$','i');

            if(pattern.test(href) && target!='_blank') {
                var events = [];
                $.each($(this).data('events'), function(i, event){
                    $.each(event, function(i, handler){
                        events.push(handler);
                    });
                });
                if(events.length==0){
                    var message = 'Are you sure you want to navigate away from this page?';
                    if ($('.story textarea[name="your-business"]').val()!=""||$('.story textarea[name="biggest-obstacle"]').val()!=""||$('.story textarea[name="diversifying-entrepreneurship"]').val()!=""||$('.story input[name="full-name"]').val()!=""||$('.story input[name="email"]').val()!="") {
                        if(!confirm(message)){
                            e.preventDefatul();
                        }
                    }
                }

            }
        });
    }

    if ($('body').hasClass('home') && $('.story textarea[name="your-business"]').length>0) {
        window.onbeforeunload = confirmOnPageExit;
    }
    $('#create_story').validate({
        rules: {
            agreed: {
                required: true,
            }
        },
        messages: {
            agreed: {
                required: "You must agree with contest rules.",
            }
        },
        submitHandler: function(form) {
            $(form).find('[type="submit"]').hide();
            $(form).find('.processing').show();
            $(form).ajaxSubmit({
                success:    function(data) {
                    $(form).find('[type="submit"]').removeAttr('style');
                    $(form).find('.processing').removeAttr('style');
                    var data_object = JSON.parse(data);

                    if(data_object.success){
                        window.onbeforeunload = null;
                        $('#create_story').slideUp();
                        $('.thank-you-story').slideDown(function () {
                           animateScroll(jQuery('.thank-you-story').offset().top);
                        });

                        jQuery('#create_story_step_2 input[name="post_id"]').val(data_object.response.post_id);
                    }
                }
            });
        }
    });

    $('#create_story_step_2').validate({
        submitHandler: function(form) {
            $(form).find('[type="submit"]').hide();
            $(form).find('.processing').show();
            $(form).ajaxSubmit({
                success:    function(data) {
                    $(form).find('[type="submit"]').removeAttr('style');
                    $(form).find('.processing').removeAttr('style');
                    var data_object = JSON.parse(data);

                    if(data_object.success){
                        $('.thank-you-story').slideUp();
                        $('.thank-you-story-2').slideDown(function () {
                            animateScroll(jQuery('.thank-you-story-2').offset().top);
                        });
                    }
                }
            });
        }
    });

    $('#nominate-entrepeneur').validate({
        submitHandler: function(form) {
            $(form).find('[type="submit"]').hide();
            $(form).find('.processing').show();
            $(form).ajaxSubmit({
                success:    function(data) {
                    $(form).find('[type="submit"]').removeAttr('style');
                    $(form).find('.processing').removeAttr('style');
                    var data_object = JSON.parse(data);

                    if(data_object.success){
                        $('.thank-you-entrepeneur').show();
                        $('#nominate-entrepeneur').hide();
                    }
                }
            });
        }
    });

    $('.maxchars').each(function(){
        var char_count = (typeof $(this).data('max-chars')!='undefined')?$(this).data('max-chars'):200;
        $(this).after('<span class="char-count" data-for="'+$(this).attr('name')+'">'+char_count+'</span>');
        $(this).siblings('[data-for="'+$(this).attr('name')+'"]').html(char_count-$(this).val().length);
    }).keydown(function(e){
        var char_count = (typeof $(this).data('max-chars')!='undefined')?Number($(this).data('max-chars')):200;
        var keys = [8, 9, 16, 17, 18, 19, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 46, 144, 145];
        if( $.inArray(e.keyCode, keys) == -1) {
            if( ($(this).val().length+1) > char_count ){
                e.preventDefault();
                 e.stopPropagation();
            }
        }
        $(this).siblings('[data-for="'+$(this).attr('name')+'"]').html(char_count-$(this).val().length);
    }).keyup(function(e){
        var char_count = (typeof $(this).data('max-chars')!='undefined')?Number($(this).data('max-chars')):200;
        if($(this).val().length > char_count){
            $(this).val($(this).val().substr(0,char_count));
        }

        $(this).siblings('[data-for="'+$(this).attr('name')+'"]').html(char_count-$(this).val().length);
    })

    $('#main-header .btn.back').click(function(e){
        if(document.referrer==homepage_url){
            e.preventDefault();
            window.history.back();
        }
    });

    // Placeholder
    jQuery(document).ready(function($){
        $('.lwa-username-input input').attr("placeholder","Email");
        $('.lwa-password-input input').attr("placeholder","Password");

     });
    // Vertical Tabs

});


})(jQuery, this);


/* ****
 * This function return a boolean if the device has
 * mobile user agent.
 *
 * @param   void
 * @return Boolean
 */
var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i) ? true : false;
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i) ? true : false;
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i) ? true : false;
    },
    iOS_iPad: function() {
        return navigator.userAgent.match(/iPad/i) ? true : false;
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i) ? true : false;
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Windows());
    }
};
