<section class="founder-story" id="founder-story">
	
    <?php
        if( get_field('enable_limit_date')=='enabled' ){
            $limit_date_field = get_field('form_limit_date');
            date_default_timezone_set('US/Eastern');
            $limit_date = DateTime::createFromFormat('d/m/Y g:i a', $limit_date_field);
            $now = new DateTime();
        }
    ?>
    <?php if( get_field('enable_limit_date')=='enabled' ? $now<$limit_date : true ):?>    
        <div class="tell-us-your-story" style="background-image: url('<?php the_field('story_bg_img'); ?>');">
		<div class="container">
			<div class="row">
				<article class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
					<?php if( get_field('story_title') ): ?>
						<h2><?php the_field('story_title'); ?></h2>
					<?php endif;
						  if( get_field('story_intro_text') ):
						the_field('story_intro_text');
					endif; wp_reset_postdata(); ?>
				</article>
			</div>
		</div>
	</div>    
        <div class="prizes">
		<div class="container">
			<div class="row">
				<article class="col-xs-12 prizes-requirements">
					<div class="prizes-requirements-container">
						<h2>Award</h2>
						<?php if( get_field('prizes_content') ):
							the_field('prizes_content');
						endif; ?>
					</div>
				</article>
			</div>
		</div>
	</div>
        <form action="<?php echo admin_url('admin-ajax.php'); ?>" class="story" id="create_story" method="post">
		<div class="container">
			<div class="row">
                            
				<div class="form-group">
					<label for="your-business">Tell us about your business. What problem is it trying to solve? *</label>
					<textarea class="form-control autoheight maxchars" data-max-chars="2000" rows="1" name="your-business" required></textarea>
				</div>
				<div class="form-group">
					<label for="biggest-obstacle">What has been your biggest obstacle in your entrepreneurship journey? *</label>
					<textarea class="form-control autoheight maxchars" data-max-chars="2000" rows="1" name="biggest-obstacle" required></textarea>
				</div>
				<div class="form-group">
					<label for="diversifying-entrepreneurship">How do you or your business contribute to advancing inclusive entrepreneurship? *</label>
					<textarea class="form-control autoheight maxchars" data-max-chars="2000" rows="1" name="diversifying-entrepreneurship" required></textarea>
				</div>
				<div class="form-group">
					<div class="col-sm-6">
						<label for="full-name">Full Name *</label>
						<input class="form-control" type="text" name="full-name" required>
					</div>
					<div class="col-sm-6">
						<label for="email">Email *</label>
						<input class="form-control" type="email" name="email" required>
					</div>
				</div>
				<div class="form-group">
					<div class="checkbox">
						<input type="checkbox" id="agreement" name="agreed" required>
						<label for="agreement"><span>I agree to the terms and conditions <button type="button" data-toggle="modal" data-target="#awards_help">
								<i class="fa fa-info-circle"></i>
						</button></span></label>
						<!-- Button trigger modal -->

					</div>
				</div>
                                <input type="hidden" name="action" value="create_story"/>

                                <input type="hidden" name="attachment_id" value=""/>
                                <input type="hidden" name="print_photo" value=""/>
                                <input type="hidden" name="post_id" value=""/>

				<button type="submit" class="btn btn-primary">Submit Story <i class="fa fa-angle-right"></i></button>
                                <button class="btn btn-primary processing" disabled="">Submitting Story<i class="fa fa-refresh fa-spin" aria-hidden="true"></i></button>
			</div>
		</div>
	</form>
    <div class="thank-you-story">
        <h3>Thanks for telling us your entrepreneurial story! </h3>
        <p>Now tell us a little more about you and your business:</p>

        <form action="<?php echo admin_url('admin-ajax.php'); ?>" class="story" id="create_story_step_2" method="post">
        	<div class="container">
        		<div class="row">
	        		<div class="form-group">
        				<label for="startup-name">Business name</label>
        				<input class="form-control" type="text" name="startup_name">
	        		</div>
	        		<div class="form-group">
						<div class="col-sm-6">
							<label for="city">City</label>
							<input class="form-control" type="text" name="story_city">
						</div>
						<div class="col-sm-6">
							<label for="state">State</label>
                                                        <div class="select-container">
                                                            <select class="form-control" name="story_state" id="state">
                                                                    <?php $field_object = get_field_object('field_57ed3759fb488'); ?>
                                                                    <?php $default_value = $field_object['default_value']; ?>
                                                                    <?php foreach($field_object['choices'] as $val=>$label):?>
                                                                        <option value="<?php echo $val;?>" <?php echo (count($default_value)>0?($val==$default_value[0]?'selected':''):'');?>><?php echo $label;?></option>
                                                                    <?php endforeach;?>
                                                            </select>
                                                        </div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-6">
							<label for="country">Country of Residence</label>
                                                        <div class="select-container">
                                                            <select class="form-control" name="story_country" id="country">
                                                                    <?php $field_object = get_field_object('field_57ed3786fb489'); ?>
                                                                    <?php $default_value = $field_object['default_value']; ?>
                                                                    <?php foreach($field_object['choices'] as $val=>$label):?>
                                                                        <option value="<?php echo $val;?>" <?php echo (count($default_value)>0?($val==$default_value[0]?'selected':''):'');?>><?php echo $label;?></option>
                                                                    <?php endforeach;?>
                                                            </select>
                                                        </div>

						</div>
						<div class="col-sm-6">
							<label for="story_gender">Gender</label>
                                                        <div class="select-container">
                                                            <select class="form-control" name="story_gender" id="gender">
                                                                    <?php $field_object = get_field_object('field_57ed37a9fb48a'); ?>
                                                                    <?php $default_value = $field_object['default_value']; ?>
                                                                    <?php foreach($field_object['choices'] as $val=>$label):?>
                                                                        <option value="<?php echo $val;?>" <?php echo (count($default_value)>0?($val==$default_value[0]?'selected':''):'');?>><?php echo $label;?></option>
                                                                    <?php endforeach;?>
                                                            </select>
                                                        </div>
						</div>
					</div>
                                        <div class="form-group">
						<div class="col-sm-6">
							<label for="ethnicy">Ethnicity</label>
                                                        <div class="select-container">
                                                            <select class="form-control" name="story_ethnicy" id="ethnicy">
                                                                    <?php $field_object = get_field_object('field_57ed37c8fb48b'); ?>
                                                                    <?php $default_value = $field_object['default_value']; ?>
                                                                    <?php foreach($field_object['choices'] as $val=>$label):?>
                                                                        <option value="<?php echo $val;?>" <?php echo (count($default_value)>0?($val==$default_value[0]?'selected':''):'');?>><?php echo $label;?></option>
                                                                    <?php endforeach;?>
                                                            </select>
                                                        </div>
						</div>
						<div class="col-sm-6">
							<label for="age">Age</label>
                                                        <div class="select-container">
                                                            <select class="form-control" name="story_age" id="age">
                                                                    <?php $field_object = get_field_object('field_57ed37dbfb48c'); ?>
                                                                    <?php $default_value = $field_object['default_value']; ?>
                                                                    <?php foreach($field_object['choices'] as $val=>$label):?>
                                                                        <option value="<?php echo $val;?>" <?php echo (count($default_value)>0?($val==$default_value[0]?'selected':''):'');?>><?php echo $label;?></option>
                                                                    <?php endforeach;?>
                                                            </select>
                                                        </div>
						</div>
					</div>
                                        <div class="form-group">
						<div class="col-sm-6">
							<label for="sector">Sector</label>
                                                        <div class="select-container">
                                                            <select class="form-control" name="story_sector" id="sector">
                                                                    <?php $field_object = get_field_object('field_57ed37e5fb48d'); ?>
                                                                    <?php $default_value = $field_object['default_value']; ?>
                                                                    <?php foreach($field_object['choices'] as $val=>$label):?>
                                                                        <option value="<?php echo $val;?>" <?php echo (count($default_value)>0?($val==$default_value[0]?'selected':''):'');?>><?php echo $label;?></option>
                                                                    <?php endforeach;?>
                                                            </select>
                                                        </div>
						</div>
						<div class="col-sm-6">
							<label for="funding">Funding</label>
                                                        <div class="select-container">
                                                            <select class="form-control" name="story_funding" id="funding">
                                                                    <?php $field_object = get_field_object('field_57ed37f7fb48e'); ?>
                                                                    <?php $default_value = $field_object['default_value']; ?>
                                                                    <?php foreach($field_object['choices'] as $val=>$label):?>
                                                                        <option value="<?php echo $val;?>" <?php echo (count($default_value)>0?($val==$default_value[0]?'selected':''):'');?>><?php echo $label;?></option>
                                                                    <?php endforeach;?>
                                                            </select>
                                                        </div>
						</div>
					</div>

                                        <input type="hidden" name="action" value="create_story_step_2"/>
                                        <input type="hidden" name="post_id" value=""/>

					<button type="submit" class="btn btn-primary">Complete My Submission <i class="fa fa-paper-plane-o"></i></button><button class="btn btn-primary processing" disabled="">Submitting<i class="fa fa-refresh fa-spin" aria-hidden="true"></i></button>
	        	</div>
        	</div>
        </form>
    </div>
     <div class="thank-you-story-2">
        <div class="container">
            <?php the_field('thanks_message'); ?>
        </div>
     </div>
    
    <?php else:?>
        <div class="tell-us-your-story expired-time" style="background-image: url('<?php the_field('story_bg_img'); ?>');">
		<div class="container">
			<div class="row">
				<article class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
					<?php if( get_field('expired_time_title') ): ?>
						<h2><?php the_field('expired_time_title'); ?></h2>
					<?php endif;
                                        wp_reset_postdata(); ?>
				</article>
			</div>
		</div>
	</div>
        <div class="prizes expired-time">
		<div class="section-devider"></div>
	</div>
    <div class="time-expired">
        <div class="container">
            <div class="row">
                <div class="content">
                    <?php the_field('expired_time_copy');?>
                </div>
            </div>
        </div>
    </div>
    <?php endif;?>
    
</section>
