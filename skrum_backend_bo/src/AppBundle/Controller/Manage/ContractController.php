<?php

namespace AppBundle\Controller\Manage;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;

/**
 * 契約コントローラ
 *
 * @author naoharu.tazawa
 */
class ContractController extends BaseController
{
    /**
     * @Route("/contract", name="contract")
     * @Method("get")
     */
    public function indexAction(Request $request)
    {
        $contractService = $this->getContractService();
        $data = $contractService->getContracts();

        return $this->render('contract/index.html.twig', array(
                'data' => $data
        ));
    }
}
