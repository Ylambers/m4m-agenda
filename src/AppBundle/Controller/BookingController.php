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


use AppBundle\Entity\Applicant;

class BookingController extends Controller
{
    /**
     * @Route("/booking", name="Booking")
     */
    public function Booking(request $request)
    {
        $book = new Applicant();

        $formBooking = $this->createFormBuilder($book)

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

            ->add('participants', 'text', array('label' => 'Deelnemers'))
            ->add('reason', 'text', array('label' => 'Reden'))
            ->add('save', 'submit', array('label' => "Verzenden"))
            ->getForm();

        $formBooking->handleRequest($request);

        if ($formBooking->isValid()) {
            /* post in database */
            $em = $this->getDoctrine()->getManager();
//            var_dump(count($formBooking->get('room')->getData()));
//            foreach ($formBooking->get('room')->getData() as $val) {
//                var_dump($val);
//                //$formBooking->addRoom($val);
//            }


            $em->persist($formBooking);
            $em->flush();

//           $text = "Uw aanvraag is aangekomen";
        }

    return $this->render('default/book.html.twig',
        array(
            'formBooking' => $formBooking->createView(),
//            "message" => $text
        ));
    }
}