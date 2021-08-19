<?php

namespace App\Entity;

use App\Repository\ProjectPlanRevisionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectPlanRevisionRepository::class)
 */
class ProjectPlanRevision
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="projectPlanRevisions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $revision_id;

    /**
     * @ORM\Column(type="text")
     */
    private $revision_details;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

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

    public function getRevisionId(): ?string
    {
        return $this->revision_id;
    }

    public function setRevisionId(string $revision_id): self
    {
        $this->revision_id = $revision_id;

        return $this;
    }

    public function getRevisionDetails(): ?string
    {
        return $this->revision_details;
    }

    public function setRevisionDetails(string $revision_details): self
    {
        $this->revision_details = $revision_details;

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
}
