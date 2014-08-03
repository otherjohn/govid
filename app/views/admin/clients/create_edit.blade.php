@extends('admin.layouts.modal')

{{-- Content --}}
@section('content')

  <div class="row">
      <div class="col-sm-10"><h1>Health.Gov</h1></div>
      <div class="col-sm-2"><a href="/users" class="pull-right"><img title="profile image" class="img-circle img-responsive" src="http://www.gravatar.com/avatar/28fd20ccec6865e2d5f0e1f4446eb7bf?s=100"></a></div>
    </div>
    <div class="row">
      <div class="col-sm-3"><!--left col-->
              
          <div class="panel panel-default">
            <div class="panel-heading">Details <i class="fa fa-link fa-1x"></i></div>
            <div class="panel-body">Website<br/><a href="http://health.gov">Health.gov</a></div>
            <div class="panel-body">Callback URL<br/><a href="http://health.gov/auth">health.gov/auth</a></div>
            <div class="panel-body">Admin Email<br/><a href="mailto:admin@health.gov">admin@health.gov</a></div>
          </div>
             
          
        </div><!--/col-3-->
      <div class="col-sm-9">
              <form class="form" method="post" action="@if (isset($client)){{ URL::to('admin/clients/' . $client->id . '/edit') }}@endif" autocomplete="off" id="registrationForm">
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                      <div class="form-group {{{ $errors->has('title') ? 'error' : '' }}}">
                          
                          <div class="col-xs-6">
                              <label for="name"><h4>App Name</h4></label>
                              <input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', isset($client) ? $client->name : null) }}}" />
                              {{{ $errors->first('name', '<span class="help-block">:message</span>') }}}
                          </div>
                      </div>
                      <div class="form-group {{{ $errors->has('website') ? 'has-error' : '' }}}">
                          
                          <div class="col-xs-6">
                            <label for="website"><h4>Website</h4></label>
                              <input type="text" class="form-control" name="website" id="website" value="{{{ Input::old('website', isset($client) ? $client->website : null) }}}">
                              {{{ $errors->first('website', '<span class="help-block">:message</span>') }}}
                          </div>
                      </div>
          
                      <div class="form-group {{{ $errors->has('callback') ? 'has-error' : '' }}}">
                          
                          <div class="col-xs-6">
                              <label for="callback"><h4>Callback URL</h4></label>
                              <input type="text" class="form-control" name="callback" id="callback" value="{{{ Input::old('callback', isset($client) ? $client->endpoint()->redirect_uri : null) }}}">
                              {{{ $errors->first('callback', '<span class="help-block">:message</span>') }}}
                          </div>
                      </div>
          
                      <div class="form-group {{{ $errors->has('email') ? 'has-error' : '' }}}">
                          
                          <div class="col-xs-6">
                              <label for="email"><h4>Admin Email</h4></label>
                              <input type="email" class="form-control" name="email" id="email" value="{{{ Input::old('email', isset($client) ? $client->email : null) }}}">
                              {{{ $errors->first('email', '<span class="help-block">:message</span>') }}}
                          </div>
                      </div>
                      <div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
                          
                          <div class="col-xs-12">
                              <label for="description"><h4>Description</h4></label>
                              <input type="text" class="form-control" name="description" id="description" value="{{{ Input::old('description', isset($client) ? $client->description : null) }}}">
                              {{{ $errors->first('description', '<span class="help-block">:message</span>') }}}
                          </div>
                      </div>
                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                                <element class="btn-cancel close_popup">Cancel</element>
                                <button type="reset" class="btn btn-default">Reset</button>
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                      </div>
                </form>      
        </div><!--/col-9-->
    @stop