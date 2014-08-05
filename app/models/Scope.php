<?php

use Illuminate\Support\Facades\URL;
use League\OAuth2\Server\Storage\ScopeInterface;

class Scope extends Eloquent implements ScopeInterface{

	protected $table = 'oauth_scopes';

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
		return nl2br($this->description);
	}

	public function clientscope()
    {
        return $this->hasMany('ClientScope', 'scope_id','id');
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
     * Return information about a scope
     * @param  string     $scope     The scope
     * @param  string     $clientId  The client ID (default = "null")
     * @param  string     $grantType The grant type used in the request (default = "null")
     * @return bool|array If the scope doesn't exist return false
     */
    public function getScope($scope, $clientId = null, $grantType = null){
		$results = DB::select('SELECT * FROM oauth_scopes WHERE scope = ?', array($scope));
    	if(empty($results)){return false;}

    	$response['id'] = $results[0]->id;
    	$response['scope'] = $results[0]->scope;
    	$response['name'] = $results[0]->name;
    	$response['description'] = $results[0]->description;

    	return $response;

	}

}
