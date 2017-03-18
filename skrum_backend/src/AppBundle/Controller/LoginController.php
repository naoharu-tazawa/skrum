<?php

namespace AppBundle\Controller\LoginController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException as BadRequestHttpException;

class LoginController extends Controller
{
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
