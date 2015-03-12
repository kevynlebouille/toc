<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="projects")
 * @ORM\Entity
 */
class Project
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="cover", type="string", length=255)
     */
    private $cover;

    /**
     * @var integer
     *
     * @ORM\Column(name="fund_coll", type="integer")
     */
    private $fundColl;

    /**
     * @var integer
     *
     * @ORM\Column(name="fund_obj", type="integer")
     */
    private $fundObj;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="contrib_max_date", type="date")
     */
    private $contribMaxDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="contrib_min_amount", type="integer")
     */
    private $contribMinAmount;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="ownedProjects")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false)
     */
    private $owner;


    public function getCountDown()
    {
        $now      = new \DateTime();
        $interval = $now->diff($this->contribMaxDate);
        $days     = $interval->days;

        if ($interval->invert) {
            $days = 0;
        }

        return $days;
    }

    public function getFundProgress()
    {
        return floor(($this->fundColl / $this->fundObj) * 100);
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
     * Set title
     *
     * @param string $title
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set cover
     *
     * @param string $cover
     * @return Project
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set fundColl
     *
     * @param integer $fundColl
     * @return Project
     */
    public function setFundColl($fundColl)
    {
        $this->fundColl = $fundColl;

        return $this;
    }

    /**
     * Get fundColl
     *
     * @return integer
     */
    public function getFundColl()
    {
        return $this->fundColl;
    }

    /**
     * Set fundObj
     *
     * @param integer $fundObj
     * @return Project
     */
    public function setFundObj($fundObj)
    {
        $this->fundObj = $fundObj;

        return $this;
    }

    /**
     * Get fundObj
     *
     * @return integer
     */
    public function getFundObj()
    {
        return $this->fundObj;
    }

    /**
     * Set contribMaxDate
     *
     * @param \DateTime $contribMaxDate
     * @return Project
     */
    public function setContribMaxDate($contribMaxDate)
    {
        $this->contribMaxDate = $contribMaxDate;

        return $this;
    }

    /**
     * Get contribMaxDate
     *
     * @return \DateTime
     */
    public function getContribMaxDate()
    {
        return $this->contribMaxDate;
    }

    /**
     * Set contribMinAmount
     *
     * @param integer $contribMinAmount
     * @return Project
     */
    public function setContribMinAmount($contribMinAmount)
    {
        $this->contribMinAmount = $contribMinAmount;

        return $this;
    }

    /**
     * Get contribMinAmount
     *
     * @return integer
     */
    public function getContribMinAmount()
    {
        return $this->contribMinAmount;
    }

    /**
     * Set owner
     *
     * @param \AppBundle\Entity\User $owner
     * @return Project
     */
    public function setOwner(\AppBundle\Entity\User $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \AppBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
