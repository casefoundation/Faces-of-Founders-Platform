<div id="menu2" class="table-item tab-pane fade guideline">
	<div class="category-menu">
		<h3>Judging Guidelines</h3>
	</div>
	<div class="container-guidelines">
		<?php
			while ( have_posts() ) : the_post();
				the_content();
			endwhile; // End of the loop.
		?>
	</div>
</div>
