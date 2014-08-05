@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get('user/user.login') }}} ::
@parent
@stop

{{-- Content --}}
@section('content')
<div class="col-sm-4 oauth-login"></div>
<div class="col-sm-8 oauth-scope"> 
<form method="POST" action="{{action('OauthController@action_authorise')}}" accept-charset="UTF-8">
  <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
                      Allow {{Client::find(Session::get('client_id'))->first()->name}} to access this information?<br/><br/>

    @foreach(ClientScope::where('client_id','=',Session::get('client_id'))->get() as $scope)

        @if(empty($scopes[$scope->scope->name]))
            {{$scopes[$scope->scope->name] = $scope->scope->description}} <br/>
        @endif
    @endforeach
<!--
    @if(count($scopes) < Scope::all()->count())
        
        {{Client::find(Session::get('client_id'))->first()->name}} will not have access to:<br/><br/>
        
        @foreach(ClientScope::where('client_id','!=',Session::get('client_id'))->get() as $scope)
            @if(empty($scopes[$scope->scope->name]))
                {{$scopes[$scope->scope->name] = $scope->scope->description}}<br/>
            @endif
        @endforeach
    @endif
  -->
                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                                <button class="btn btn-lg btn-success" type="submit" name="approve"><i class="glyphicon glyphicon-ok-sign"></i> Authorize</button>
                                <button class="btn btn-lg" type="submit" name="deny"><i class="glyphicon glyphicon-repeat"></i> Deny</button>
                            </div>
                      </div>
                </form>
</div>
@stop