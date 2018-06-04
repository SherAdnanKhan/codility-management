@extends('layouts.app')
@section('body')
	<!-- navbar-->

	<!-- Header Section-->
	<section class="dashboard-header section-padding">
		<div class="container-fluid">
			<div class="card">
				<div class="card-header">
					<h4 class="text-dark">Latest Task</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table">
							<thead>
							<tr>
								<th>#</th>
								<th>Employee</th>
								<th>Description</th>
								<th>Time Taken</th>
								<th>created On</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<th scope="row">1</th>
								<td>Sameer Khan</td>
								<td>Uploading, Parsing and Saving CV doc, docx and pdf text in db is done.</td>
								<td>08:00:00</td>
								<td>5/15/2018</td>


							</tr>
							<tr>
								<th scope="row">2</th>
								<td>Sameer Khan</td>
								<td>Uploading, Parsing and Saving CV doc, docx and pdf text in db is done.</td>
								<td>08:00:00</td>
								<td>5/15/2018</td>

							</tr>
							<tr>
								<th scope="row">3</th>
								<td>Sameer Khan</td>
								<td>Uploading, Parsing and Saving CV doc, docx and pdf text in db is done.</td>
								<td>08:00:00</td>
								<td>5/15/2018</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</section>
	<section class="statistics">
		<div class="container-fluid">
			<div class="row d-flex">
				<div class="col-md-4"></div>
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h4>Todays Attendence</h4>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table">
									<thead>
									<tr>
										<th>#</th>
										<th>Employee</th>
										<th>Check In</th>
										<th>Check Out</th>
									</tr>
									</thead>
									<tbody>
									<tr>
										<th scope="row">1</th>
										<td>Atta</td>
										<td>09:00:00</td>
										<td>04:00:00</td>
									</tr>
									<tr>
										<th scope="row">2</th>
										<td>Sufyan</td>
										<td>04:00:00</td>
										<td>04:00:00</td>
									</tr>
									<tr>
										<th scope="row">3</th>
										<td>Ali</td>
										<td>04:00:00</td>
										<td>04:00:00</td>
									</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

@endsection