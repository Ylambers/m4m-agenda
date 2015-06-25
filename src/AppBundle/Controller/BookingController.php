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
    /**
     * @Route("/booking", name="Booking")
     */
    public function Booking(request $request)
    {
        $message = array();
        $booking = new Applicant();

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
            $em = $this->getDoctrine()->getManager();
            $bookEntity = $em->getRepository("AppBundle:Applicant")->findBy(array("date" => $booking->getDate()));
            $time_start = $em->getRepository("AppBundle:Applicant")->findBy(array("timeStart"=>$formBooking->get("timeStart") -> getViewData()));
            $time_end = $em->getRepository("AppBundle:Applicant")->findBy(array("timeEnd"=>$formBooking->get("timeEnd") -> getViewData()));

            if (count($bookEntity) == 0) {
                if (count($time_start) == 0){
                    $em = $this->getDoctrine()->getManager();
                    $booking->setRoom($formBooking->get("room")->getData());
                    $em->persist($booking);
                    $em->flush();
                }
            }elseif(count($bookEntity) == 1){
                // return new Response ('Er is al iets gepland');
                $errorMsg = "Er is al iets gepland";
            }
        }


    return $this->render('default/book.html.twig',
        array(
            'formBooking' => $formBooking->createView(),
        ));
    }
}