<?php
/* Template Name: Dashboard Admin*/

if(!current_user_can('administrator') ) {
	$url = get_bloginfo('url');
	header ("Location: ".$url);
}

get_header();
 ?>
	<main id="main" class="main-content-container" role="main">
		<?php
        global $wpdb;
        $prefix = $wpdb->prefix;
		$blogusers = get_users('role=judge');
        $judges = $wpdb->get_results(
            "
            SELECT r.judge_id, COUNT(*) as assigned, SUM(r.status) as completed, SUM(r.recused) as recused, SUM(r.in_progress) as in_progress
            FROM {$wpdb->prefix}csl_story_reviews r
            GROUP BY r.judge_id
            "
        );

		?>
		<?php if (count($blogusers)>0): ?>
		<div class="dashboard-container <?php if (count($judges)==0) {echo "hidden";} ?>">
			<div class="sidebar-menu" id="sidebar">
				<div class="sidebar-container">
					<h2 class="hidden-xs">Main Menu</h2>
					<nav class="navbar navbar-default dash-nav" role="navigation">
						<div class="container-fluid">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
							</div>
					<div class="collapse navbar-collapse" id="navbar-collapse-1">
					<ul class="nav nav-tabs menu">
						<li class="item-menu active"><a data-toggle="tab" href="#tbl-judges">Judges</a></li>
						<li class="item-menu"><a data-toggle="tab" href="#tbl-submission">Submissions</a></li>
                        <li class="item-menu"><a data-toggle="tab" href="#tbl-requests">Requests</a></li>
						<li class="item-menu"><a data-toggle="tab" href="#tbl-recuses">Recuses</a></li>
                        <li class="item-menu orange"><a class="orange download-csv" href="#"><i class="fa fa-cloud-download"></i>Export Data</a></li>
					</ul>
				</div>
			</div>
		</nav>
			</div>
		</div>
			<div class="tab-content">
				<div id="tbl-judges" class="table-item fade in active">
					<div class="category-menu dash-filter">
						<h3>Judges</h3>
						<ul>
							<?php
								$complete_judge = 0;
                                foreach ( $judges as $judge ) {
									$incomplete = $judge->assigned - $judge->completed - $judge->recused - $judge->in_progress;
									if ($incomplete==0) {
										$complete_judge++;
									}
								}
							?>
							<li>Show:</li>
							<li><a data-filter="all" href="#" class="active">All <span>(<?php echo count($judges) ?>)</span></a></li>
							<li><a data-filter="complete" href="#">Completed <span>(<?php echo $complete_judge ?>)</span></a></li>
							<li><a data-filter="incomplete" href="#">In Progress <span>(<?php echo (count($judges)-$complete_judge); ?>)</span></a></li>
						</ul>
					</div>
					<div class="table-item-container">
						<table id="judges-table" style="width:100%">
							<thead>
								<tr>
									<th>Name</th>
									<th>Assigned</th>
									<th>Completed</th>
                                    <th>In Progress</th>
									<th>Incomplete</th>
									<th>Recused</th>
									<th>Last Log</th>
								</tr>
							</thead>
							<tbody class="judges">
							<?php
							foreach ( $judges as $judge ) {
								$user = get_user_by('id', $judge->judge_id);
								$incomplete = $judge->assigned - $judge->completed - $judge->recused - $judge->in_progress;
							?>
							<tr <?php if ($incomplete!=0) {echo 'class="incomplete-row"';} else {echo 'class="completed-row"';} ?>>
								<td><?php echo $user->user_firstname .' '. $user->user_lastname;?></td>
								<td><?php echo $judge->assigned; ?></td>
								<td><?php echo $judge->completed;?></td>
                                <td><?php echo $judge->in_progress;?></td>
								<td><?php echo $incomplete; ?></td>
								<td><?php echo $judge->recused; ?></td>
								<td><?php if ($user->user_last_login!="") { echo date("M jS, g:ia", $user->user_last_login); } ?></td>
							</tr>
							<?php } ?>
                            <?php if (count($judges)==0) : ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
				<div id="tbl-submission" class="table-item tab-pane fade">
					<?php
                        $submission = $wpdb->get_results(
                            "
                            SELECT
							r.story_id,
							COUNT(*) as judges,
							SUM(r.status) as reviewed,
							(AVG(r.answer_1) + AVG(r.answer_2) + AVG(r.answer_3) + AVG(r.answer_4))/4 as average_score
							FROM {$wpdb->prefix}csl_story_reviews r
							WHERE r.ID
							NOT IN (
								SELECT r.ID
								FROM {$wpdb->prefix}csl_story_reviews r
								WHERE r.recused_handled = 1 AND r.recused = 1
							)
							GROUP BY r.story_id
                            "
                        );
                        $sub_count = 0;
                        foreach($submission as $count) {
                            if ($count->judges==$count->reviewed) {
                                $sub_count++;
                            }
                        }
					?>
					<div class="category-menu dash-filter">
						<h3>Submissions</h3>
						<ul>
							<li>Show:</li>
							<li><a data-filter="all" href="#" class="active">All <span>(<?php echo count($submission); ?>)</span></a></li>
							<li><a data-filter="complete" href="#">Completed <span>(<?php echo $sub_count; ?>)</span></a></li>
							<li><a data-filter="incomplete" href="#">In Progress <span>(<?php echo count($submission) - $sub_count; ?>)</span></a></li>
						</ul>
					</div>
					<div class="table-item-container">
						<?php if (count($submission)>0): ?>
							<table style="width:100%">
								<thead>
									<tr>
										<th>Story Author</th>
										<th>No. Of Judges</th>
										<th>Reviews</th>
										<th>Average Score</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($submission as $sub_row):?>
                                    <tr <?php if ($sub_row->judges != $sub_row->reviewed) {echo 'class="incomplete-row"';} else {echo 'class="completed-row"';} ?>>
                                      <td><?php the_field('story_full_name', $sub_row->story_id) ?></td>
                                      <td><?php echo $sub_row->judges ?></td>
                                      <td><?php echo $sub_row->reviewed ?></td>
                                      <td><?php echo round($sub_row->average_score, 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
								</tbody>
							</table>
                        <?php else: ?>
                            <h2>There are no submissions to display.</h2>
						<?php endif; ?>
					</div>
				</div>
				<div id="tbl-requests" class="table-item fade in">
                    <?php
                    global $wpdb;
                    $prefix = $wpdb->prefix;
                    $requests = $wpdb->get_results(
                        "
                        SELECT *
                        FROM (SELECT
                        r.judge_id,
                        COUNT(*) as assigned,
                        SUM(r.status) as completed,
                        SUM(r.recused) as recused
                        FROM {$wpdb->prefix}csl_story_reviews r
                        GROUP BY r.judge_id ) as s INNER JOIN {$wpdb->prefix}csl_judge_requests req ON s.judge_id = req.judge_id;
                        "
                    );
                    $complete_requests = $wpdb->get_results(
                        "
                        SELECT *
                        FROM {$wpdb->prefix}csl_judge_requests r
                        WHERE r.request_status = 1
                        "
                    );
                    ?>
					<div class="category-menu dash-filter">
						<h3>Requests</h3>
                        <ul>
                            <li>Show:</li>
                            <li><a data-filter="all" href="#" class="active">All <span>(<?php echo count($requests); ?>)</span></a></li>
                            <li><a data-filter="complete" href="#">Completed <span>(<?php echo count($complete_requests); ?>)</span></a></li>
                            <li><a data-filter="incomplete" href="#">Pending <span>(<?php echo count($requests) - count($complete_requests); ?>)</span></a></li>
                        </ul>
					</div>
					<div class="table-item-container">
						<?php if (count($requests)>0): ?>
							<table style="width:100%">
								<thead>
									<tr>
										<th>Judge Name</th>
										<th>Email</th>
										<th>Reviewed</th>
										<th>Unreviewed</th>
										<th>Recused</th>
										<th>Total</th>
										<th>Requested Stories</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($requests as $req_row):
											$judge = get_user_by('id', $req_row->judge_id);
                                            $incomplete = $req_row->assigned - $req_row->completed - $req_row->recused;
									?>
										<tr data-requests="<?php echo $req_row->story_requests ?>" data-judgeid="<?php echo $req_row->judge_id ?>" data-judge="<?php echo $judge->user_firstname . ' ' . $judge->user_lastname; ?>" class="reassign-row <?php if ($req_row->request_status == 0) {echo 'incomplete-row';} else {echo 'completed-row';} ?>">
											<td><?php echo $judge->user_firstname . ' ' . $judge->user_lastname; ?></td>
											<td><?php echo $judge->user_email ?></td>
											<td><?php echo $req_row->completed ?></td>
											<td><?php echo $incomplete ?></td>
											<td><?php echo $req_row->recused ?></td>
											<td><?php echo $req_row->assigned ?></td>
											<td><?php echo $req_row->story_requests ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php else: ?>
							<h2>There are no request to display.</h2>
						<?php endif; ?>
					</div>
				</div>
				<div id="tbl-recuses" class="table-item fade in">
                    <?php
                    $recused = $wpdb->get_results(
                        "
                        SELECT *
                        FROM {$wpdb->prefix}csl_story_reviews
                        WHERE recused = 1
                        "
                    );
                    $complete_recuse = $wpdb->get_results(
                        "
                        SELECT *
                        FROM {$wpdb->prefix}csl_story_reviews r
                        WHERE r.recused = 1
                        AND r.status = 1
                        "
                    );
                    ?>
					<div class="category-menu dash-filter">
						<h3>Recuses</h3>
                        <ul>
                            <li>Show:</li>
                            <li><a data-filter="all" href="#" class="active">All <span>(<?php echo count($recused); ?>)</span></a></li>
                            <li><a data-filter="complete" href="#">Completed <span>(<?php echo count($complete_recuse); ?>)</span></a></li>
                            <li><a data-filter="incomplete" href="#">Pending <span>(<?php echo count($recused) - count($complete_recuse); ?>)</span></a></li>
                        </ul>
					</div>
					<div class="table-item-container">
						<?php if (count($recused)>0): ?>
							<table style="width:100%">
								<thead>
									<tr>
										<th>Judge Name</th>
										<th>Author Name</th>
										<th>Date Recused</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($recused as $recused_row):
										$judge = get_user_by('id', $recused_row->judge_id);
                                    ?>
                                    <tr data-id="<?php echo $recused_row->ID; ?>" data-judge="<?php echo $judge->user_firstname . " " . $judge->user_lastname ?>" data-judgeid="<?php echo $recused_row->judge_id ?>" data-author="<?php the_field('story_full_name', $recused_row->story_id); ?>" data-storyid="<?php echo $recused_row->story_id; ?>" class="recused-row <?php if ($recused_row->recused_handled != 1) {echo 'incomplete-row';} else {echo 'completed-row';} ?>" >
                                        <td><?php echo $judge->user_firstname . " " . $judge->user_lastname ?></td>
                                        <td><?php the_field('story_full_name', $recused_row->story_id); ?></td>
                                        <td><?php echo date("M jS, g:ia", $recused_row->date_recused); ?></td>
                                    </tr>
									<?php endforeach; ?>
								</tbody>
							</table>
                         <?php else: ?>
                                <h2>There are no recuses to display.</h2>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<?php if (count($judges)==0): ?>
			<div class="no-reviews">
				<h2>There are no stories assigned to the judges, please enter the number of desired revisions and click the auto-assign button</h2>
				<div class="auto-assign-container">
					<span>The number of revisions should not be greater than <?php echo count($blogusers); ?></span>
					<input type="number" class="no-review" name="no-review" value="">
					<a href="#" class="orange autoassing-btn">Auto-Assign<i class="fa fa-refresh fa-spin fa-fw"></i></a>
				</div>
			</div>
		<?php endif; ?>
		<?php else: ?>
			<div class="no-judges">
				<h2>There are no judges available. Please add a judge to assign to this story.</h2>
			</div>
		<?php endif; ?>
	</main><!-- /main -->
<?php get_template_part('molecules/admin', 'popups'); ?>
<?php
get_footer(); ?>
