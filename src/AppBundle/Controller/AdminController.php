<?php
/**
 * Created by PhpStorm.
 * User: yaron
 * Date: 3-7-2015
 * Time: 09:23
 */

// src/AppBundle/Controller/DefaultController.php
// ...

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Room;
use AppBundle\Entity\Applicant;

class adminController extends Controller
{
    private $text = array();
    /**
     * @Route("/admin/{id}", defaults={"id"=""}, name="admin")
     */
    public function createRoom($id, request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Geen toegang tot deze pagina');
//        form room
        /*
        $room = new Room;

//        form room
        $formRoom = $this->createFormBuilder($room)
            ->add('name', 'text', array('label' => 'name','attr' => array("id" => "datetimepicker", "class" => "form-control")))
            ->add('seats', 'integer', array('label' => 'stoelen','attr' => array("id" => "datetimepicker", "class" => "form-control")))
            ->add('save', 'submit', array('label' => "Verzenden",'attr' => array("class" => "form-control")))
            ->getForm();
        $formRoom->handleRequest($request);

//        form room
        if($formRoom->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($room);
            $em->flush();
        }*/

        $scheduler = $this->getDoctrine()->getRepository('AppBundle:Applicant')->findAll();

        $booking = $this->getDoctrine()->getManager()->getRepository('AppBundle:Applicant')->find($id);

        $formBooking = $this->createFormBuilder($booking)

            ->add('room', 'entity', array('required' => true, 'class' => "AppBundle:Room", 'property' => 'name', 'label' => 'Ruimte','attr' => array("class" => "form-control")))

            ->add('name', 'text', array('label' => 'Voornaam','attr' => array("class" => "form-control")))

            ->add('lastName', 'text', array('label' => 'Achternaam','attr' => array("class" => "form-control")))

            ->add('date', 'date', array('label' => 'Datum','attr' => array("class" => "form-control")))

            ->add('timeStart', 'time', array(
                'input'  => 'datetime',
                'widget' => 'choice',
                'attr' => array("class" => "form-control")
            ))

            ->add('timeEnd', 'time', array(
                'input'  => 'datetime',
                'widget' => 'choice',
                'attr' => array("class" => "form-control")
            ))

            ->add('save', 'submit', array('label' => "Verzenden",'attr' => array("class" => "form-control")))
            ->getForm();
        $formBooking->handleRequest($request);

        $errors = array();
        if($formBooking->isValid()){
            $bookingCheck = new BookingController();
            $errors = $bookingCheck->checkBooking($booking, $this->getDoctrine()->getManager(),$id);
        }


        return $this->render('default/admin.html.twig', array(
            'formRoom' => $formBooking->createView(),
            'scheduler' =>$scheduler,
            'errors' =>$errors,
        ));
    }

}