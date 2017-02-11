<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    protected function apiResponse($data = array(), $status = 200, $headers = array())
    {
        // @TODO format に合わせた response オブジェクトを返却する
        $headers['Content-Type'] = 'application/json; charset=utf-8';
        //$headers['Cache-Control'] = 'max-age=60';
        $json_response = new JsonResponse($data, $status, $headers);
        $json_response->setCharset('utf-8');
        $json_response->setEncodingOptions(JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

        return $json_response;
    }
}
