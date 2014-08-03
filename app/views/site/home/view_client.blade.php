@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ String::title($client->title) }}} ::
@parent
@stop

{{-- Update the Meta Title --}}
@section('meta_title')
@parent

@stop

{{-- Update the Meta Description --}}
@section('meta_description')
@parent

@stop

{{-- Update the Meta Keywords --}}
@section('meta_keywords')
@parent

@stop

{{-- Content --}}
@section('content')
<h3>{{ $client->name }}</h3>

<p>{{ $client->content() }}</p>

<div>
	<span class="badge badge-info">Posted {{{ $client->date() }}}</span>
</div>

<hr />
@stop
