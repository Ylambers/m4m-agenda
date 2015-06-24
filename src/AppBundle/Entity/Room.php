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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->applicants = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return Room
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
     * Set seats
     *
     * @param integer $seats
     * @return Room
     */
    public function setSeats($seats)
    {
        $this->seats = $seats;

        return $this;
    }

    /**
     * Get seats
     *
     * @return integer 
     */
    public function getSeats()
    {
        return $this->seats;
    }

    /**
     * Add applicants
     *
     * @param \AppBundle\Entity\Applicant $applicants
     * @return Room
     */
    public function addApplicant(\AppBundle\Entity\Applicant $applicants)
    {
        $this->applicants[] = $applicants;

        return $this;
    }

    /**
     * Remove applicants
     *
     * @param \AppBundle\Entity\Applicant $applicants
     */
    public function removeApplicant(\AppBundle\Entity\Applicant $applicants)
    {
        $this->applicants->removeElement($applicants);
    }

    /**
     * Get applicants
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApplicants()
    {
        return $this->applicants;
    }
}
