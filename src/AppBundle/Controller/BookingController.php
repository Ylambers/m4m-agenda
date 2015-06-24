<?php
/**
 * Created by PhpStorm.
 * User: yaron
 * Date: 24-6-2015
 * Time: 10:14
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Applicant;

class BookingController extends Controller
{
    /**
     * @Route("/booking", name="Booking")
     */
    public function Booking()
    {
        $booking = new Book;

        $formBooking = $this->createFormBuilder($booking)
            ->add('text', 'textarea', array('label' => 'Naam ', array("class" => "btn"), 'required' => false))
            ->add('save', 'submit', array('label' => "Verzenden", 'attr' => array("class" => "canBeDisabled")))
            ->getForm();

        return $this->render('default/book.html.twig',
            array(
                "formBook" => $booking
            ));
    }
}
