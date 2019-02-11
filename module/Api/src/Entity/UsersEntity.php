<?php

namespace Api\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use API\Entity\TokensEntity;
use Doctrine\ORM\Mapping as ORM;
use stdClass;

/**
 * Api\Entity\UsersEntity
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class UsersEntity extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="user_email")
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="user_password")
     */
    protected $password;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }
}