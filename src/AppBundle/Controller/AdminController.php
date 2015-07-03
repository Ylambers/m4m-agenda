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
use AppBundle\Entity\User;

class adminController extends Controller
{
    private $text = array();
    /**
     * @Route("/admin", name="admin")
     */
    public function createRoom(request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Geen toegang tot deze pagina');
//        form room
        $room = new Room;

        //        form user
        $user = new User;

//        form room
        $formRoom = $this->createFormBuilder($room)
            ->add('name', 'text', array('label' => 'name','attr' => array("id" => "datetimepicker", "class" => "form-control")))
            ->add('seats', 'integer', array('label' => 'stoelen','attr' => array("id" => "datetimepicker", "class" => "form-control")))
            ->add('save', 'submit', array('label' => "Verzenden",'attr' => array("class" => "form-control")))
            ->getForm();

//        form user
        $formUser = $this->createFormBuilder($user)
            ->add("name", "text", array("label" => "Naam",'attr' => array("id" => "datetimepicker", "class" => "form-control")))
            ->add("password", "text", array("label" => "Wachtwoord",'attr' => array("id" => "datetimepicker", "class" => "form-control")))
            ->add("save", "submit", array("label" => "verzenden",'attr' => array("id" => "datetimepicker", "class" => "form-control")))
            ->getForm();

        $formRoom && $formUser->handleRequest($request);

//        form room
        if($formRoom->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($room);
            $em->flush();
        }

//        form user
        if($formUser->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }



        return $this->render('default/admin.html.twig', array(
            'formRoom' => $formRoom->createView(),
            'user' => $formUser->createView(),
        ));
    }
}