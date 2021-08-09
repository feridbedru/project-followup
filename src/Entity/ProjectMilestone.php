<?php

namespace App\Entity;

use App\Repository\ProjectMilestoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectMilestoneRepository::class)
 */
class ProjectMilestone
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="projectMilestones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $last_revision;

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
     * @ORM\OneToMany(targetEntity=ProjectMilestoneStatus::class, mappedBy="milestone")
     */
    private $projectMilestoneStatuses;

    /**
     * @ORM\OneToMany(targetEntity=ProjectActivity::class, mappedBy="milestone")
     */
    private $projectActivities;

    /**
     * @ORM\Column(type="date")
     */
    private $planned_delivery_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activities_equal_weight;

    /**
     * @ORM\OneToMany(targetEntity=ProjectDeliverable::class, mappedBy="milestone")
     */
    private $projectDeliverables;

    /**
     * @ORM\Column(type="integer")
     */
    private $weight;

    public function __construct()
    {
        $this->projectMilestoneStatuses = new ArrayCollection();
        $this->projectActivities = new ArrayCollection();
        $this->projectDeliverables = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getLastRevision(): ?\DateTimeInterface
    {
        return $this->last_revision;
    }

    public function setLastRevision(?\DateTimeInterface $last_revision): self
    {
        $this->last_revision = $last_revision;

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

    /**
     * @return Collection|ProjectMilestoneStatus[]
     */
    public function getProjectMilestoneStatuses(): Collection
    {
        return $this->projectMilestoneStatuses;
    }

    public function addProjectMilestoneStatus(ProjectMilestoneStatus $projectMilestoneStatus): self
    {
        if (!$this->projectMilestoneStatuses->contains($projectMilestoneStatus)) {
            $this->projectMilestoneStatuses[] = $projectMilestoneStatus;
            $projectMilestoneStatus->setMilestone($this);
        }

        return $this;
    }

    public function removeProjectMilestoneStatus(ProjectMilestoneStatus $projectMilestoneStatus): self
    {
        if ($this->projectMilestoneStatuses->removeElement($projectMilestoneStatus)) {
            // set the owning side to null (unless already changed)
            if ($projectMilestoneStatus->getMilestone() === $this) {
                $projectMilestoneStatus->setMilestone(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProjectActivity[]
     */
    public function getProjectActivities(): Collection
    {
        return $this->projectActivities;
    }

    public function addProjectActivity(ProjectActivity $projectActivity): self
    {
        if (!$this->projectActivities->contains($projectActivity)) {
            $this->projectActivities[] = $projectActivity;
            $projectActivity->setMilestone($this);
        }

        return $this;
    }

    public function removeProjectActivity(ProjectActivity $projectActivity): self
    {
        if ($this->projectActivities->removeElement($projectActivity)) {
            // set the owning side to null (unless already changed)
            if ($projectActivity->getMilestone() === $this) {
                $projectActivity->setMilestone(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
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

    public function getActivitiesEqualWeight(): ?bool
    {
        return $this->activities_equal_weight;
    }

    public function setActivitiesEqualWeight(bool $activities_equal_weight): self
    {
        $this->activities_equal_weight = $activities_equal_weight;

        return $this;
    }

    /**
     * @return Collection|ProjectDeliverable[]
     */
    public function getProjectDeliverables(): Collection
    {
        return $this->projectDeliverables;
    }

    public function addProjectDeliverable(ProjectDeliverable $projectDeliverable): self
    {
        if (!$this->projectDeliverables->contains($projectDeliverable)) {
            $this->projectDeliverables[] = $projectDeliverable;
            $projectDeliverable->setMilestone($this);
        }

        return $this;
    }

    public function removeProjectDeliverable(ProjectDeliverable $projectDeliverable): self
    {
        if ($this->projectDeliverables->removeElement($projectDeliverable)) {
            // set the owning side to null (unless already changed)
            if ($projectDeliverable->getMilestone() === $this) {
                $projectDeliverable->setMilestone(null);
            }
        }

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }
}
