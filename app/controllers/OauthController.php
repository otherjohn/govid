<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OauthController extends BaseController {

	 /**
     * The Auth Server Object
     * @var Authorization
     */
    protected $authserver;
    protected $resource;

	//In your controller constuctor you should instantiate the auth server:
    public function __construct(){
    // Initiate the request handler which deals with $_GET, $_POST, etc
    //$this->authserver->getRequest() = new League\OAuth2\Server\Util\Request($_GET);
    
    // Initiate a new database connection
    //$db = new League\OAuth2\Server\Storage\PDO\Db('mysql://HLTT7BQtMNFyT:fYu354ssAPXQIIByv@localhost/govid');

    // Create the auth server, the three parameters passed are references
    //  to the storage models
    $this->authserver = new League\OAuth2\Server\Authorization( new Client, new OauthSession, new Scope);

    // Enable the authorization code grant type
    $this->authserver->addGrantType(new League\OAuth2\Server\Grant\AuthCode());
    $this->authserver->setRequest(new League\OAuth2\Server\Util\Request($_GET,$_POST,array(),array(),
                                        array('HTTP_AUTHORIZATION' => $_ENV['HTTP_AUTHORIZATION']),
                                        array('Authorization' => $_ENV['HTTP_AUTHORIZATION'])));
    $this->authserver->setScopeDelimeter($scopeDelimeter = ',');
    $this->authserver->requireStateParam();
    $this->authserver->requireNonceParam();
    $this->authserver->setDefaultScope('openid');
}


//Create your first route (for example “index” - which would resolve to /oauth).
public function action_index(){
    try {

        // Tell the auth server to check the required parameters are in the
        //  query string
        $params = $this->authserver->getGrantType('authorization_code')->checkAuthoriseParams($this->authserver->getRequest()->get());

        // Save the verified parameters to the user's session
        Session::put('client_id', $params['client_id']);
        Session::put('redirect_uri', $params['redirect_uri']);
        Session::put('response_type', $params['response_type']);
        Session::put('scopes', $params['scopes']);
        Session::put('state', $params['state']);
        Session::put('nonce', $params['nonce']);

        
        $user = Auth::user();
    
        if(empty($user->id)){
            // Redirect the user to the sign-in route
            return Redirect::to('signin');
        }

        // Redirect the user to the sign-in route
        return Redirect::to('authorise');

    } catch (Oauth2\Exception\ClientException $e) {

        // Throw an error here which says what the problem is with the
        //  auth params

    } catch (Exception $e) {

        // Throw an error here which has caught a non-library specific error

    }
}


//Next create a sign-in route (at /signin):
//In the sign-in form HTML page you should tell the user the name of the client that their signing into.
public function action_signin()
{
    // Retrieve the auth params from the user's session
    $params['client_id'] = Session::get('client_id');
    //$params['client_details'] = Session::get('client_details');
    $params['redirect_uri'] = Session::get('redirect_uri');
    $params['response_type'] = Session::get('response_type');
    $params['scopes'] = Session::get('scopes');
    $params['state'] = Session::get('state');
    $params['nonce'] = Session::get('nonce');

    //var_dump($params);die();

    // Check that the auth params are all present
    foreach ($params as $key=>$value) {
        if ($value === null) {
            // Throw an error because an auth param is missing - don't
            //  continue any further
        }
    }

    // Process the sign-in form submission
    if (Input::get('signin') !== null) {
        try {

            // Get username
            $u = Input::get('username');
            if ($u === null || trim($u) === '') {
                throw new Exception('please enter your username.');
            }

            // Get password
            $p = Input::get('password');
            if ($p === null || trim($p) === '') {
                throw new Exception('please enter your password.');
            }

            // Verify the user's username and password
            // Set the user's ID to a session

        } catch (Exception $e) {
            $params['error_message'] = $e->getMessage();
        }
    }

    // Get the user's ID from their session
    $params['user_id'] = Session::get('user_id');

    // User is signed in
    if ($params['user_id'] !== null) {

        // Redirect the user to /oauth/authorise route
        return Redirect::to('authorise');

    }

    // User is not signed in, show the sign-in form
    else {
        return View::make('oauth.signin', $params);
    }
}



//Once the user has signed in (if they didn’t already have an existing session) then
//they should be redirected the authorise route where the user explicitly gives
//permission for the client to act on their behalf.
//
//In the authorize form the user should again be told the name of the client
//and also list all the scopes (permissions) it is requesting.
public function action_authorise()
{
    // Retrieve the auth params from the user's session
    $params['client_id'] = Session::get('client_id');
    $params['redirect_uri'] = Session::get('redirect_uri');
    $params['response_type'] = Session::get('response_type');
    $params['scopes'] = Session::get('scopes');
    $params['state'] = Session::get('state');
    $params['nonce'] = Session::get('nonce');

    
    // Check that the auth params are all present
    foreach ($params as $key=>$value) {
        if ($value === null) {
            // Throw an error because an auth param is missing - don't
            //  continue any further
        }
    }

    // Get the user ID
    $params['user_id'] = Session::get('user_id');

    // User is not signed in so redirect them to the sign-in route (/oauth/signin)
    $user = Auth::user();
    
    if(empty($user->id)){
        return Redirect::to('/signin');
    }

    // Check if the client should be automatically approved
    //$autoApprove = (isset($params['client_details']['auto_approve']) and ($params['client_details']['auto_approve'] === '1')) ? true : false;
    // Process the authorise request if the user's has clicked 'approve' for the client
    if (Input::get('approve') !== null || $autoApprove === true) {

        // Generate an authorization code
        $code = $this->authserver->getGrantType('authorization_code')->newAuthoriseRequest('user', $user->id, $params);

        // Redirect the user back to the client with an authorization code
        return Redirect::to(
            League\OAuth2\Server\Util\RedirectUri::make($params['redirect_uri'],
            array(
                'code'  =>  $code,
                'state' =>  isset($params['state']) ? $params['state'] : '',
            )
        ));
    }

    // If the user has denied the client so redirect them back without an authorization code
    if (Input::get('deny') !== null) {
        return Redirect::to(
            League\OAuth2\Server\Util\RedirectUri::make($params['redirect_uri'],
            array(
                'error' =>  'access_denied',
                'error_message' =>  $this->authserver->getExceptionMessage('access_denied'),
                'state' =>  isset($params['state']) ? $params['state'] : ''
            )
        ));
    }

    // The client shouldn't automatically be approved and the user hasn't yet
    //  approved it so show them a form
    return View::make('oauth.authorise', $params);
}


//The final route to create is where the client exchanges the authorization code for an access token.
public function action_access_token()
{
    
    try {

        // Tell the auth server to issue an access token
        //$params = $this->authserver->getGrantType('authorization_code')->checkAuthoriseParams($this->authserver->getRequest()->get());
        $ttl = 3600*24; //Access Token Expires in a day
        $this->authserver->setAccessTokenTTL($ttl);
        $response = $this->authserver->issueAccessToken($this->authserver->getRequest()->get());

        //Convert ID token to JWT using client secret as key
        $client_secret = Client::find($this->authserver->getRequest()->get()['client_id'])->secret;

        $response['id_token'] = JWT::encode($response['id_token'], $client_secret);

    } catch (League\OAuth2\Server\Exception\ClientException $e) {

        // Throw an exception because there was a problem with the client's request
        $response = array(
            'error' =>  $this->authserver->getExceptionType($e->getCode()),
            'error_description' => $e->getMessage()
        );

        // Set the correct header
        header($this->authserver->getExceptionHttpHeaders($this->authserver->getExceptionType($e->getCode()))[0]);

    } catch (Exception $e) {

        // Throw an error when a non-library specific exception has been thrown
        $response = array(
            'error' =>  'undefined_error',
            'error_description' => $e->getMessage()
        );
    }

    header('Content-type: application/json');
    echo json_encode($response);
}


//the client will use this function to verify the ID Token provided during authorization
//They may also verify themselves, but if they ask this server to verify, it will be here
public function action_check_id(){
    
    try {

        $jwt = $this->authserver->getRequest()->get()['access_token'];

        //Get Token header and payload
        $content = JWT::decode($this->authserver->getRequest()->get()['access_token'], null, false);
        $client_id = DB::table('oauth_client_metadata')->where('key', 'website')->where('value', $content->aud)->first()->client_id;
        $client_secret = Client::find($client_id)->secret;

        //Verify Signature
        $response = JWT::decode($this->authserver->getRequest()->get()['access_token'], $client_secret);

    } catch (League\OAuth2\Server\Exception\ClientException $e) {

        // Throw an exception because there was a problem with the client's request
        $response = array(
            'error' =>  $this->authserver->getExceptionType($e->getCode()),
            'error_description' => $e->getMessage()
        );

        // Set the correct header
        header($this->authserver->getExceptionHttpHeaders($this->authserver->getExceptionType($e->getCode()))[0]);

    } catch (Exception $e) {

        // Throw an error when a non-library specific exception has been thrown
        $response = array(
            'error' =>  'undefined_error',
            'error_description' => $e->getMessage()
        );
    }

    header('Content-type: application/json');
    echo json_encode($response);
}

//Process UserInfo Requests
public function action_userinfo(){
    
    try {
        $this->resource = new League\OAuth2\Server\Resource($this->authserver->getStorage('session'));
        $this->resource->setRequest(new League\OAuth2\Server\Util\Request(
                $_GET,$_POST,array(),array(),array('HTTP_AUTHORIZATION' => $_ENV['HTTP_AUTHORIZATION']),array('Authorization' => $_ENV['HTTP_AUTHORIZATION'])));
        
        //Validate Access Token
        //Will throw an exception is token is invalid. No need for IF statement
        $this->resource->isValid();
        if ($this->resource->getOwnerType() != 'user') {
            throw new ModelNotFoundException("User Not Found");
        }

        $token = $this->resource->getAccessToken();
        $user = User::findOrFail($this->resource->getOwnerId());
        //$user = User::findOrFail(10);
        $scopes = $this->resource->getScopes();
        
        $openid = false;
        foreach ($scopes as $scope) {
            if($scope == 'openid'){
                $openid = true;
                break;
            }
        }

        //Clients making UserInfo requests MUST have the openid scope
        if(!$openid){
            throw new League\OAuth2\Server\Exception\ClientException(sprintf($this->authserver->getExceptionMessage('access_denied')), 2);            
        }

        $response = array();

        foreach ($scopes as $scope) {
            if($scope == 'openid'){
                $response["sub"] = $user->pid;
            }
            if($scope == 'email'){
                $response["email"] = $user->email;
                $response["verified"] = ($user->confirmed == 1)?true:false;
            }
            if($scope == 'profile'){
                //"name","given_name","family_name","address","city","state","zip","email","verified","phone"],
                $response["name"] = $user->first_name.' '.$user->last_name;
                $response["given_name"] = $user->first_name;
                $response["family_name"] = $user->last_name;
                $response["phone"] = $user->phone;
                $response["mobile"] = $user->mobile;
                $response["email"] = $user->email;
                $response["verified"] = ($user->confirmed == 1)?true:false;
                $response["address"] = $user->street.', '.$user->city.', '.strtoupper($user->state).' '.$user->zip;
                $response["street"] = $user->street;
                $response["city"] = $user->city;
                $response["state"] = $user->state;
                $response["zip"] = $user->zip;
            }
        }
        
        
        

    } catch (League\OAuth2\Server\Exception\ClientException $e) {

        // Throw an exception because there was a problem with the client's request
        $response = array(
            'error' =>  $this->authserver->getExceptionType($e->getCode()),
            'error_description' => $e->getMessage()
        );

        // Set the correct header
        header($this->authserver->getExceptionHttpHeaders($this->authserver->getExceptionType($e->getCode()))[0]);

    }catch(ModelNotFoundException $e){
        http_response_code(404);
        $response = array(
            'error' => "User Not Found"
        );
    }catch (Exception $e) {

        // Throw an error when a non-library specific exception has been thrown
        $response = array(
            'error' =>  'undefined_error',
            'error_description' => $e->getMessage()
        );
    }

    header('Content-type: application/json');
    echo json_encode($response);
}

//Process UserInfo Requests
public function action_configuration(){
    
    try {

        $response = array(  "issuer" => "http:\/\/gov.nellcorp.com",
                            "authorization_endpoint" => "http:\/\/gov.nellcorp.com\/oauth",
                            "token_endpoint" => "http:\/\/gov.nellcorp.com\/oauth\/token",
                            "token_endpoint_auth_methods_supported" => ["client_secret_post","client_secret_jwt","client_secret_basic"],
                            "token_endpoint_auth_signing_alg_values_supported" => ["HS256","HS512","HS384"],
                            "userinfo_endpoint" => "http:\/\/gov.nellcorp.com\/oauth\/userinfo",
                            "userinfo_signing_alg_values_supported" => ["HS256","HS512","HS384"],
                            "request_parameter_supported" => false,
                            "display_values_supported" => ["page"],
                            "response_types_supported" => ["code"],
                            "service_documentation" => "http:\/\/gov.nellcorp.com\/docs",
                            "registration_endpoint" => "http:\/\/gov.nellcorp.com\/oauth\/register",
                            "ui_locales_supported" => ["en"],
                            "claim_types_supported" => ["normal"],
                            "grant_types_supported" => ["authorization_code"],
                            "request_uri_parameter_supported" => false,
                            "acr_values_supported" => ["0"],
                            "subject_types_supported" => ["public"],
                            "response_modes_supported" => ["query","fragment"],
                            "claims_parameter_supported" => true,
                            "jwks_uri" => "http:\/\/gov.nellcorp.com\/oauth\/jwks",
                            "scopes_supported" => ["openid","profile","email"],
                            "claims_supported" => ["sub","iss","auth_time","acr","name","given_name","family_name","address","city","state","zip","email","verified","phone","mobile"],
                            "id_token_signing_alg_values_supported" => ["HS256","HS512","HS384"],
                            "require_request_uri_registration" => false);
        
        

    } catch (League\OAuth2\Server\Exception\ClientException $e) {

        // Throw an exception because there was a problem with the client's request
        $response = array(
            'error' =>  $this->authserver->getExceptionType($e->getCode()),
            'error_description' => $e->getMessage()
        );

        // Set the correct header
        header($this->authserver->getExceptionHttpHeaders($this->authserver->getExceptionType($e->getCode()))[0]);

    } catch (Exception $e) {

        // Throw an error when a non-library specific exception has been thrown
        $response = array(
            'error' =>  'undefined_error',
            'error_description' => $e->getMessage()
        );
    }

    header('Content-type: application/json');
    echo json_encode($response);
}

public function action_jwks(){
    
    try {

        $response = array('keys' => array(
                            "e"=>"AQAB",
                            "n"=>"kWp2zRA23Z3vTL4uoe8kTFptxBVFunIoP4t_8TDYJrOb7D1iZNDXVeEsYKp6ppmrTZDAgd-cNOTKLd4M39WJc5FN0maTAVKJc7NxklDeKc4dMe1BGvTZNG4MpWBo-taKULlYUu0ltYJuLzOjIrTHfarucrGoRWqM0sl3z2-fv9k",
                            "kty"=>"RSA",
                            "kid"=>"1"));
        

    } catch (League\OAuth2\Server\Exception\ClientException $e) {

        // Throw an exception because there was a problem with the client's request
        $response = array(
            'error' =>  $this->authserver->getExceptionType($e->getCode()),
            'error_description' => $e->getMessage()
        );

        // Set the correct header
        header($this->authserver->getExceptionHttpHeaders($this->authserver->getExceptionType($e->getCode()))[0]);

    } catch (Exception $e) {

        // Throw an error when a non-library specific exception has been thrown
        $response = array(
            'error' =>  'undefined_error',
            'error_description' => $e->getMessage()
        );
    }

    header('Content-type: application/json');
    echo json_encode($response);
}
}