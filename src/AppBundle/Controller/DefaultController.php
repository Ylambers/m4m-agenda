<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Room;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
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
}
