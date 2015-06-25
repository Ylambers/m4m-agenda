<?php
/**
 * Created by PhpStorm.
 * User: Alwin
 * Date: 24-6-2015
 * Time: 10:26
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Applicant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Room;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class AppController extends Controller
{
    /**
     * @Route("/app/{type}/{extra}/{room}", defaults={"type" = "rooms", "extra" = "", "room" = ""}, name="appAPI")
     */
    public function indexAction($type,$extra,$room)
    {
        $response = new Response();
        $page = array();
        if($type == "reservations"){
            if($extra != "" && $extra != "false"){
                $date = new \DateTime($extra);
                if($room != ""){
                    $room = $this->getDoctrine()->getRepository("AppBundle:Room")->find($room);

                    $bookings = $this->getDoctrine()
                        ->getRepository('AppBundle:Applicant')
                        ->findBy(array("date" => $date,"room"=>$room));
                }else{
                    $bookings = $this->getDoctrine()
                        ->getRepository('AppBundle:Applicant')
                        ->findBy(array("date" => $date));
                }
            }else{
                if($room != ""){
                    $room = $this->getDoctrine()->getRepository("AppBundle:Room")->find($room);
                    $bookings = $this->getDoctrine()
                        ->getRepository('AppBundle:Applicant')
                        ->findBy(array("room"=>$room));
                }else{
                    $bookings = $this->getDoctrine()
                        ->getRepository('AppBundle:Applicant')
                        ->findAll();
                }
            }

            foreach($bookings as $booking){
                $page[$booking->getId()] = array();
                $page[$booking->getId()]['name'] = $booking->getName();
                $page[$booking->getId()]['lastname'] = $booking->getLastName();
                $page[$booking->getId()]['date'] = $booking->getDate();
                $page[$booking->getId()]['startTime'] = $booking->getTimeStart();
                $page[$booking->getId()]['endTime'] = $booking->getTimeEnd();
//                $page[$booking->getId()]['participants'] = $booking->getParticipants();
//                $page[$booking->getId()]['reason'] = $booking->getReason();
                $page[$booking->getId()]['room'] = array();
                $page[$booking->getId()]['room']['id'] = $booking->getRoom()->getId();
                $page[$booking->getId()]['room']['name'] = $booking->getRoom()->getName();
                $page[$booking->getId()]['room']['seats'] = $booking->getRoom()->getSeats();
            }
        }elseif($type == "reservation"){
        }elseif($type == "results"){
            $reservation = new Applicant();
            $room = $this->getDoctrine()->getManager()->getRepository("AppBundle:Room")->find(intval($_POST['room_id']));

            $reservation->setRoom($room);

            $date = new \DateTime(strtotime($_POST['date']));
            $reservation->setDate($date);

            $reservation->setName($_POST['firstname']);
            $reservation->setLastName($_POST['surname']);

            $startTime = new \DateTime(strtotime($_POST['startTime']));
            $reservation->setTimeStart($startTime);

            $endTime = new \DateTime(strtotime($_POST['endTime']));
            $reservation->setTimeStart($endTime);

            $bookingCheck = new BookingController();
            $bookingCheck->checkBooking($reservation);


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
