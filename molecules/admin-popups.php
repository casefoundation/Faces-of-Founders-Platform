<!-- Reassign Modal -->
<div class="modal fade fof-modal" id="reassign-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i>
			</button>
			<p><strong class="judge">judge</strong> requested <strong class="requests">15</strong> stories more.</p>
			<p>Please select an option:</p>
			<a href="#" class="btn orange from-other-judges">Reassign Stories from other Judges <i class="fa fa-refresh fa-spin fa-fw"></i></a>
			<a href="#" class="btn orange recused-stories-btn">Assign Recused Stories <i class="fa fa-refresh fa-spin fa-fw"></i></a>
		</div>
	</div>
</div>

<!-- Recused Modal -->
<div class="modal fade fof-modal" id="recused-modal" data-story="" data-id="" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i>
			</button>
			<p>The story from <strong class="author">author</strong> has been recused by <strong class="judge">judge</strong>.</p>
			<p>Select the judge whom you want to reassign this recused story:</p>
			<div class="select-container">
				<select name="judges-list" id="judges-list">
					<option value="">Select a judge</option>
				</select>
			</div>
			<a href="#" class="btn orange reassign-recuses">Assign Story <i class="fa fa-refresh fa-spin fa-fw"></i></a>
		</div>
	</div>
</div>

<!-- Other Judges Modal -->
<div class="modal fade fof-modal" id="other-judges-modal" data-story="" data-id="" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i>
			</button>
			<p><strong class="judge">judge</strong> requested <strong class="requests">15</strong> stories more.</p>
			<p>Select the judges to whom you want to reassign the stories:</p>
			<div class="select-container multiple">
				<select class="selectpicker" multiple>
					<option>Select a Judge</option>
				</select>
			</div>
			<p>Number of stories to reassign from each Judge:</p>
			<div class="select-container">
				<input type="number" min="1">
			</div>
			<a href="#" class="btn orange reassign-stories-other-judges">Assign Stories <i class="fa fa-refresh fa-spin fa-fw"></i></a>
		</div>
	</div>
</div>

<!-- Other Recused Stories -->
<div class="modal fade fof-modal" id="recused-stories-modal" data-story="" data-id="" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i>
			</button>
			<p><strong class="judge">judge</strong> requested <strong class="requests">15</strong> more stories. <br>There are <strong class="recused">0</strong> recused stories that this judge could review.</p>
			<p>Number of recused stories to reassign to this judge:</p>
			<div class="select-container">
				<input type="number" min="1" max="0">
			</div>
			<a href="#" class="btn orange reassign-recused-stories">Assign Stories <i class="fa fa-refresh fa-spin fa-fw"></i></a>
		</div>
	</div>
</div>

