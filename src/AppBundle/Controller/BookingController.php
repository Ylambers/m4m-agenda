<?php
/**
 * Created by PhpStorm.
 * User: yaron
 * Date: 24-6-2015
 * Time: 10:14
 */

namespace AppBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use AppBundle\Entity\Applicant;

class BookingController extends Controller
{

    private $text = array();

    /**
     * @Route("/booking", name="Booking")
     */
    public function Booking(request $request)
    {
        $booking = new Applicant();
        $this->text['error'] = array();


        $formBooking = $this->createFormBuilder($booking)

          ->add('room', 'entity', array('required' => true, 'class' => "AppBundle:Room",
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }, 'property' => 'name', 'label' => 'Ruimte'))

            ->add('name', 'text', array('label' => 'Voornaam'))

            ->add('lastName', 'text', array('label' => 'Achter naam'))

            ->add('date', 'date', array('label' => 'Datum', 'placeholder' => array
            ('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
                'years' => range(Date('Y'), Date('Y',strtotime('+3 year')))))


            ->add('timeStart', 'time', array(
                'input'  => 'datetime',
                'widget' => 'choice',
                'placeholder' => false))

            ->add('timeEnd', 'time', array(
                'input'  => 'datetime',
                'widget' => 'choice',
                'placeholder' => false))

            ->add('save', 'submit', array('label' => "Verzenden"))
            ->getForm();

        $formBooking->handleRequest($request);

//        Find the items
//        $location = $this->getDoctrine()
//            ->getRepository('AppBundle:Applicant')
//            ->findAll();
//
//        foreach($location as $val){
//            $val->getDate();
//        }
//        if ($formBooking->isValid()) {
////            if($formBooking->get('lastName') == 'test'){
//                /* post in database */
//                $em = $this->getDoctrine()->getManager();
//                $booking->setRoom($formBooking->get("room")->getData());
//                $em->persist($booking);
//                $em->flush();
//            return new Response('The author is valid! Yes!');
//        }

        if ($formBooking->isValid()){

            $this->checkBooking($formBooking,$booking);





            //$time_start = $em->getRepository("AppBundle:Applicant")->findBy(array("timeStart"=>$formBooking->get("timeStart") -> getViewData()));
            //$time_end = $em->getRepository("AppBundle:Applicant")->findBy(array("timeEnd"=>$formBooking->get("timeEnd") -> getViewData()));
//
//            if (count($bookEntity) == 0) {
//                if (count($time_start) == 0){
//                    $em = $this->getDoctrine()->getManager();
//                    $booking->setRoom($formBooking->get("room")->getData());
//                    $em->persist($booking);
//                    $em->flush();
//                }
//            }elseif(count($bookEntity) == 1){
//                // return new Response ('Er is al iets gepland');
//                $errorMsg = "Er is al iets gepland";
//            }
        }


        return $this->render('default/book.html.twig',array(
                'formBooking' => $formBooking->createView(),
                'texts' => $this->text,
            ));
    }
    private function checkBooking($formBooking,$booking){

        $em = $this->getDoctrine()->getManager();

        $booking->setRoom($formBooking->get("room")->getData());


        $reservations = $em->getRepository("AppBundle:Applicant")->findBy(array("date" => $booking->getDate(),"room" => $booking->getRoom()));
        $error = 0;
        foreach($reservations as $reservation){
            $timeStart = $reservation->getTimeStart()->format('H:i:s');
            $timeEnd = $reservation->getTimeEnd()->format('H:i:s');
            $bookingTimeStart = $booking->getTimeStart()->format('H:i:s');
            $bookingTimeEnd = $booking->getTimeEnd()->format('H:i:s');
//                echo $bookingTimeStart ." > ".$timeStart." && ". $bookingTimeStart." < ".$timeEnd;
//                echo "<br />";
//                echo $bookingTimeEnd ." > ".$timeStart." && ". $bookingTimeEnd." < ".$timeEnd;
//                echo "<br />";
//                echo $bookingTimeStart ." > ".$timeStart." && ". $bookingTimeEnd." < ".$timeEnd;
//                echo '<br /><br /><br />';

            if($bookingTimeStart >= $timeStart && $bookingTimeStart <= $timeEnd){
                $error++;
            }
            if($bookingTimeEnd >= $timeStart && $bookingTimeEnd <= $timeEnd){
                $error++;
            }
            if($bookingTimeStart <= $timeStart && $bookingTimeEnd >= $timeEnd){
                $error++;
            }

        }
        if($error > 0){
            $this->text['error'][] = "Deze ruimte is al op deze tijd bezet.";
        }
        if($booking->getTimeEnd() < $booking->getTimeStart()){
            $this->text['error'][] = "De eind tijd kan niet minder zijn dan de start tijd.";
            $error++;
        }

        if($error == 0){
            $em->persist($booking);
            $em->flush();
        }
    }
}