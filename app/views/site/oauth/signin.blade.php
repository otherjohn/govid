@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get('user/user.login') }}} ::
@parent
@stop

{{-- Content --}}
@section('content')

      <!-- Main component for a primary marketing message or call to action -->
<div class="col-sm-4 oauth-login">
<form method="POST" action="{{{ URL::to('/user/login') }}}" accept-charset="UTF-8">
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
    <input type="hidden" name="oauth" value="authorise">
    <fieldset>
        <div class="form-group">
            <label for="email">{{{ Lang::get('confide::confide.username') }}}</label>
            <input class="form-control" tabindex="1" placeholder="{{{ Lang::get('confide::confide.username') }}}" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">
        </div>
        <div class="form-group">
        <label for="password">
            {{{ Lang::get('confide::confide.password') }}}
            <small>
                <a href="{{{ (Confide::checkAction('UserController@forgot_password')) ?: 'forgot' }}}">{{{ Lang::get('confide::confide.login.forgot_password') }}}</a>
            </small>
        </label>
        <input class="form-control" tabindex="2" placeholder="{{{ Lang::get('confide::confide.password') }}}" type="password" name="password" id="password">
        </div>
        
        @if ( Session::get('error') )
            <div class="alert alert-error">{{{ Session::get('error') }}}</div>
        @endif

        @if ( Session::get('notice') )
            <div class="alert">{{{ Session::get('notice') }}}</div>
        @endif
        <br/>
        <div class="form-group">
            <button tabindex="3" type="submit" class="btn btn-default">{{{ Lang::get('confide::confide.login.submit') }}}</button>
        </div>
    </fieldset>
</form>
</div>
<div class="col-sm-8 oauth-scope"> 
The Application {{Client::find(Session::get('client_id'))->first()->name}} would like to access the following information:<br/><br/>

    @foreach(ClientScope::where('client_id','=',Session::get('client_id'))->get() as $scope)

        @if(empty($scopes[$scope->scope->name]))
            {{$scopes[$scope->scope->name] = $scope->scope->description}} <br/>
        @endif
    @endforeach
<!--
    @if(count($scopes) < Scope::all()->count())
        
        The Application will not have access to the following:<br/><br/>
        
        @foreach(ClientScope::where('client_id','!=',Session::get('client_id'))->get() as $scope)
            @if(empty($scopes[$scope->scope->name]))
                {{$scopes[$scope->scope->name] = $scope->scope->description}}<br/>
            @endif
        @endforeach
    @endif
-->
                                    
</div>

@stop