<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException as BadRequestHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;

class LoginController extends Controller
{
    /**
     *
     * @Rest\Post("/login.{_format}")
     * @param Request $request
     * @throws BadRequestHttpException
     * @return unknown
     */
    public function login(Request $request)
    {
        # 認可は共通処理なのでmiddlewareで処理します
        $user_name = $request->get('user_name');
        $password  = $request->get('password');
        if (empty($user_name)) throw new BadRequestHttpException();
        if (empty($password)) throw new BadRequestHttpException();

        // Todo:認可の判定

        // sample情報を返す->DBの中身がわかればそのまま返したい
        return $this->apiResponse(
            array(
                'id' => 'id',
                'company_information' => array(
                    'company_name' => 'company_name'
                ),
                'user_information' => array(
                    'user_name' => 'user_name',
                    'user_purpose' => array(
                        '1' => array(
                            'name' => 'user_information1'
                        )
                    )
                )
            )
        );
    }
}
