<?php

namespace App\Entity;

use App\Repository\ProjectDeliverableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectDeliverableRepository::class)
 */
class ProjectDeliverable
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $delivery_date;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $percentage;

    /**
     * @ORM\Column(type="date")
     */
    private $planned_delivery_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $verify_deliverable;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    /**
     * @ORM\OneToMany(targetEntity=ProjectDeliverableStatus::class, mappedBy="deliverable")
     */
    private $projectDeliverableStatuses;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="projectDeliverables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectMilestone::class, inversedBy="projectDeliverables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $milestone;

    public function __construct()
    {
        $this->projectDeliverableStatuses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->delivery_date;
    }

    public function setDeliveryDate(\DateTimeInterface $delivery_date): self
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    public function getPercentage(): ?float
    {
        return $this->percentage;
    }

    public function setPercentage(?float $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getPlannedDeliveryDate(): ?\DateTimeInterface
    {
        return $this->planned_delivery_date;
    }

    public function setPlannedDeliveryDate(\DateTimeInterface $planned_delivery_date): self
    {
        $this->planned_delivery_date = $planned_delivery_date;

        return $this;
    }

    public function getVerifyDeliverable(): ?bool
    {
        return $this->verify_deliverable;
    }

    public function setVerifyDeliverable(bool $verify_deliverable): self
    {
        $this->verify_deliverable = $verify_deliverable;

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

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * @return Collection|ProjectDeliverableStatus[]
     */
    public function getProjectDeliverableStatuses(): Collection
    {
        return $this->projectDeliverableStatuses;
    }

    public function addProjectDeliverableStatus(ProjectDeliverableStatus $projectDeliverableStatus): self
    {
        if (!$this->projectDeliverableStatuses->contains($projectDeliverableStatus)) {
            $this->projectDeliverableStatuses[] = $projectDeliverableStatus;
            $projectDeliverableStatus->setDeliverable($this);
        }

        return $this;
    }

    public function removeProjectDeliverableStatus(ProjectDeliverableStatus $projectDeliverableStatus): self
    {
        if ($this->projectDeliverableStatuses->removeElement($projectDeliverableStatus)) {
            // set the owning side to null (unless already changed)
            if ($projectDeliverableStatus->getDeliverable() === $this) {
                $projectDeliverableStatus->setDeliverable(null);
            }
        }

        return $this;
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

    public function __toString()
    {
        return $this->title;
    }

    public function getMilestone(): ?ProjectMilestone
    {
        return $this->milestone;
    }

    public function setMilestone(?ProjectMilestone $milestone): self
    {
        $this->milestone = $milestone;

        return $this;
    }
}
