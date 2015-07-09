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

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Cookie;


use AppBundle\Entity\Applicant;

class BookingController extends Controller
{
    private $text = array();
    /**
     * @Route("/", name="Booking")
     */
    public function Booking()
    {
       // $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Geen toegang tot deze pagina');
        $booking = new Applicant();
        $this->text['error'] = array();
        //$booking->setDate(new \DateTime('NOW'));

        $formBooking = $this->createFormBuilder($booking)

          ->add('room', 'entity', array('required' => true, 'class' => "AppBundle:Room",
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }, 'property' => 'name', 'label' => 'Ruimte','attr' => array("class" => "form-control")))

            ->add('name', 'text', array('label' => 'Voornaam','attr' => array("class" => "form-control")))

            ->add('lastName', 'text', array('label' => 'Achternaam','attr' => array("class" => "form-control")))

            ->add('date', 'text', array('label' => 'Datum','attr' => array("class" => "form-control")))
//             'placeholder' => array
//    ('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
//                'years' => range(Date('Y'), Date('Y',strtotime('+3 year')))

            ->add('timeStart', 'time', array(
                'input'  => 'datetime',
                'widget' => 'choice',
                'attr' => array("class" => "form-control picker")
            ))

            ->add('timeEnd', 'time', array(
                'input'  => 'datetime',
                'widget' => 'choice',
                'attr' => array("class" => "form-control picker")
            ))

            ->add('save', 'submit', array('label' => "Verzenden",'attr' => array("class" => "form-control")))
            ->getForm();
        /*
        $formBooking->handleRequest($request);
        if ($formBooking->isValid()){

            $booking->setRoom($formBooking->get("room")->getData());
            //var_dump($formBooking->get('date')->getData());
            $booking->setDate(new \DateTime($formBooking->get('date')->getData()) );
            //$booking->getDate()->format();

            foreach($this->checkBooking($booking, $this->getDoctrine()->getManager()) as $key => $val){
                if($key != "token"){
                    $this->text['error'][] = $val;
                }else{
                    $this->text['box'] = [
                        "autoLoad" => true,
                        "title" => "Aanpassen",
                        "text" => "Uw reservering is aangemaakt.<br />\nAls u deze graag aan wil passen heeft u een token nodig. Het token is:<br />\n".$val."<br />\n<br />Ga naar <a href='/aanpassen/".$val."'>-website-/aanpassen/".$val."</a>"

                    ];
                }
            }
        }*/
        return $this->render('default/book.html.twig',array(
                'formBooking' => $formBooking->createView(),
                'texts' => $this->text,
            ));
    }
    /**
     * @Route("/change/{token}", defaults={"token"=""}, name="change")
     */
    public function change($token, request $request){

        if($token == false){
            return $this->render('default/change.html.twig');
        }else{
            $this->text['error'] = array();
            $booking = $this->getDoctrine()->getManager()->getRepository("AppBundle:Applicant")->findOneBy(array("token"=>$token));

            //$booking->setDate(new \DateTime('NOW'));

            $formBooking = $this->createFormBuilder($booking)

                ->add('room', 'entity', array('required' => true, 'class' => "AppBundle:Room",
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->orderBy('u.name', 'ASC');
                    }, 'property' => 'name', 'label' => 'Ruimte','attr' => array("class" => "form-control")))

                ->add('name', 'text', array('label' => 'Voornaam','attr' => array("class" => "form-control")))

                ->add('lastName', 'text', array('label' => 'Achternaam','attr' => array("class" => "form-control")))

                ->add('date', 'date', array('label' => 'Datum','attr' => array("class" => "form-control")))
//             'placeholder' => array
//    ('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
//                'years' => range(Date('Y'), Date('Y',strtotime('+3 year')))

                ->add('timeStart', 'time', array(
                    'input'  => 'datetime',
                    'widget' => 'choice',
                    'attr' => array("id" => "datetimepicker", "class" => "form-control picker")
                ))

                ->add('timeEnd', 'time', array(
                    'input'  => 'datetime',
                    'widget' => 'choice',
                    'attr' => array("id" => "datetimepicker", "class" => "form-control picker")
                ))

                ->add('save', 'submit', array('label' => "Aanpassen",'attr' => array("class" => "btn btn-primary", "style" => "margin-top: 10px;")))
                ->getForm();

            $formBooking->handleRequest($request);
            if ($formBooking->isValid()){

                $booking->setRoom($formBooking->get("room")->getData());
                //var_dump($formBooking->get('date')->getData());
                //$booking->getDate()->format();

                foreach($this->checkBooking($booking, $this->getDoctrine()->getManager(), $booking->getId()) as $key => $val){
                    if($key != "token"){
                        $this->text['error'][] = $val;
                    }else{
                        $this->text['box'] = [
                            "autoLoad" => true,
                            "title" => "Aanpassen",
                            "text" => "Uw reservering is aangemaakt.<br />\nAls u deze graag aan wil passen heeft u een token nodig. Het token is:<br />\n".$val."<br />\n<br />Ga naar <a href='/aanpassen/".$val."'>-website-/aanpassen/".$val."</a>"
                        ];
                    }
                }
            }

            return $this->render('default/change.html.twig',array(
                'formBooking' => $formBooking->createView(),
                'texts' => $this->text,
            ));
        }
    }
    /**
     * @Route("/updateTokens", name="updateTokens")
    */
    public function updateTokens(){
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository("AppBundle:Applicant")->findBy(array("token"=>""));
        //$reservations = [];
        foreach($reservations as $reservation){
            $token = md5($reservation->getId().$reservation->getName());

            $reservation->setToken($token);
            $em->persist($reservation);
            $em->flush();

        }
        $response = new Response();
        $response->setContent("Updated");
        return $response;
    }

    
    public function checkBooking($booking,$em,$id=false){

        $reservations = $em->getRepository("AppBundle:Applicant")->findBy(array("date" => $booking->getDate(),"room" => $booking->getRoom()));

        $errors = 0;
        $bookingTimeStart = $booking->getTimeStart()->format('H:i');
        $bookingTimeEnd = $booking->getTimeEnd()->format('H:i');

        foreach($reservations as $reservation){
            if($id != $reservation->getId()){

                $timeStart = $reservation->getTimeStart()->format('H:i');
                $timeEnd = $reservation->getTimeEnd()->format('H:i');

                if($bookingTimeStart >= $timeStart && $bookingTimeStart <= $timeEnd){
                    $errors++;
                }
                if($bookingTimeEnd >= $timeStart && $bookingTimeEnd <= $timeEnd){
                    $errors++;
                }
                if($bookingTimeStart <= $timeStart && $bookingTimeEnd >= $timeEnd){
                    $errors++;
                }
            }
        }
        $error = array();
        if($errors > 0){
            $error[] = "De ruimte is al op deze tijd bezet.";
        }
        if($bookingTimeEnd < $bookingTimeStart){
            $error[] = "De eind tijd kan niet minder zijn dan de start tijd.";
        }

        if(count($error) == 0){
            //$last = $em->
            if($id == false){
                $last = $em->getRepository("AppBundle:Applicant")->findAll();
                $lastId = 0;
                foreach($last as $item){
                    if($item->getId() > $lastId){
                        $lastId = $item->getId();
                    }
                }
                $lastId++;
                $token = md5($lastId.$booking->getName());
                $booking->setToken($token);
            }
            $em->persist($booking);
            $em->flush();
            if($id == false){
                return ["token",$token];
            }else{
                return ["token", $booking->getToken()];
            }
        }else{
            return $error;
        }
    }
}

//if(isset($_COOKIE['tokens'])) {
//    $tokens = explode(",", $_COOKIE['tokens']);
//    foreach ($tokens as $token) {
//        echo  $token. "<br />";
//    }
//}