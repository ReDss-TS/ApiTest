<?php

namespace Api\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Api\Entity\TokensEntity
 *
 * @ORM\Entity
 * @ORM\Table(name="tokens")
 */
class TokensEntity extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="token_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="token_body")
     */
    protected $body;

    /**
     * @ORM\Column(name="token_date_created")
     */
    protected $created_at;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="\Api\Entity\UsersEntity", inversedBy="tokens")
     * @ORM\JoinColumn(name="token_user_id", referencedColumnName="user_id")
     */
    protected $user;

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
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Api\Entity\UsersEntity $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

}