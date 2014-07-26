@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get('user/user.login') }}} ::
@parent
@stop

{{-- Content --}}
@section('content')

      <!-- Main component for a primary marketing message or call to action -->
            <form class="form" action="{{ URL::to('user/login') }}" accept-charset="UTF-8" method="post" id="loginForm">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="email"><h4>{{ Lang::get('confide::confide.username_e_mail') }}</h4></label>
                              <input type="text" class="form-control" name="email" id="email" placeholder="{{ Lang::get('confide::confide.username_e_mail') }}" value="{{ Input::old('email') }}">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="password"><h4>{{ Lang::get('confide::confide.password') }}</h4></label>
                              <input type="password" class="form-control" name="password" id="password" placeholder="{{ Lang::get('confide::confide.password') }}" title="enter your password.">
                          </div>
                      </div>
                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                                <button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> {{ Lang::get('confide::confide.login.submit') }}</button>
                                <a class="btn btn-default" href="forgot">{{ Lang::get('confide::confide.login.forgot_password') }}</a>
                            </div>
                      </div>
                      @if ( Session::get('error') )
                        <div class="alert alert-danger">{{ Session::get('error') }}</div>
                      @endif

                      @if ( Session::get('notice') )
                        <div class="alert">{{ Session::get('notice') }}</div>
                      @endif
                </form>
@stop