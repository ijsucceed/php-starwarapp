<?php declare(strict_types = 1);

namespace App\Controllers\Frontend;

use Http\Request;
use Http\Response;
use App\Template\Renderer;
use Facebook\Facebook;

class Login
{
    private $request;
    private $response;
    private $renderer;

    public function __construct(
         Request $request, 
         Response $response,
         Renderer $renderer
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->renderer = $renderer;
    }
    public function show()
    {
       
        $html = $this->renderer->render('Login');

        // Initialize the Facebook PHP SDK v5.
        $fb = new Facebook([
            'app_id'                => '2199588096984369',
            'app_secret'            => 'fdffe2cfa23cca57b6c1cbf6b7d2e847',
            'default_graph_version' => 'v2.10',
        ]);
        
        $helper = $fb->getRedirectLoginHelper();
        
        // Requested permissions - optional
        $permissions = array(
            'email',
            'name',
            'user_location'
        );
        $callback = 'http://localhost/idea-hub/?logged=1';
        $loginUrl = $helper->getLoginUrl($callback, $permissions);


        // Set and display content.
        $this->response->setContent($html);

        foreach ($this->response->getHeaders() as $header) {
            header($header, false);
        }

        echo $this->response->getContent();

    }
}