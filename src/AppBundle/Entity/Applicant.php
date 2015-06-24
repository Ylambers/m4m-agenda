<?php
/**
 * Created by PhpStorm.
 * User: yaron
 * Date: 18-6-2015
 * Time: 12:27
 */

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Time;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="Applicant")
 */

class Applicant{
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
     * @var string
     * @ORM\Column(name="lastName", type="string", length=64)
     */
    private $lastName;

    /**
     * @var datetime
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var time
     * @ORM\Column(name="timeStart", type="time")
     */
    private $timeStart;

    /**
     * @var time
     * @ORM\Column(name="timeEnd", type="time")
     */
    private $timeEnd;

    /**
     * @var string
     * @ORM\Column(name="participants", type="string", length=255)
     */
    private $participants;

    /**
     * @var string
     * @ORM\Column(name="reason", type="string", length=255)
     */
    private $reason;

    /**
     * @ORM\ManyToMany(targetEntity="Room", mappedBy="applicants")
     */
    private $room;
}