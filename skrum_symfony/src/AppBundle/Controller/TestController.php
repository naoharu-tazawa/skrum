<?php


namespace TestController;

use Silex\Application as Application;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException as BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;

class TestController extends DefaultController
{

    public function getUserInfo(Application $app, Request $request)
    {

        return $this->apiResponse(
            array(
                'id'        => 1,
                'user_name' => 'test_user'
            )
        );
    }
}
