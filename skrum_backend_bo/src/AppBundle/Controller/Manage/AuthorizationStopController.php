<?php

namespace AppBundle\Controller\Manage;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Form\Data\AuthorizationStopData;
use AppBundle\Form\Type\AuthorizationStopType;

/**
 * 認可停止コントローラ
 *
 * @author naoharu.tazawa
 */
class AuthorizationStopController extends BaseController
{
    /**
     * @Route("/authz_stop/{companyId}", name="authz_stop")
     * @Method("get")
     */
    public function indexAction(Request $request, int $companyId)
    {
        // フォーム作成
        $form = $this->createForm(AuthorizationStopType::class, new AuthorizationStopData());

        $contractService = $this->getContractService();
        $data = $contractService->getContract($companyId);

        return $this->render('authorization_stop/index.html.twig', array(
                'form' => $form->createView(),
                'data' => $data,
                'message' => ''
        ));
    }

    /**
     * @Route("/authz_stop/{companyId}", name="authz_stop_post")
     * @Method("post")
     */
    public function indexPostAction(Request $request, int $companyId)
    {
        // フォーム作成
        $form = $this->createForm(AuthorizationStopType::class, new AuthorizationStopData());

        // リクエストデータをフォームにバインド
        $form->handleRequest($request);

        // リクエストデータ取得
        $formData = $form->getData();

        // バリデーション
        $contractService = $this->getContractService();
        if (!$form->isValid() || !is_int($formData->getAuthorizationStopFlg())) {
            $data = $contractService->getContract($companyId);

            return $this->render('authorization_stop/index.html.twig', array(
                    'form' => $form->createView(),
                    'data' => $data,
                    'message' => '利用停止または利用停止解除を選択してください'
            ));
        }

        $data = $contractService->getContract($companyId);

        $authorizationService = $this->getAuthorizationService();
        $result = $authorizationService->changeAuthzStopFlg($companyId, $formData->getAuthorizationStopFlg());

        if ($result) {
            // 契約プラン変更画面にリダイレクト
            return $this->render('authorization_stop/index.html.twig', array(
                    'form' => $form->createView(),
                    'data' => $data,
                    'message' => '利用停止/利用停止解除が完了しました'
            ));
        } else {
            return $this->render('authorization_stop/index.html.twig', array(
                    'form' => $form->createView(),
                    'data' => $data,
                    'message' => '利用停止/利用停止解除に失敗しました'
            ));
        }
    }
}
