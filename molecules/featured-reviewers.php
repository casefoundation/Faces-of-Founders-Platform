<section class="featured-reviewers">
	<div class="container">
		<div class="row">
				<h2><?php the_field('featured_reviewers_title'); ?></h2>

					<?php if( have_rows('featured_reviewers_items') ): ?>

						<ul class="reviewers-container">
						<?php while( have_rows('featured_reviewers_items') ): the_row();
							// vars
							$image = get_sub_field('image');
							$name_and_lastname = get_sub_field('name_and_lastname');
							$startup_name = get_sub_field('startup_name');
							?>
							<li class="col-md-3 match-height">
								<div class="container-image">
										<div class="wrapper">
											<div class="wrapper-inner">
												<img src="<?php echo $image; ?>">
											</div>
										</div>
									</div>
									<h4><?php echo $name_and_lastname; ?></h4>
									<p>
										<?php echo $startup_name; ?>
									</p>

							</li>
						<?php endwhile; ?>
						</ul>

					<?php endif; ?>

		</div>
	</div>
	<div class="section-devider"></div>
</section>
