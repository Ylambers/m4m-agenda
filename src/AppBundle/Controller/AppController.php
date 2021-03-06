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
            $repo = $this->getDoctrine()->getRepository('AppBundle:Applicant');
            if($extra != "" && $extra != "false"){
                $date = new \DateTime($extra);
                if($room != ""){
                    $room = $this->getDoctrine()->getRepository("AppBundle:Room")->find($room);
//
//                    $bookings = $this->getDoctrine()
//                        ->getRepository('AppBundle:Applicant')
//                        ->findBy(array("date" => $date,"room"=>$room),["timeStart" => "ASC"]);

                    $query = $repo->createQueryBuilder('p')
                        ->where('p.date=:date AND p.room=:room')
                        ->setParameter('date', $date)
                        ->setParameter('room', $room)
                        ->addOrderBy('p.timeStart', 'ASC')
                        ->getQuery();
                }else{
//                    $bookings = $this->getDoctrine()
//                        ->getRepository('AppBundle:Applicant')
//                        ->findBy(array("date" => $date),["timeStart" => "ASC"]);
                    $query = $repo->createQueryBuilder('p')
                        ->where('p.date=:date')
                        ->setParameter('date', $date)
                        ->addOrderBy('p.timeStart', 'ASC')
                        ->getQuery();
                }
            }else{
                if($room != ""){
                    $room = $this->getDoctrine()->getRepository("AppBundle:Room")->find($room);

                    $query = $repo->createQueryBuilder('p')
                        ->where('p.room=:room')
                        ->setParameter('room', $room)
                        ->addOrderBy('p.date', 'DESC')
                        ->addOrderBy('p.timeStart', 'ASC')
                        ->getQuery();

//                    $bookings = $this->getDoctrine()
//                        ->getRepository('AppBundle:Applicant')
//                        ->findBy(array("room"=>$room),["date"=>"DESC","timeStart" => "ASC"]);
                }else{
//                    $bookings = $this->getDoctrine()
//                        ->getRepository('AppBundle:Applicant')
//                        ->findBy([],["date"=>"DESC","timeStart" => "ASC"]);
                    $query = $repo->createQueryBuilder('p')
                        ->addOrderBy('p.date', 'DESC')
                        ->addOrderBy('p.timeStart', 'ASC')
                        ->getQuery();

                }
            }
            $bookings = $query->getResult();
            $i = 0;
            foreach($bookings as $booking){

                $page[strval($i)] = $booking->getDate()->format("d-m-Y");
                $page[strval($i)] = [
                    "id" => $booking->getId(),
                    "name" => $booking->getName(),
                    "lastname" => $booking->getLastName(),
                    "date" => $booking->getDate(),
                    "startTime" => $booking->getTimeStart(),
                    "endTime" => $booking->getTimeEnd(),
                    "room" => [
                        "id" => $booking->getRoom()->getId(),
                        "name" => $booking->getRoom()->getName(),
                        "seats" => $booking->getRoom()->getSeats(),
                    ],
                ];
                $i++;
//                $page[$booking->getId()] = array();
//                $page[$booking->getId()]['name'] = $booking->getName();
//                $page[$booking->getId()]['lastname'] = $booking->getLastName();
//                $page[$booking->getId()]['date'] = $booking->getDate();
//                $page[$booking->getId()]['startTime'] = $booking->getTimeStart();
//                $page[$booking->getId()]['endTime'] = $booking->getTimeEnd();
//                $page[$booking->getId()]['room'] = array();
//                $page[$booking->getId()]['room']['id'] = $booking->getRoom()->getId();
//                $page[$booking->getId()]['room']['name'] = $booking->getRoom()->getName();
//                $page[$booking->getId()]['room']['seats'] = $booking->getRoom()->getSeats();
            }
        }elseif($type == "checkconnect"){
            $page = array(true);
        }elseif($type == "getReservation"){
            $reservation = $this->getDoctrine()->getManager()->getRepository("AppBundle:Applicant")->findOneBy(["token"=> $extra]);
            if($reservation != null){
                $page['id'] = $reservation->getId();
                $page['name'] = $reservation->getName();
                $page['lastName'] = $reservation->getLastName();
                $page['date'] = $reservation->getDate();
                $page['startTime'] = $reservation->getTimeStart();
                $page['endTime'] = $reservation->getTimeEnd();
                $page['room'] = [
                    "id" => $reservation->getRoom()->getId(),
                    "name" => $reservation->getRoom()->getName(),
                    "seats" => $reservation->getRoom()->getSeats(),
                ];
                $page['token'] = $reservation->getToken();
            }else{
                $page = null;
            }

            //$page = $reservation;
        }elseif($type == "checkDates"){
            $page = [];
            $reservations = $this->getDoctrine()->getManager()->getRepository("AppBundle:Applicant")->findBy([],["date"=>"ASC"]);
            foreach($reservations as $reservation){
                if(!in_array($reservation->getDate()->format("Y-m-d"),$page)){
                    $page[] = $reservation->getDate()->format("Y-m-d");
                }
            }
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

                $em = $this->getDoctrine()->getManager();
                if(isset($_POST['id'])){
                    $reservation = $em->getRepository("AppBundle:Applicant")->find($_POST['id']);
                    if(count($reservation) != 1){
                        $error[] = "Id bestaat niet.";
                    }
                    }else{
                    $reservation = new Applicant();
                }
                if(count($error) == 0) {
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
                    if(isset($_POST['id'])){
                        $errors = $bookingCheck->checkBooking($reservation, $em,$_POST['id']);
                    }else{
                        $errors = $bookingCheck->checkBooking($reservation, $em);
                    }

                    if(count($errors) != 0){
                        $page = $errors;
                    }else{
                        $page[] = "succes";
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
