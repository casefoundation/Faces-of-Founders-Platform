<section class="faq-container">
	<div class="container">
		<div class="row">
			<div class="col-xs-10 col-xs-offset-1">
				<h2 id="faqs"><?php the_field('faq_title_section'); ?></h2>

					<?php if( have_rows('faq_items') ): ?>

						<ul class="faq-items-container">

						<?php while( have_rows('faq_items') ): the_row();

							// vars
							$title = get_sub_field('faq_title');
							$content = get_sub_field('faq_description');

							?>

							<li class="faq-item">
								<h3><?php echo $title; ?></h3>
								<?php echo $content; ?>
							</li>

						<?php endwhile; ?>

						</ul>

					<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="section-devider"></div>
</section>
