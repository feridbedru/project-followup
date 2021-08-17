<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrganizationRepository::class)
 */
class Organization
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=OrganizationUnit::class, mappedBy="organization")
     */
    private $organizationUnits;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $acronym;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    public function __construct()
    {
        $this->organizationUnits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|OrganizationUnit[]
     */
    public function getOrganizationUnits(): Collection
    {
        return $this->organizationUnits;
    }

    public function addOrganizationUnit(OrganizationUnit $organizationUnit): self
    {
        if (!$this->organizationUnits->contains($organizationUnit)) {
            $this->organizationUnits[] = $organizationUnit;
            $organizationUnit->setOrganization($this);
        }

        return $this;
    }

    public function removeOrganizationUnit(OrganizationUnit $organizationUnit): self
    {
        if ($this->organizationUnits->removeElement($organizationUnit)) {
            // set the owning side to null (unless already changed)
            if ($organizationUnit->getOrganization() === $this) {
                $organizationUnit->setOrganization(null);
            }
        }

        return $this;
    }

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(?string $acronym): self
    {
        $this->acronym = $acronym;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }
}
