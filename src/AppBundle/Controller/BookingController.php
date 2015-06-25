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
                }, 'property' => 'name', 'label' => 'Ruimte','attr' => array("class" => "form-control")))

            ->add('name', 'text', array('label' => 'Voornaam','attr' => array("class" => "form-control")))

            ->add('lastName', 'text', array('label' => 'Achter naam','attr' => array("class" => "form-control")))

            ->add('date', 'date', array('label' => 'Datum', 'placeholder' => array
            ('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
                'years' => range(Date('Y'), Date('Y',strtotime('+3 year')))))


            ->add('timeStart', 'time', array(
                'input'  => 'datetime',
                'widget' => 'choice',
                'attr' => array("class" => "datetimepicker")
            ))

            ->add('timeEnd', 'time', array(
                'input'  => 'datetime',
                'widget' => 'choice',
                'attr' => array("class" => "form-control")
            ))

            ->add('save', 'submit', array('label' => "Verzenden",'attr' => array("class" => "form-control")))
            ->getForm();

        $formBooking->handleRequest($request);
        if ($formBooking->isValid()){


            $booking->setRoom($formBooking->get("room")->getData());

            $this->checkBooking($booking);

        }


        return $this->render('default/book.html.twig',array(
                'formBooking' => $formBooking->createView(),
                'texts' => $this->text,
            ));
    }
    public function checkBooking($booking){


        $booking->setRoom($formBooking->get("room")->getData());

        $em = $this->getDoctrine()->getManager();

        $reservations = $em->getRepository("AppBundle:Applicant")->findBy(array("date" => $booking->getDate(),"room" => $booking->getRoom()));
        $error = 0;
        foreach($reservations as $reservation){
            $timeStart = $reservation->getTimeStart()->format('H:i:s');
            $timeEnd = $reservation->getTimeEnd()->format('H:i:s');
            $bookingTimeStart = $booking->getTimeStart()->format('H:i:s');
            $bookingTimeEnd = $booking->getTimeEnd()->format('H:i:s');

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
            $this->text['error'][] = "De ruimte is al op deze tijd bezet.";
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