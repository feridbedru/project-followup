<?php

namespace App\Entity;

use App\Repository\ProjectResourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectResourceRepository::class)
 */
class ProjectResource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="projectResources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity=ResourceType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $resource_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_public;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_pinned;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $uploaded_by;

    /**
     * @ORM\Column(type="datetime")
     */
    private $uploaded_at;

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

    public function getResourceType(): ?ResourceType
    {
        return $this->resource_type;
    }

    public function setResourceType(?ResourceType $resource_type): self
    {
        $this->resource_type = $resource_type;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->is_public;
    }

    public function setIsPublic(?bool $is_public): self
    {
        $this->is_public = $is_public;

        return $this;
    }

    public function getIsPinned(): ?bool
    {
        return $this->is_pinned;
    }

    public function setIsPinned(?bool $is_pinned): self
    {
        $this->is_pinned = $is_pinned;

        return $this;
    }

    public function getUploadedBy(): ?User
    {
        return $this->uploaded_by;
    }

    public function setUploadedBy(?User $uploaded_by): self
    {
        $this->uploaded_by = $uploaded_by;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeInterface
    {
        return $this->uploaded_at;
    }

    public function setUploadedAt(\DateTimeInterface $uploaded_at): self
    {
        $this->uploaded_at = $uploaded_at;

        return $this;
    }
}
