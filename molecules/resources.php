<section class="resources">
    <div class="container">

            <?php $query = new WP_Query([
                'post_type' => 'resource',
                'post_status' => 'publish',
                'orderby'   => 'title',
                'order'     => 'ASC',
                'posts_per_page' => -1,
            ]); ?>

                <?php if ($query->have_posts()) : ?>
                    <div class="row">
                        <h2>Resources</h2>
                        <ul class="resources-cont">
                <?php
                while ($query->have_posts()) : $query->the_post();
                    $resource_type = get_field('resource_type');
                    switch ($resource_type):
                        case 'external':
                            $link = get_field('external_link');
                            break;
                        case 'file':
                            $link = get_field('resource_file');
                            break;
                        default:
                            $link = get_permalink();
                            break;
                    endswitch;
                    ?>
                    <li class="match-height col-md-4 col-sm-6 col-xs-12 ">
                        <a href="<?php echo $link; ?>"
                           class="resource-item <?php echo $resource_type; ?>"<?php echo $resource_type != 'article' ? 'target="_blank"' : '' ?>>
                            <h4><?php the_title() ?></h4>
                            <span class="learn-text">Learn More <i class="fa fa-arrow-right"
                                                                   aria-hidden="true"></i></span>
                        </a>
                    </li>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
    <div class="section-devider"></div>
</section>
