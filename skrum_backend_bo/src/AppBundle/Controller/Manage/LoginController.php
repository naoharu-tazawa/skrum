<?php

namespace AppBundle\Controller\Manage;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Controller\BaseController;
use AppBundle\Form\Type\LoginType;
use AppBundle\Form\Data\LoginData;

/**
 * ログインコントローラ
 *
 * @author naoharu.tazawa
 */
class LoginController extends BaseController
{
    /**
     * @Route("/login", name="login")
     * @Method("get")
     */
    public function indexAction(Request $request)
    {
        // フォーム作成
        $form = $this->createForm(LoginType::class, new LoginData());

        return $this->render('login/index.html.twig', array(
                'form' => $form->createView(),
                'message' => ''
        ));
    }

    /**
     * @Route("/login", name="login_post")
     * @Method("post")
     */
    public function indexPostAction(Request $request)
    {
        // フォーム作成
        $form = $this->createForm(LoginType::class, new LoginData());

        // リクエストデータをフォームにバインド
        $form->handleRequest($request);

        // バリデーション
        if (!$form->isValid()) {
            return $this->render('login/index.html.twig', array(
                    'form' => $form->createView()
            ));
        }

        // リクエストデータ取得
        $data = $form->getData();

        // ID/パスワード確認
        if (!($data->getId() === 'skrumadmin' && $data->getPassword() === 'atfo')) {
            return $this->render('login/index.html.twig', array(
                    'form' => $form->createView(),
                    'message' => 'IDまたはパスワードが違います'
            ));
        }

        // セッションにユーザ情報を格納
        $session = $request->getSession();
        $session->set('session', $data);

        // コントロールパネル画面にリダイレクト
        return $this->redirect(
                $this->generateUrl('control_panel')
        );
    }
}
