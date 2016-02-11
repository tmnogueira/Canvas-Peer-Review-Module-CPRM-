<div class="container-fluid">
	<div class="panel panel-default" style="margin-top:5px;">
		<div class="panel-body" id="test">
			<div class="row" style="margin-top:15px;">
				<div class="col-md-3" style="float:left;">
					<div class="peerReview-side-menu">
						<div style="padding-bottom:2px;">
							<button type="button" class="btn btn-default feedback-button-fixes" id="single">
								Create Rubric
							</button>
						</div>
						<div style="padding-top:2px;">
							<button type="button" class="btn btn-default feedback-button-fixes" id="group">
								Load Rubric
							</button>
						</div>
						<div style="padding-top:2px;">
							<button type="button" class="btn btn-default feedback-button-fixes" id="group">
								Assign Group
							</button>
						</div>
						<div style="padding-top:2px;">
							<button type='submit' class='btn btn-default'>Submit</button>
						</div>
					</div>
				</div>
				<?php
				
				$db = Db::getInstance();
				$query = 'SELECT * FROM Test1 WHERE reviewName="cs462"';

				foreach ($db->query($query) as $row) {
					//only pulls one row
					$iter = $row['fieldsUsed'];
				
				?>
				<form action="" method="post">
					<div class="col-md-7" style="float:left;">
						<div style="width:100%;">
							<table class="table table-striped table-condensed">
								<thead>
									<tr>
										<th>Evaluation Criteria</th>
										<th>Points Possible</th>
										<!--<th>Points Earned</th>-->
									</tr>
								</thead>
								<tbody>
									<?php
										for ($i = 0; $i < $iter; $i++) {
											echo "<tr>";
											echo "<td class='feedback-tb-top-padding'>" . $row['field' . ($i + 1)] . "</td>";
											echo "<td><input type='text' style='width:20%' class='form-control' value='" . $row['pMax' . ($i + 1)] . "' /></td>";
											//echo "<td><input type='text' style='width:20%' class='form-control' value='" . $row['pEarn' . ($i + 1)] . "' /></td>";
											echo "</tr>";
										}
										
									?>
								</tbody>
							</table>
						</div>
					</div>
					<?php } ?>
				
					<button type='button' class='btn btn-default' onclick="addRowRubric()">Add Row</button>
				</form>
			</div>
		</div>
	</div>
</div>