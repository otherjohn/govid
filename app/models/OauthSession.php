<?php

use Illuminate\Support\Facades\URL;
use League\OAuth2\Server\Storage\SessionInterface;

class OauthSession extends Eloquent implements SessionInterface{
    protected $table = 'oauth_sessions';



   /**
     * Create a new session
     * @param  string $clientId  The client ID
     * @param  string $ownerType The type of the session owner (e.g. "user")
     * @param  string $ownerId   The ID of the session owner (e.g. "123")
     * @return int               The session ID
     */
    public function createSession($clientId, $ownerType, $ownerId){

    	$session_id = DB::table('oauth_sessions')->insertGetId(array('client_id' => $clientId, 'owner_type' => $ownerType, 'owner_id' => $ownerId));

    	return $session_id;
    }

    /**
     * Delete a session
     *
     * Example SQL query:
     *
     * <code>
     * DELETE FROM oauth_sessions WHERE client_id = :clientId AND owner_type = :type AND owner_id = :typeId
     * </code>
     *
     * @param  string $clientId  The client ID
     * @param  string $ownerType The type of the session owner (e.g. "user")
     * @param  string $ownerId   The ID of the session owner (e.g. "123")
     * @return void
     */
    public function deleteSession($clientId, $ownerType, $ownerId){

    	DB::table('oauth_sessions')->where('client_id', '=', $clientId)
    								->where('owner_type', '=', $ownerType)
    								->where('owner_id', '=', $ownerId)->delete();

    }

    /**
     * Associate a redirect URI with a session
     *
     * Example SQL query:
     *
     * <code>
     * INSERT INTO oauth_session_redirects (session_id, redirect_uri) VALUE (:sessionId, :redirectUri)
     * </code>
     *
     * @param  int    $sessionId   The session ID
     * @param  string $redirectUri The redirect URI
     * @return void
     */
    public function associateRedirectUri($sessionId, $redirectUri){
    	$insert_id = DB::table('oauth_session_redirects')->insertGetId(array('session_id' => $sessionId, 'redirect_uri' => $redirectUri));
    }

    /**
     * Associate an access token with a session
     *
     * Example SQL query:
     *
     * <code>
     * INSERT INTO oauth_session_access_tokens (session_id, access_token, access_token_expires)
     *  VALUE (:sessionId, :accessToken, :accessTokenExpire)
     * </code>
     *
     * @param  int    $sessionId   The session ID
     * @param  string $accessToken The access token
     * @param  int    $expireTime  Unix timestamp of the access token expiry time
     * @return int                 The access token ID
     */
    public function associateAccessToken($sessionId, $accessToken, $expireTime){
    	$insert_id = DB::table('oauth_session_access_tokens')->insertGetId(array('session_id' => $sessionId, 'access_token' => $accessToken, 'access_token_expires' => $expireTime));

    	return $insert_id;
    }

    /**
     * Associate a refresh token with a session
     *
     * Example SQL query:
     *
     * <code>
     * INSERT INTO oauth_session_refresh_tokens (session_access_token_id, refresh_token, refresh_token_expires,
     *  client_id) VALUE (:accessTokenId, :refreshToken, :expireTime, :clientId)
     * </code>
     *
     * @param  int    $accessTokenId The access token ID
     * @param  string $refreshToken  The refresh token
     * @param  int    $expireTime    Unix timestamp of the refresh token expiry time
     * @param  string $clientId      The client ID
     * @return void
     */
    public function associateRefreshToken($accessTokenId, $refreshToken, $expireTime, $clientId){
    	$insert_id = DB::table('oauth_session_refresh_tokens')->insertGetId(array('session_access_token_id' => $accessTokenId, 'refresh_token' => $refreshToken, 'refresh_token_expires' => $expireTime));
    }

    /**
     * Assocate an authorization code with a session
     *
     * Example SQL query:
     *
     * <code>
     * INSERT INTO oauth_session_authcodes (session_id, auth_code, auth_code_expires)
     *  VALUE (:sessionId, :authCode, :authCodeExpires)
     * </code>
     *
     * @param  int    $sessionId  The session ID
     * @param  string $authCode   The authorization code
     * @param  int    $expireTime Unix timestamp of the access token expiry time
     * @return int                The auth code ID
     */
    public function associateAuthCode($sessionId, $authCode, $expireTime){
    	$insert_id = DB::table('oauth_session_authcodes')->insertGetId(array('session_id' => $sessionId, 'auth_code' => $authCode, 'auth_code_expires' => $expireTime));
    }

    /**
     * Remove an associated authorization token from a session
     *
     * Example SQL query:
     *
     * <code>
     * DELETE FROM oauth_session_authcodes WHERE session_id = :sessionId
     * </code>
     *
     * @param  int    $sessionId   The session ID
     * @return void
     */
    public function removeAuthCode($sessionId){
    	DB::table('oauth_session_authcodes')->where('session_id', '=', $sessionId)->delete();
    }

    /**
     * Validate an authorization code
     *
     * Example SQL query:
     *
     * <code>
     * SELECT oauth_sessions.id AS session_id, oauth_session_authcodes.id AS authcode_id FROM oauth_sessions
     *  JOIN oauth_session_authcodes ON oauth_session_authcodes.`session_id` = oauth_sessions.id
     *  JOIN oauth_session_redirects ON oauth_session_redirects.`session_id` = oauth_sessions.id WHERE
     * oauth_sessions.client_id = :clientId AND oauth_session_authcodes.`auth_code` = :authCode
     *  AND `oauth_session_authcodes`.`auth_code_expires` >= :time AND
     *  `oauth_session_redirects`.`redirect_uri` = :redirectUri
     * </code>
     *
     * Expected response:
     *
     * <code>
     * array(
     *     'session_id' =>  (int)
     *     'authcode_id'  =>  (int)
     * )
     * </code>
     *
     * @param  string     $clientId    The client ID
     * @param  string     $redirectUri The redirect URI
     * @param  string     $authCode    The authorization code
     * @return array|bool              False if invalid or array as above
     */
    public function validateAuthCode($clientId, $redirectUri, $authCode){
    	$results = DB::select('SELECT oauth_sessions.`id` AS session_id, oauth_session_authcodes.`id` AS authcode_id FROM oauth_sessions
     								JOIN oauth_session_authcodes ON oauth_session_authcodes.`session_id` = oauth_sessions.`id`
     								JOIN oauth_session_redirects ON oauth_session_redirects.`session_id` = oauth_sessions.`id`
     							WHERE oauth_sessions.`client_id` = ? AND oauth_session_authcodes.`auth_code` = ? AND `oauth_session_redirects`.`redirect_uri` = ?',
     								array($clientId, $authCode, $redirectUri));

        
        if(empty($results)){return false;}
        $results = $results[0];
     	$response['session_id'] = $results->session_id;
     	$response['authcode_id'] = $results->authcode_id;
     	return $response;
    }

    /**
     * Validate an access token
     *
     * Example SQL query:
     *
     * <code>
     * SELECT session_id, oauth_sessions.`client_id`, oauth_sessions.`owner_id`, oauth_sessions.`owner_type`
     *  FROM `oauth_session_access_tokens` JOIN oauth_sessions ON oauth_sessions.`id` = session_id WHERE
     *  access_token = :accessToken AND access_token_expires >= UNIX_TIMESTAMP(NOW())
     * </code>
     *
     * Expected response:
     *
     * <code>
     * array(
     *     'session_id' =>  (int),
     *     'client_id'  =>  (string),
     *     'owner_id'   =>  (string),
     *     'owner_type' =>  (string)
     * )
     * </code>
     *
     * @param  string     $accessToken The access token
     * @return array|bool              False if invalid or an array as above
     */
    public function validateAccessToken($accessToken){
    	$results = DB::select('SELECT session_id, oauth_sessions.`client_id`, oauth_sessions.`owner_id`, oauth_sessions.`owner_type`
    							FROM `oauth_session_access_tokens` JOIN oauth_sessions ON oauth_sessions.`id` = session_id
    							WHERE access_token = ? AND access_token_expires >= UNIX_TIMESTAMP(NOW())', array($accessToken));

     	if(empty($results)){return false;}
        $results = $results[0];
        $response['session_id'] = $results->session_id;
        $response['client_id'] = $results->client_id;
        $response['owner_id'] = $results->owner_id;
        $response['owner_type'] = $results->owner_type;
    }

    /**
     * Removes a refresh token
     *
     * Example SQL query:
     *
     * <code>
     * DELETE FROM `oauth_session_refresh_tokens` WHERE refresh_token = :refreshToken
     * </code>
     *
     * @param  string $refreshToken The refresh token to be removed
     * @return void
     */
    public function removeRefreshToken($refreshToken){
    	DB::table('oauth_session_refresh_tokens')->where('refresh_token', '=', $refreshToken)->delete();
    }

    /**
     * Validate a refresh token
     *
     * Example SQL query:
     *
     * <code>
     * SELECT session_access_token_id FROM `oauth_session_refresh_tokens` WHERE refresh_token = :refreshToken
     *  AND refresh_token_expires >= UNIX_TIMESTAMP(NOW()) AND client_id = :clientId
     * </code>
     *
     * @param  string   $refreshToken The refresh token
     * @param  string   $clientId     The client ID
     * @return int|bool               The ID of the access token the refresh token is linked to (or false if invalid)
     */
    public function validateRefreshToken($refreshToken, $clientId){
    	$results = DB::select('SELECT session_access_token_id
    							FROM `oauth_session_refresh_tokens`
    							WHERE refresh_token = ? AND refresh_token_expires >= UNIX_TIMESTAMP(NOW())
    							AND client_id = ?', array($refreshToken, $clientId));

     	if(empty($results)){return false;}

     	return $results[0]->session_access_token_id;
    }

    /**
     * Get an access token by ID
     *
     * Example SQL query:
     *
     * <code>
     * SELECT * FROM `oauth_session_access_tokens` WHERE `id` = :accessTokenId
     * </code>
     *
     * Expected response:
     *
     * <code>
     * array(
     *     'id' =>  (int),
     *     'session_id' =>  (int),
     *     'access_token'   =>  (string),
     *     'access_token_expires'   =>  (int)
     * )
     * </code>
     *
     * @param  int    $accessTokenId The access token ID
     * @return array
     */
    public function getAccessToken($accessTokenId){
    	$results = DB::select('SELECT * FROM `oauth_session_access_tokens` WHERE `id` = ?', array($accessTokenId));

     	if(empty($results)){return false;}
        $results = $results[0];
        $response = array('id' => $results->id,
                            'session_id' => $results->session_id,
                            'access_token' => $results->access_token,
                            'access_token_expires' => $results->access_token_expires);
     	return $response;
    }

    /**
     * Associate scopes with an auth code (bound to the session)
     *
     * Example SQL query:
     *
     * <code>
     * INSERT INTO `oauth_session_authcode_scopes` (`oauth_session_authcode_id`, `scope_id`) VALUES
     *  (:authCodeId, :scopeId)
     * </code>
     *
     * @param  int $authCodeId The auth code ID
     * @param  int $scopeId    The scope ID
     * @return void
     */
    public function associateAuthCodeScope($authCodeId, $scopeId){
    	$insert_id = DB::table('oauth_session_authcode_scopes')->insertGetId(array('oauth_session_authcode_id' => $authCodeId, 'scope_id' => $scopeId));
    }

    /**
     * Get the scopes associated with an auth code
     *
     * Example SQL query:
     *
     * <code>
     * SELECT scope_id FROM `oauth_session_authcode_scopes` WHERE oauth_session_authcode_id = :authCodeId
     * </code>
     *
     * Expected response:
     *
     * <code>
     * array(
     *     array(
     *         'scope_id' => (int)
     *     ),
     *     array(
     *         'scope_id' => (int)
     *     ),
     *     ...
     * )
     * </code>
     *
     * @param  int   $oauthSessionAuthCodeId The session ID
     * @return array
     */
    public function getAuthCodeScopes($oauthSessionAuthCodeId){
    	$results = DB::select('SELECT scope_id FROM `oauth_session_authcode_scopes` WHERE oauth_session_authcode_id = ?', array($oauthSessionAuthCodeId));
        
        $response = array();
     	
        foreach ($results as $result) {
            $response[] = array('scope_id' => $result->scope_id);
        }
        return $response;
    }

    /**
     * Associate a scope with an access token
     *
     * Example SQL query:
     *
     * <code>
     * INSERT INTO `oauth_session_token_scopes` (`session_access_token_id`, `scope_id`) VALUE (:accessTokenId, :scopeId)
     * </code>
     *
     * @param  int    $accessTokenId The ID of the access token
     * @param  int    $scopeId       The ID of the scope
     * @return void
     */
    public function associateScope($accessTokenId, $scopeId){
    	$insert_id = DB::table('oauth_session_token_scopes')->insertGetId(array('session_access_token_id' => $accessTokenId, 'scope_id' => $scopeId));
    }

    /**
     * Get all associated access tokens for an access token
     *
     * Example SQL query:
     *
     * <code>
     * SELECT oauth_scopes.* FROM oauth_session_token_scopes JOIN oauth_session_access_tokens
     *  ON oauth_session_access_tokens.`id` = `oauth_session_token_scopes`.`session_access_token_id`
     *  JOIN oauth_scopes ON oauth_scopes.id = `oauth_session_token_scopes`.`scope_id`
     *  WHERE access_token = :accessToken
     * </code>
     *
     * Expected response:
     *
     * <code>
     * array (
     *     array(
     *         'id'     =>  (int),
     *         'scope'  =>  (string),
     *         'name'   =>  (string),
     *         'description'    =>  (string)
     *     ),
     *     ...
     *     ...
     * )
     * </code>
     *
     * @param  string $accessToken The access token
     * @return array
     */
    public function getScopes($accessToken){
    	$results = DB::select('SELECT oauth_scopes.*
    							FROM oauth_session_token_scopes JOIN oauth_session_access_tokens
     							ON oauth_session_access_tokens.`id` = `oauth_session_token_scopes`.`session_access_token_id` JOIN oauth_scopes
     							ON oauth_scopes.id = `oauth_session_token_scopes`.`scope_id`
     							WHERE access_token = ?', array($accessToken));

        $response = array();
     	
        foreach ($results as $result) {
            $response[] = array('id' => $result->id,
                                'scope' => $result->scope,
                                'name' => $result->name,
                                'description' => $result->description,
                                );
        }
        return $response;
     	
    }

}