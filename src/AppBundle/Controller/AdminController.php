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


        $scheduler = $this->getDoctrine()->getRepository('AppBundle:Applicant')->findBy([], ['id' => 'DESC']);

        $booking = $this->getDoctrine()->getManager()->getRepository('AppBundle:Applicant')->find($id);
        $formBooking = $this->createFormBuilder($booking, array('attr' => array('id' => "reservations")))

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

        $this->text['errors'] = array();
        $formRoom = $this->addRoom();
        if($request->getMethod() == "POST"){

            $data = $request->request->all();
            if(isset($data['form']['seats'])){

                $formRoom = $this->addRoom($request);
            }else {
                $formBooking->handleRequest($request);
                if ($formBooking->isValid()) {
                    $bookingCheck = new BookingController();
                    $this->text['errors'] = $bookingCheck->checkBooking($booking, $this->getDoctrine()->getManager(), $id);
                }
            }
        }

        return $this->render('default/admin.html.twig', array(
            'formBooking' => $formBooking->createView(),
            'formRooms' => $formRoom->createView(),
            'scheduler' =>$scheduler,
            'texts' => $this->text,
//            'deleteRoom' => $this->deleteRoom($id)
        ));
    }

    public function addRoom($request=false)
    {
        $room = new Room;

        $formRoom = $this->createFormBuilder($room, array('attr' => array('id' => 'rooms')))
            ->add('name', 'text', array('label' => 'name', 'attr' => array("id" => "datetimepicker", "class" => "form-control")))
            ->add('seats', 'integer', array('label' => 'stoelen', 'attr' => array("id" => "datetimepicker", "class" => "form-control")))
            ->add('save', 'submit', array('label' => "Verzenden", 'attr' => array("class" => "form-control")))
            ->getForm();
        if($request != null){
            $formRoom->handleRequest($request);

            if ($formRoom->isValid()) {
                //$this->text['errors'] = [];
                $em = $this->getDoctrine()->getManager();
                $em->persist($room);
                $em->flush();
            }
        }
        return $formRoom;
    }

//    /**
//     * @Route("/admin/delete/{id}", defaults={"id"=""}, name="admin/delete")
//     */
//    public function deleteRoom($id){
//        $em = $this->getDoctrine()->getEntityManager();
//
//        $id = $em->getRepository('AppBundle:Room')->findBy([], ['id' => 'DESC']);
//        $room = $em->getRepository('AppBundle:Room')->find($id);
//
//
//        if($room == $id){
//            $em->remove($room);
//            $em->flush();
//        }
//
//        return $room;
//    }
//
}