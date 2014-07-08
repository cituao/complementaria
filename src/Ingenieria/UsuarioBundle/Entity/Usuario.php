<?php

namespace Ingenieria\UsuarioBundle\Entity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Usuario
 */
class Usuario implements AdvancedUserInterface, \Serializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var string
     */
    private $password;

    /**
     * @var boolean
     */
    private $isActive;

	private $roles;
	
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
     * @return Usuario
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
     * Set salt
     *
     * @param string $salt
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Usuario
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return Usuario
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
		$this->setIsActive(true);
    }

    /**
     * Add roles
     *
     * @param \Ingenieria\UsuarioBundle\Entity\Role $roles
     * @return Usuario
     */
    public function addRole(\Ingenieria\UsuarioBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Ingenieria\UsuarioBundle\Entity\Role $roles
     */
    public function removeRole(\Ingenieria\UsuarioBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoles()
    {
		return $this->roles->toArray();
        //return $this->roles;
    }
	
    /**
     * @inheritDoc
     */
    public function isAccountNonLocked()
    {
        return true;
    }

	/**
     * @inheritDoc
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }
	
    /**
     * @inheritDoc
     */
   public function isAccountNonExpired()
    {
        return true;
    }
	
	    /**
     * @inheritDoc
     */

    public function isEnabled()
    {
        return $this->isActive;
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
            $this->salt,
            $this->password,
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
            $this->salt,
            $this->password,
        ) = unserialize($serialized);
    }

}
