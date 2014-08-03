<?php

class OauthController extends BaseController {

	 /**
     * The Auth Server Object
     * @var Authorization
     */
    protected $authserver;
    protected $request;

	//In your controller constuctor you should instantiate the auth server:
    public function __construct(){
    // Initiate the request handler which deals with $_GET, $_POST, etc
    $this->request = new League\OAuth2\Server\Util\Request($_GET);
    
    // Initiate a new database connection
    //$db = new League\OAuth2\Server\Storage\PDO\Db('mysql://HLTT7BQtMNFyT:fYu354ssAPXQIIByv@localhost/govid');

    // Create the auth server, the three parameters passed are references
    //  to the storage models
    $this->authserver = new League\OAuth2\Server\Authorization( new Client, new OauthSession, new Scope);

    // Enable the authorization code grant type
    $this->authserver->addGrantType(new League\OAuth2\Server\Grant\AuthCode());
}


//Create your first route (for example “index” - which would resolve to /oauth).
public function action_index(){
    try {

        // Tell the auth server to check the required parameters are in the
        //  query string
        $params = $this->authserver->getGrantType('authorization_code')->checkAuthoriseParams($this->request->get());
        

        // Save the verified parameters to the user's session
        Session::put('client_id', $params['client_id']);
        Session::put('client_details', $params['client_details']);
        Session::put('redirect_uri', $params['redirect_uri']);
        Session::put('response_type', $params['response_type']);
        Session::put('scopes', $params['scopes']);
        Session::put('state', $params['state']);

        
        // Redirect the user to the sign-in route
        return Redirect::to('oauth/signin');

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
    $params['client_details'] = Session::get('client_details');
    $params['redirect_uri'] = Session::get('redirect_uri');
    $params['response_type'] = Session::get('response_type');
    $params['scopes'] = Session::get('scopes');
    $params['state'] = Session::get('state');

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
        return Redirect::to('oauth/authorise');

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
    $params['client_details'] = Session::get('client_details');
    $params['redirect_uri'] = Session::get('redirect_uri');
    $params['response_type'] = Session::get('response_type');
    $params['scopes'] = Session::get('scopes');
    $params['state'] = Session::get('state');

    //dd($params);

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
        return Redirect::to('/oauth/signin');
    }

    // Check if the client should be automatically approved
    $autoApprove = (isset($params['client_details']['auto_approve']) and ($params['client_details']['auto_approve'] === '1')) ? true : false;
    // Process the authorise request if the user's has clicked 'approve' for the client
    if (Input::get('approve') !== null || $autoApprove === true) {

        // Generate an authorization code
        $code = $this->authserver->getGrantType('authorization_code')->newAuthoriseRequest('user', $user->id, $params);

        // Redirect the user back to the client with an authorization code
        return Redirect::to(
            League\OAuth2\Server\Util\RedirectUri::make($params['redirect_uri'],
            array(
                'code'  =>  $code,
                'state' =>  isset($params['state']) ? $params['state'] : ''
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
        //$params = $this->authserver->getGrantType('authorization_code')->checkAuthoriseParams($this->request->get());
        //dd($params);
        $ttl = 3600*24; //Access Token Expires in a day
        $response = $this->authserver->setAccessTokenTTL($ttl);
        $response = $this->authserver->issueAccessToken($this->request->get());
        //dd($response);

    } catch (League\OAuth2\Server\Exception\ClientException $e) {

        // Throw an exception because there was a problem with the client's request
        $response = array(
            'error' =>  $this->authserver->getExceptionType($e->getCode()),
            'error_description' => $e->getMessage()
        );

        // Set the correct header
        dd($response);
        header($this->authserver->getExceptionHttpHeaders($this->authserver->getExceptionType($e->getCode())));

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