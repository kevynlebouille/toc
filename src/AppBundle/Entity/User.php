<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User implements UserInterface, \Serializable
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
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Project", mappedBy="owner")
     */
    private $ownedProjects;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Contrib", mappedBy="user")
     */
    private $contribs;

    public function __construct()
    {
        $this->ownedProjects = new ArrayCollection();
        $this->contribs      = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }

    /**
     * Add ownedProject
     *
     * @param \AppBundle\Entity\Project $ownedProject
     * @return User
     */
    public function addOwnedProject(\AppBundle\Entity\Project $ownedProject)
    {
        $this->ownedProjects[] = $ownedProject;

        return $this;
    }

    /**
     * Remove ownedProject
     *
     * @param \AppBundle\Entity\Project $ownedProject
     */
    public function removeOwnedProject(\AppBundle\Entity\Project $ownedProject)
    {
        $this->ownedProjects->removeElement($ownedProject);
    }

    /**
     * Get ownedProjects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOwnedProjects()
    {
        return $this->ownedProjects;
    }

    /**
     * Get contribs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContribs()
    {
        return $this->contribs;
    }
}
