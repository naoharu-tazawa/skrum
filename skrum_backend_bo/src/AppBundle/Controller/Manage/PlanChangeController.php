<?php

namespace AppBundle\Controller\Manage;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Form\Type\PlanChangeType;
use AppBundle\Form\Data\PlanChangeData;

/**
 * 契約プラン変更コントローラ
 *
 * @author naoharu.tazawa
 */
class PlanChangeController extends BaseController
{
    /**
     * @Route("/plan_change/{companyId}", name="plan_change")
     * @Method("get")
     */
    public function indexAction(Request $request, int $companyId)
    {
        // フォーム作成
        $form = $this->createForm(PlanChangeType::class, new PlanChangeData());

        $contractService = $this->getContractService();
        $data = $contractService->getContract($companyId);

        return $this->render('plan_change/index.html.twig', array(
                'form' => $form->createView(),
                'data' => $data,
                'domain' => $this->getParameter('domain'),
                'message' => ''
        ));
    }

    /**
     * @Route("/plan_change/{companyId}", name="plan_change_post")
     * @Method("post")
     */
    public function indexPostAction(Request $request, int $companyId)
    {
        // フォーム作成
        $form = $this->createForm(PlanChangeType::class, new PlanChangeData());

        // リクエストデータをフォームにバインド
        $form->handleRequest($request);

        // リクエストデータ取得
        $formData = $form->getData();

        // バリデーション
        $contractService = $this->getContractService();
        if (!$form->isValid() || !is_int($formData->getPlanId())) {
            $data = $contractService->getContract($companyId);

            return $this->render('plan_change/index.html.twig', array(
                    'form' => $form->createView(),
                    'data' => $data,
                    'domain' => $this->getParameter('domain'),
                    'message' => 'プランを選択してください'
            ));
        }

        $data = $contractService->getContract($companyId);

        $result = $contractService->changePlan($companyId, $formData->getPlanId());

        if ($result) {
            // 契約プラン変更画面にリダイレクト
            return $this->render('plan_change/index.html.twig', array(
                    'form' => $form->createView(),
                    'data' => $data,
                    'domain' => $this->getParameter('domain'),
                    'message' => '契約プラン変更が完了しました'
            ));
        } else {
            return $this->render('plan_change/index.html.twig', array(
                    'form' => $form->createView(),
                    'data' => $data,
                    'domain' => $this->getParameter('domain'),
                    'message' => '契約プラン変更に失敗しました'
            ));
        }
    }
}
