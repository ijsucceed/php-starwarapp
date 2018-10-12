<?php declare(strict_types = 1);

namespace App\Controllers\Account;

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
        // Fb instance
        $fb = new Facebook([
            'app_id'                => '2199588096984369',
            'app_secret'            => 'fdffe2cfa23cca57b6c1cbf6b7d2e847',
            'default_graph_version' => 'v2.10',
        ]);
        $helper = $fb->getRedirectLoginHelper();
        // Requested permissions - optional
        $permissions = array(
            'email',
            'user_location'
        );
        // Callback url
        $callback = 'http://localhost:8000/callback/login/fb';
        $baseUrl = 'http://localhost:8000';
        $loginUrl = $helper->getLoginUrl($callback, $permissions);
        // Template data
        $data = [
            'BaseUrl' => $baseUrl,
            'loginUrl' => $loginUrl,
        ];
        // render data to Login view
        $html = $this->renderer->render('Login', $data);
        // Set content.
        $this->response->setContent($html);
    
        foreach ($this->response->getHeaders() as $header) {
            header($header, false);
        }
        // display result
        echo $this->response->getContent();
    }
    public function fbCallback() 
    {
        // fb instance
        $fb = new Facebook([
            'app_id'                => '2199588096984369',
            'app_secret'            => 'fdffe2cfa23cca57b6c1cbf6b7d2e847',
            'default_graph_version' => 'v2.10',
        ]);
        $helper = $fb->getRedirectLoginHelper();
        if (isset($_GET['state'])) {
            $helper->getPersistentDataHandler()->set('state', $_GET['state']);
        }
        
        // Handle Login Error
        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        
        if (! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }
        
        // Logged in
        // echo '<h3>Access Token</h3>';
        // var_dump($accessToken->getValue());
        
        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();
        
        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        // echo '<h3>Metadata</h3>';
        // var_dump($tokenMetadata);
        
        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId('2199588096984369');
        // If you know the user ID this access token belongs to, you can validate it here
        // $tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();
        
        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
                exit;
            }
            echo '<h3>Long-lived</h3>';
            var_dump($accessToken->getValue());
        }
        
        $_SESSION['fb_access_token'] = (string) $accessToken;

        // GET request.
        $res = $fb->get('/me', $_SESSION['fb_access_token'] );
        $user = $res->getGraphObject();
        echo '<br>' . $user->getProperty('email');

        // header('Location:8000/?name=' . $user->getProperty('email') );
    }
}