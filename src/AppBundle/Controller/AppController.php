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
                        ->findBy(array("date" => $date,"room"=>$room),["date"=>"DESC","timeStart" => "ASC"]);
                }else{
                    $bookings = $this->getDoctrine()
                        ->getRepository('AppBundle:Applicant')
                        ->findBy(array("date" => $date),["date"=>"DESC","timeStart" => "ASC"]);
                }
            }else{
                if($room != ""){
                    $room = $this->getDoctrine()->getRepository("AppBundle:Room")->find($room);
                    $bookings = $this->getDoctrine()
                        ->getRepository('AppBundle:Applicant')
                        ->findBy(array("room"=>$room),["date"=>"DESC","timeStart" => "ASC"]);
                }else{
                    $bookings = $this->getDoctrine()
                        ->getRepository('AppBundle:Applicant')
                        ->findBy([],["date"=>"DESC","timeStart" => "ASC"]);
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
            $error = array();
            if(isset($_POST['room_id']) && isset($_POST['firstname']) && isset($_POST['surname']) && isset($_POST['date']) && isset($_POST['startTime']) && isset($_POST['endTime']) ){

                $room = $this->getDoctrine()->getManager()->getRepository("AppBundle:Room")->find(intval($_POST['room_id']));



                if(count($room) == 0){
                    $error[] = "Ruimte niet gevonden.";
                }
                if($_POST['date'] == ""){
                    $error[] = "Er is iets mis met de datum.";
                }
                if(strlen($_POST['firstname']) < 3){
                    $error[] = "Geen naam ingevult.";
                }

                if(strlen($_POST['surname']) < 3){
                    $error[] = "Geen achternaam ingevult.";
                }
                if($_POST['startTime'] == "00:00"){
                    $error[] = "Geen starttijd gekozen.";
                }
                if($_POST['startTime'] == ""){
                    $error[] = "Er is iets mis met de starttijd.";
                }
                if($_POST['endTime'] == ""){
                    $error[] = "Er is iets mis met de eindtijd.";
                }
                if($_POST['endTime'] == "00:00"){
                    $error[] = "Geen eindtijd gekozen.";
                }
//                var_dump($_POST['room_id']);
//                echo " - ";
//                var_dump($_POST['firstname']);
//                echo " - ";
//                var_dump($_POST['surname']);
//                echo " - ";
//                var_dump($_POST['date']);
//                echo " - ";
//                var_dump($_POST['startTime']);
//                echo " - ";
//                var_dump($_POST['endTime']);
//                echo " - ";

                if(count($error) == 0) {
                    $reservation = new Applicant();
                    $reservation->setRoom($room);

                    $date = new \DateTime($_POST['date']);
                    $reservation->setDate($date);

                    $reservation->setName($_POST['firstname']);
                    $reservation->setLastName($_POST['surname']);

                    $startTime = new \DateTime($_POST['startTime']);
                    $reservation->setTimeStart($startTime);

                    $endTime = new \DateTime($_POST['endTime']);
                    $reservation->setTimeEnd($endTime);

//                    var_dump($reservation->getRoom()->getName());
//                    echo " - ";
//                    var_dump($reservation->getDate());
//                    echo " - ";
//                    var_dump($reservation->getName());
//                    echo " - ";
//                    var_dump($reservation->getLastName());
//                    echo " - ";
//                    var_dump($reservation->getTimeStart());
//                    echo " - ";
//                    var_dump($reservation->getTimeEnd());

                    $bookingCheck = new BookingController();
                    $errors = $bookingCheck->checkBooking($reservation, $this->getDoctrine()->getManager());

                    if(count($errors) != 0){
                        $page = $errors;
                    }

                }else{
                    $page = $error;
                }
            }

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
