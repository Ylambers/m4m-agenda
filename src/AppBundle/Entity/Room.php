<?php
/**
 * Created by PhpStorm.
 * User: yaron
 * Date: 18-6-2015
 * Time: 12:21
 */

namespace AppBundle\Entity;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Time;


/**
 *
 * @ORM\Entity
 * @ORM\Table(name="Room")
 */

class Room{

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var integer
     * @ORM\Column(name="seats", type="integer", length=3)
     */
    private  $seats;

    /**
     * @ORM\ManyToMany(targetEntity="Applicant", inversedBy="applicants")
     */
    private $applicants;
}