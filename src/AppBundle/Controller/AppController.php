<?php
/**
 * Created by PhpStorm.
 * User: Alwin
 * Date: 24-6-2015
 * Time: 10:26
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Room;

class AppController extends Controller
{
    /**
     * @Route("/app", name="appAPI")
     */
    public function indexAction()
    {
        $bookings = $this->getDoctrine()
            ->getRepository('AppBundle:Room')
            ->findAll();

        $response = new Response();
        $page = array();
        foreach($bookings as $booking){
            $page[$booking->getId()] = array();
            $page[$booking->getId()]['name'] = $booking->getName();
            $page[$booking->getId()]['seats'] = $booking->getSeats();
        }
        $response->setContent(json_encode($page));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
