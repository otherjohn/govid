@extends('site.layouts.default')

{{-- Content --}}
@section('content')
@foreach ($clients as $client)
<div class="row">
	<div class="col-md-8">
		<!-- Post Title -->
		<div class="row">
			<div class="col-md-8">
				<h4><strong><a href="{{{ $client->url() }}}">{{ String::title($client->name) }}</a></strong></h4>
			</div>
		</div>
		<!-- ./ client title -->

		<!-- Post Content -->
		<div class="row">
			<div class="col-md-2">
				<a href="{{{ $client->url() }}}" class="thumbnail"><img src="http://placehold.it/260x180" alt=""></a>
			</div>
			<div class="col-md-6">
				<p>
					{{ String::tidy(Str::limit($client->content(), 200)) }}
				</p>
				<p><a class="btn btn-mini btn-default" href="{{{ $client->url() }}}">Read more</a></p>
			</div>
		</div>
		<!-- ./ client content -->

		<!-- Post Footer -->
		<div class="row">
			<div class="col-md-8">
				<p></p>
				<p>
					<span class="glyphicon glyphicon-calendar"></span> <!--Sept 16th, 2012-->{{{ $client->date() }}}
				</p>
			</div>
		</div>
		<!-- ./ client footer -->
	</div>
</div>

<hr />
@endforeach

@stop
