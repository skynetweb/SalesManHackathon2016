<?php

namespace SalesMan\TheSalesManBundle\Controller;

use SalesMan\TheSalesManBundle\Form\CampaignType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DefaultController extends Controller
{
    /**
     * @Route("/save", name="save")
     * @Method("POST")
     * @ParamConverter("post", options={"mapping": {"postSlug": "slug"}})
     *
     * NOTE: The ParamConverter mapping is required because the route parameter
     * (postSlug) doesn't match any of the Doctrine entity properties (slug).
     * See http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html#doctrine-converter
     */
    public function indexAction(Request $request)
    {
        $post = $request->request->all();
        $form = $this->createForm(CampaignType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            var_export($post);

            return $this->redirectToRoute('/', ['slug' => $post->getSlug()]);
        }

        return $this->render('SalesManBundle:Default:index.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
//        return $this->render('SalesManBundle:Default:index.html.twig');
    }
}
