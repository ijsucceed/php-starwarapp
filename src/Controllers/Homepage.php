<?php declare(strict_types = 1);

namespace App\Controllers;

use Http\Request;
use Http\Response;
use App\Template\Renderer;
use Facebook\Facebook;

class Homepage
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
        $data = [
            'name' => $this->request->getParameter('name', 'stranger'),
        ];
        $html = $this->renderer->render('Sample', $data);

        $this->response->setContent($html);

        foreach ($this->response->getHeaders() as $header) {
            header($header, false);
        }

        echo $this->response->getContent();

    }
}