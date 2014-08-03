<?php

use Illuminate\Support\Facades\URL;
use League\OAuth2\Server\Storage\ClientInterface;

class Client extends Eloquent implements ClientInterface{

    //Use oauth_clients table to store clients
    protected $table = 'oauth_clients';

	/**
	 * Deletes a blog post and all
	 * the associated comments.
	 *
	 * @return bool
	 */
	public function delete()
	{
		
		// Delete the blog post
		return parent::delete();
	}

	/**
	 * Returns a formatted post content entry,
	 * this ensures that line breaks are returned.
	 *
	 * @return string
	 */
	public function content()
	{
		return nl2br($this->metadata()->where('key','=','description')->first()->value);

	}

	
    public function endpoint()
    {
        return $this->hasOne('ClientEndpoint', 'client_id','id');
    }

    public function scopes()
    {
        return $this->hasMany('ClientScope', 'client_id','id');
    }

    public function metadata()
    {
        return $this->hasMany('ClientMetadata', 'client_id','id');
    }

    /**
     * Get the date the post was created.
     *
     * @param \Carbon|null $date
     * @return string
     */
    public function date($date=null)
    {
        if(is_null($date)) {
            $date = $this->created_at;
        }

        return String::date($date);
    }

	/**
	 * Get the URL to the post.
	 *
	 * @return string
	 */
	public function url()
	{
		return Url::to($this->metadata()->where('key','=','slug')->first()->value);
	}

	/**
	 * Returns the date of the blog post creation,
	 * on a good and more readable format :)
	 *
	 * @return string
	 */
	public function created_at()
	{
		return $this->date($this->created_at);
	}

	/**
	 * Returns the date of the blog post last update,
	 * on a good and more readable format :)
	 *
	 * @return string
	 */
	public function updated_at()
	{
        return $this->date($this->updated_at);
	}


	 /**
     * Validate a client
     * @param  string     $clientId     The client's ID
     * @param  string     $clientSecret The client's secret (default = "null")
     * @param  string     $redirectUri  The client's redirect URI (default = "null")
     * @param  string     $grantType    The grant type used in the request (default = "null")
     * @return bool|array               Returns false if the validation fails, array on success
     */

     //getClient($authParams['client_id'], null, $authParams['redirect_uri'], $this->identifier);
public function getClient($clientId, $clientSecret = null, $redirectUri = null, $grantType = null)
    {
        $query = null;
        
        if (! is_null($redirectUri) && is_null($clientSecret)) {
            $query = DB::table('oauth_clients')
                        ->select(
                            'oauth_clients.id as id',
                            'oauth_clients.secret as secret',
                            'oauth_client_endpoints.redirect_uri as redirect_uri',
                            'oauth_clients.name as name')
                        ->join('oauth_client_endpoints', 'oauth_clients.id', '=', 'oauth_client_endpoints.client_id')
                        ->where('oauth_clients.id', $clientId)
                        ->where('oauth_client_endpoints.redirect_uri', $redirectUri);
         
        } elseif (! is_null($clientSecret) && is_null($redirectUri)) {
            $query = DB::table('oauth_clients')
                        ->select(
                            'oauth_clients.id as id',
                            'oauth_clients.secret as secret',
                            'oauth_clients.name as name')
                        ->where('oauth_clients.id', $clientId)
                        ->where('oauth_clients.secret', $clientSecret);
        } elseif (! is_null($clientSecret) && ! is_null($redirectUri)) {
            $query = DB::table('oauth_clients')
                        ->select(
                            'oauth_clients.id as id',
                            'oauth_clients.secret as secret',
                            'oauth_client_endpoints.redirect_uri as redirect_uri',
                            'oauth_clients.name as name')
                        ->join('oauth_client_endpoints', 'oauth_clients.id', '=', 'oauth_client_endpoints.client_id')
                        ->where('oauth_clients.id', $clientId)
                        ->where('oauth_clients.secret', $clientSecret)
                        ->where('oauth_client_endpoints.redirect_uri', $redirectUri);
        }

        if (! is_null($grantType)) {
            $query = $query->join('oauth_client_grants', 'oauth_clients.id', '=', 'oauth_client_grants.client_id')
                           ->join('oauth_grants', 'oauth_grants.id', '=', 'oauth_client_grants.grant_id')
                           ->where('oauth_grants.grant', $grantType);

        }



        $result = $query->first();
        
        if (is_null($result)) {
            return false;
        }

        $result = (object) $result;

        $metadata = DB::table('oauth_client_metadata')->where('client_id', '=', $result->id)->lists('value', 'key');

        return array(
            'client_id'     =>  $result->id,
            'client_secret' =>  $result->secret,
            'redirect_uri'  =>  (isset($result->redirect_uri)) ? $result->redirect_uri : null,
            'name'          =>  $result->name,
            'metadata'      =>  $metadata
        );
    }	

}
