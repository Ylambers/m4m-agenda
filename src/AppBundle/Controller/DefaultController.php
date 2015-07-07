<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Room;

class DefaultController extends Controller
{
    /**
     * @Route("/default", name="homepage")
     */
    public function indexAction()
    {
        $booking = $this->getDoctrine()
            ->getRepository('AppBundle:Room')
            ->findAll();

        return $this->render('default/index.html.twig',
            array(
                "available_room" => $booking
            ));
    }
    /**
     * @Route("/loginscreens/{type}", defaults={"type"="apollo"}, name="homepage")
     */
    public function loginScreens($type)
    {

        return $this->render('default/login.screens.html.twig',
            array(
                "type" => $type
            ));
    }

}
