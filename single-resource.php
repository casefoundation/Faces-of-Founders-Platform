<?php
get_header(); ?>
  <main id="main" class="main-content-container" role="main">
    <div class="container">
      <div class="row">
        <article class="col-sm-10 col-sm-offset-1">
          <?php
          while ( have_posts() ) : the_post();
            the_content();
          endwhile; // End of the loop.
          ?>
            <?php if( get_field('resource_type') == 'article' ): ?>
              <?php the_field('article_description'); ?>
            <?php endif;?>
        </article>
      </div>
    </div>
    <div class="section-devider top-devider"></div>
    <div class="section-devider bot-devider"></div>
  </main><!-- /main -->
<?php
get_footer(); ?>
