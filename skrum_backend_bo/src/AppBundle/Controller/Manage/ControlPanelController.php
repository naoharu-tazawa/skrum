<?php

namespace AppBundle\Controller\Manage;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;

/**
 * コントロールパネルコントローラ
 *
 * @author naoharu.tazawa
 */
class ControlPanelController extends BaseController
{
    /**
     * @Route("/control_panel", name="control_panel")
     * @Method("get")
     */
    public function indexAction(Request $request)
    {
        return $this->render('control_panel/index.html.twig');
    }
}
