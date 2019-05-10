@extends('layouts.app')
@section('title')
	<title> {{config('app.name')}} | Administrator</title>
@endsection

@section('body')
	@if (session('status'))
		<div class="alert alert-success">
			{{ session('status') }}
		</div>
	@endif
	<!-- navbar-->

	<!-- Header Section-->
	{{--<section class="dashboard-header section-padding">--}}
		{{--<div class="container-fluid">--}}
			{{--<div class="card">--}}
				{{--<div class="card-header">--}}
					{{--<h4 class="text-dark">Latest Task</h4>--}}
				{{--</div>--}}
				{{--<div class="card-body">--}}
					{{--<div class="table-responsive">--}}
						{{--<table class="table">--}}
							{{--<thead>--}}
							{{--<tr>--}}
								{{--<th>#</th>--}}
								{{--<th>Employee</th>--}}
								{{--<th>Description</th>--}}
								{{--<th>Time Taken</th>--}}
								{{--<th>created On</th>--}}
							{{--</tr>--}}
							{{--</thead>--}}
							{{--<tbody>--}}
							{{--<tr>--}}
								{{--<th scope="row">1</th>--}}
								{{--<td>Sameer Khan</td>--}}
								{{--<td>Uploading, Parsing and Saving CV doc, docx and pdf text in db is done.</td>--}}
								{{--<td>08:00:00</td>--}}
								{{--<td>5/15/2018</td>--}}


							{{--</tr>--}}
							{{--<tr>--}}
								{{--<th scope="row">2</th>--}}
								{{--<td>Sameer Khan</td>--}}
								{{--<td>Uploading, Parsing and Saving CV doc, docx and pdf text in db is done.</td>--}}
								{{--<td>08:00:00</td>--}}
								{{--<td>5/15/2018</td>--}}

							{{--</tr>--}}
							{{--<tr>--}}
								{{--<th scope="row">3</th>--}}
								{{--<td>Sameer Khan</td>--}}
								{{--<td>Uploading, Parsing and Saving CV doc, docx and pdf text in db is done.</td>--}}
								{{--<td>08:00:00</td>--}}
								{{--<td>5/15/2018</td>--}}
							{{--</tr>--}}
							{{--</tbody>--}}
						{{--</table>--}}
					{{--</div>--}}
				{{--</div>--}}
			{{--</div>--}}

		{{--</div>--}}
	{{--</section>--}}
	@if(isset($users))
	<section class="statistics" style="margin: auto;">
		<div class="container-fluid">
			<div class="row d-flex">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<div class="card">
						<div class="card-header">
							<h4>Employees Name</h4>
							<i style="font-weight: lighter; font-size: 12px">(whose have not submit their NTN NO:)</i>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table">
									<thead>
									<tr>
										<th>Employee</th>
										<th>Send Notification</th>
									</tr>
									</thead>
									<tbody>
									@foreach($users as $user => $value)
									<tr>
										<th scope="row">{{$value}}</th>
										<th> <a class="applicant_id" title="Send Email" href='{{route('send_mail_employee.no_ntn',$user)}}'><i class='fa fa-paper-plane'></i></a></td>
										</th>
									</tr>
									@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	@endif
@endsection