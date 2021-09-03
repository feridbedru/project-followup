<?php

namespace App\Entity;

use App\Repository\ProjectSponsorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectSponsorRepository::class)
 */
class ProjectSponsor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="projectSponsors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Organization::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $organization;

    /**
     * @ORM\ManyToOne(targetEntity=SponsorshipType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="text")
     */
    private $additional_info;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getType(): ?SponsorshipType
    {
        return $this->type;
    }

    public function setType(?SponsorshipType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAdditionalInfo(): ?string
    {
        return $this->additional_info;
    }

    public function setAdditionalInfo(string $additional_info): self
    {
        $this->additional_info = $additional_info;

        return $this;
    }
}
