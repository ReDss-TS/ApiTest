<?php

namespace Api\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Api\Entity\CompanyEntity
 *
 * @ORM\Entity
 * @ORM\Table(name="company")
 */
class CompanyEntity extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="company_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="company_name")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="company_symbol")
     */
    protected $symbol;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     */
    public function setSymbol(string $symbol): void
    {
        $this->symbol = $symbol;
    }


}