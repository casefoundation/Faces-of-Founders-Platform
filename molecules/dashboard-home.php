<?php
   global $wpdb;
   $prefix = $wpdb->prefix;
   $table_name = $prefix . "csl_story_reviews";
   $current_user = wp_get_current_user();
   $data = $wpdb->get_results(
       "
       SELECT *
       FROM $table_name
       WHERE judge_id = $current_user->ID
       AND recused = 0
       AND sent = 0
       "
   );
   $reviewed = $wpdb->get_results(
       "
       SELECT *
       FROM $table_name
       WHERE judge_id = $current_user->ID
       AND recused = 0
       AND status = 1
       AND sent = 0
       "
   );
   $unreviewed = count($data) - count($reviewed);
   ?>
<div id="home" class="table-item fade in active">
   <div class="category-menu dash-filter">
      <ul>
         <li><strong>Sort By:</strong></li>
         <li><a data-filter="all" href="#" class="active">All (<span><?php echo count($data); ?>)</span></a></li>
         <li><a data-filter="incomplete" href="#">Unreviewed (<span><?php echo $unreviewed; ?></span>)</a></li>
         <li><a data-filter="complete" href="#">Reviewed (<span><?php echo count($reviewed); ?></span>)</a></li>
      </ul>
   </div>
   <div class="table-container">
      <?php if (count($data)>0): ?>
      <table style="width:100%">
         <thead>
            <tr>
               <th class="headerSortDown">Name</th>
               <th>Startup</th>
               <th>Status</th>
               <th>Q1</th>
               <th>Q2</th>
               <th>Q3</th>
               <th>Q4</th>
               <th>Average</th>
               <th>Edit</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($data as $revision) {
                $edit_link = get_bloginfo('url') .'/review-story/?judge=' . $revision->judge_id . '&story_id=' . $revision->story_id;
                $average = ($revision->answer_1 + $revision->answer_2 + $revision->answer_3 + $revision->answer_4)/4;
                $complete_status = $revision->answer_1 && $revision->answer_2 && $revision->answer_3 && $revision->answer_4;
                $partiality_status = $revision->answer_1 || $revision->answer_2 || $revision->answer_3 || $revision->answer_4;
               ?>
            <tr class="<?php echo ($revision->status!=1 ? 'incomplete-row' : 'completed-row');?> <?php echo ($revision->in_progress == 1 ? 'partially-complete' : 'no_in_progress');?>">
               <td class="name-item">
                  <a href="<?php echo $edit_link; ?>">
                  <?php the_field('story_full_name', $revision->story_id); ?>
                  </a>
               </td>
               <td class="startup-item"><a href="<?php echo $edit_link; ?>"><?php the_field('startup_name', $revision->story_id); ?></a></td>
               <td class="status-item"><span><?php echo ($revision->in_progress == 1 ? 'in progress' : ($revision->status!=1 ? 'incomplete' : 'completed'));?></span><i class="icon" aria-hidden="true"></i></td>
               <td><?php echo $revision->answer_1; ?></td>
               <td><?php echo $revision->answer_2; ?></td>
               <td><?php echo $revision->answer_3; ?></td>
               <td><?php echo $revision->answer_4; ?></td>
               <td><strong><?php echo round($average, 2); ?></strong></td>
               <td><a href="<?php echo $edit_link; ?>" class="btn-edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
            </tr>
            <?php } ?>
         </tbody>
      </table>
      <div class="container-btn">
         <a data-judge="<?php echo $current_user->ID; ?>"  href="#" class="btn final-review">Finalize Review</a>
      </div>
      <?php else: ?>
      <div class="no-assigned">
         <h2>For the moment has no stories assigned.</h2>
         <h3>Please contact an administrator</h3>
      </div>
      <?php endif; ?>
   </div>
</div>
