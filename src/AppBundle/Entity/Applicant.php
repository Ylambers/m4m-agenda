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
use Symfony\Component\Validator\Constraints\Date;
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
     * @var date
     * @ORM\Column(name="date", type="date", nullable=true)
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
     * @ORM\ManyToOne(targetEntity="Room")
     */
    private $room;

    /**
     * @var string
     * @ORM\Column(name="token", type="string", length=64)
     */
    private $token;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Applicant
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Applicant
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Applicant
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set timeStart
     *
     * @param \DateTime $timeStart
     * @return Applicant
     */
    public function setTimeStart($timeStart)
    {
        $this->timeStart = $timeStart;

        return $this;
    }

    /**
     * Get timeStart
     *
     * @return \DateTime 
     */
    public function getTimeStart()
    {
        return $this->timeStart;
    }

    /**
     * Set timeEnd
     *
     * @param \DateTime $timeEnd
     * @return Applicant
     */
    public function setTimeEnd($timeEnd)
    {
        $this->timeEnd = $timeEnd;

        return $this;
    }

    /**
     * Get timeEnd
     *
     * @return \DateTime 
     */
    public function getTimeEnd()
    {
        return $this->timeEnd;
    }

    /**
     * Set room
     *
     * @param \AppBundle\Entity\Room $room
     * @return Applicant
     */
    public function setRoom(\AppBundle\Entity\Room $room = null)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return \AppBundle\Entity\Room 
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Applicant
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }
}
