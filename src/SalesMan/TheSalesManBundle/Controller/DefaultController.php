<?php

namespace SalesMan\TheSalesManBundle\Controller;

use SalesMan\TheSalesManBundle\Form\CampaignType;
use SalesMan\TheSalesManBundle\Form\PeriodType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SalesMan\TheSalesManBundle\Service\TrainCampaign;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/save", name="save")
     * @Method("POST")
     * @ParamConverter("post", options={"mapping": {"postSlug": "slug"}})
     */
    public function indexAction(Request $request)
    {

//        $post = $request->request->all();
        $form = $this->createForm(CampaignType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $service = $this->get('sales_man.estimate');
            $data = $service->estimate($request->request->all());
            return $this->render('SalesManBundle:Default:estimate.html.twig', [
                'data' => $data,
                'period' => $request->request->all()
            ]);
        }

        return $this->render('SalesManBundle:Default:index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/train", name="train")
     * @Method("GET")
     */
    public function trainAction(Request $request)
    {
        $service = $this->get('sales_man.train');
        $service->train();


        return  new Response();
    }

    /**
     * @Route("/estimate", name="estimate")
     * @Method("POST")
     */
    public function estimateAction(Request $request)
    {
        $service = $this->get('sales_man.estimate');
        $data = $service->estimate($request->request->all());
        return $this->render('SalesManBundle:Default:estimate.html.twig', [
            'data' => $data
        ]);
    }

    /**
     * @Route("/periodEstimate", name="period-estimate")
     * @Method("GET")
     */
    public function periodEstimateAction(Request $request)
    {

        $form = $this->createForm(PeriodType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $request->request->all();
//            $service = $this->get('sales_man.estimate');
            return $this->render('SalesManBundle:Default:estimatePeriod.html.twig', [
                'data' => $post
            ]);
        }

        return $this->render('SalesManBundle:Default:index.html.twig', [
//            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
}
