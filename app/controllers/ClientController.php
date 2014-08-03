<?php

class ClientController extends BaseController {

    /**
     * Client Model
     * @var Client
     */
    protected $client;

    /**
     * User Model
     * @var User
     */
    protected $user;

    /**
     * Inject the models.
     * @param Client $client
     * @param User $user
     */
    public function __construct(Client $client, User $user)
    {
        parent::__construct();

        $this->client = $client;
        $this->user = $user;
    }
    
	/**
	 * Returns all the clients.
	 *
	 * @return View
	 */
	public function getIndex()
	{
		
		// Get all the clients
		$clients = $this->client->orderBy('created_at', 'DESC')->paginate(10);

		// Show the page
		return View::make('site/clients/index', compact('clients'));
	}

	/**
	 * View a post.
	 *
	 * @param  string  $slug
	 * @return View
	 * @throws NotFoundHttpException
	 */
	public function getView($slug)
	{
		// Get this blog post data
		$client = ClientMetadata::where('key', '=', 'slug')->where('value', '=', $slug)->first()->client()->first();
		//var_dump($client);die;

		// Check if the blog post exists
		if (is_null($client))
		{
			// If we ended up in here, it means that
			// a page or a blog post didn't exist.
			// So, this means that it is time for
			// 404 error page.
			return Client::abort(404);
		}

		// Show the page
		return View::make('site/clients/view_client', compact('client'));
	}

	/**
	 * View a blog post.
	 *
	 * @param  string  $slug
	 * @return Redirect
	 */
	public function postView($slug)
	{

        $user = $this->user->currentUser();

		// Get this blog post data
		$client = $this->client->where('slug', '=', $slug)->first();

		
		// Redirect to this blog post page
		return Redirect::to($slug);
	}
}
