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
use Symfony\Component\Validator\Constraints\DateTime;

class AppController extends Controller
{
    /**
     * @Route("/app/{type}/{extra}", defaults={"type" = "rooms", "extra" = ""}, name="appAPI")
     */
    public function indexAction($type,$extra)
    {
        $response = new Response();
        $page = array();
        if($type == "reservations"){
            if($extra != ""){
                $date = new \DateTime($extra);
                //$year = Date("Y",strtotime($extra));
                $bookings = $this->getDoctrine()
                    ->getRepository('AppBundle:Applicant')
                    ->findBy(array("date" => $date));
                //echo $extra;
            }else{
                $bookings = $this->getDoctrine()
                    ->getRepository('AppBundle:Applicant')
                    ->findAll();
            }

            foreach($bookings as $booking){
                $page[$booking->getId()] = array();
                $page[$booking->getId()]['name'] = $booking->getName();
                $page[$booking->getId()]['lastname'] = $booking->getLastName();
                $page[$booking->getId()]['date'] = $booking->getDate();
                $page[$booking->getId()]['startTime'] = $booking->getTimeStart();
                $page[$booking->getId()]['endTime'] = $booking->getTimeEnd();
                $page[$booking->getId()]['participants'] = $booking->getParticipants();
                $page[$booking->getId()]['reason'] = $booking->getReason();
                $page[$booking->getId()]['room'] = array();
                $page[$booking->getId()]['room']['id'] = $booking->getRoom()->getId();
                $page[$booking->getId()]['room']['name'] = $booking->getRoom()->getName();
                $page[$booking->getId()]['room']['seats'] = $booking->getRoom()->getSeats();
            }
        }elseif($type == "reservation"){

        }else{

            $bookings = $this->getDoctrine()
                ->getRepository('AppBundle:Room')
                ->findAll();

            foreach($bookings as $booking){
                $page[$booking->getId()] = array();
                $page[$booking->getId()]['name'] = $booking->getName();
                $page[$booking->getId()]['seats'] = $booking->getSeats();
            }
        }


        $response->setContent(json_encode($page));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
