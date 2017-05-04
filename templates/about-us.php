<?php
/* Template Name: About Us */
get_header(); ?>
	<main id="main" class="main-content-container" role="main">
		<?php
		get_template_part('molecules/about', 'description');
		get_template_part('molecules/faq', 'content');
		//get_template_part('molecules/primary', 'partners');
		//get_template_part('molecules/partners');
		//get_template_part('molecules/featured', 'reviewers');
		get_template_part('molecules/resources');
		?>
	</main><!-- /main -->
<?php
get_footer(); ?>
