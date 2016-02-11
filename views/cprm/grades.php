<!-- This will be the grades table that shows the students' grades -->
<div class="container-fluid">

		<!-- 	Buttons for demo video purposes 
				Toggles profView or studentView 
				divs to reflect either what a professor
				would see, or what a student would see.
		-->
		<button type="button" class="btn-default" onClick="showProfessorGrades()">Professor View</button>
		<button type="button" class="btn-default" onClick="showStudentGrades()">Student View</button>
		<br></br>

	<!-- What a professor would see -->
	<div class="panel panel-default" style="margin-top:5px;" id="profView">
		<div class="panel-body">
			<!-- Search Bar -->
			<div class="row" style="width:800px">
					<label for="gradesSearch"> Search </label>
					<input type="text" class="form-control" id="gradesSearch" placeholder="Student">
			</div>
			
			<br></br>
			
			<!-- Import / Export Buttons -->
			<div class="row" style="width:800px">
				<button type="button" class="btn-default" id="importGrades">Import</button>
				<button type="button" class="btn-default" id="exportGrades">Export</button>
			</div>

			<br></br>
			
			<!-- Table in which grades data will be displayed -->
			<h2>Class Grades</h2>
				<table class="table-bordered" style="width:800px">
					<thead>
						<tr>
							<th>Student Name</th>
							<th>Student ID</th>
							<th>Peer Eval 1</th>
							<th>Peer Eval 2</th>
							<th>Peer Eval 3</th>
							<th>Peer Eval 4</th>
						</tr>
					</thead>
					
					<tbody id="grades-table-body-professor">
					
					</tbody>
				</table>
				
				<!-- Button to test loading table rows -->
				<br></br>
				<div class="container" hidden>
					<button type="button" class="btn-default" id="load-grades" onclick="loadGradesProf()">Load Grades [TEST]</button>
				</div>
			</div>	
	</div>
	
	<!-- Temporary Script that loads sample grades when the page loads -->
	<script type="text/javascript">
		loadProfessorGrades();
	</script>
	
	
	<!-- What a student would see. Hide by default -->
	<div class="panel panel-default" style="margin-top:5px; display:none;" id="studentView">
			<div class="panel-body">
				<h2>Student Grades</h2>
				<table class="table-bordered" style="width:800px">
					<thead>
						<tr>
							<th>Peer Review</th>
							<th>Description</th>
							<th>Criteria 1</th>
							<th>Criteria 2</th>
							<th>Criteria 3</th>
							<th>Criteria 4</th>
							<th>Total</th>
						</tr>
					</thead>
					
					<tbody id="grades-table-body-student">
					
					</tbody>
				</table>
			</div>
	</div>
	
</div>

<br></br>

