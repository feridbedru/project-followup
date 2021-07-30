<?php

namespace App\Entity;

use App\Repository\ProjectActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectActivityRepository::class)
 */
class ProjectActivity
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
     * @ORM\ManyToOne(targetEntity=ProjectMilestone::class, inversedBy="projectActivities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $milestone;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="date")
     */
    private $due_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $display_order;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;

    /**
     * @ORM\Column(type="boolean")
     */
    private $can_be_concurrent;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weight;

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
     * @ORM\OneToMany(targetEntity=ActivityFiles::class, mappedBy="activity")
     */
    private $activityFiles;

    /**
     * @ORM\OneToMany(targetEntity=ActivityUser::class, mappedBy="activity")
     */
    private $activityUsers;

    /**
     * @ORM\OneToMany(targetEntity=ActivityProgress::class, mappedBy="activity")
     */
    private $activityProgress;

    /**
     * @ORM\OneToMany(targetEntity=ActivityChat::class, mappedBy="activity")
     */
    private $activityChats;

    public function __construct()
    {
        $this->activityFiles = new ArrayCollection();
        $this->activityUsers = new ArrayCollection();
        $this->activityProgress = new ArrayCollection();
        $this->activityChats = new ArrayCollection();
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

    public function getMilestone(): ?ProjectMilestone
    {
        return $this->milestone;
    }

    public function setMilestone(?ProjectMilestone $milestone): self
    {
        $this->milestone = $milestone;

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

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(\DateTimeInterface $due_date): self
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getDisplayOrder(): ?int
    {
        return $this->display_order;
    }

    public function setDisplayOrder(int $display_order): self
    {
        $this->display_order = $display_order;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getCanBeConcurrent(): ?bool
    {
        return $this->can_be_concurrent;
    }

    public function setCanBeConcurrent(bool $can_be_concurrent): self
    {
        $this->can_be_concurrent = $can_be_concurrent;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

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
     * @return Collection|ActivityFiles[]
     */
    public function getActivityFiles(): Collection
    {
        return $this->activityFiles;
    }

    public function addActivityFile(ActivityFiles $activityFile): self
    {
        if (!$this->activityFiles->contains($activityFile)) {
            $this->activityFiles[] = $activityFile;
            $activityFile->setActivity($this);
        }

        return $this;
    }

    public function removeActivityFile(ActivityFiles $activityFile): self
    {
        if ($this->activityFiles->removeElement($activityFile)) {
            // set the owning side to null (unless already changed)
            if ($activityFile->getActivity() === $this) {
                $activityFile->setActivity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ActivityUser[]
     */
    public function getActivityUsers(): Collection
    {
        return $this->activityUsers;
    }

    public function addActivityUser(ActivityUser $activityUser): self
    {
        if (!$this->activityUsers->contains($activityUser)) {
            $this->activityUsers[] = $activityUser;
            $activityUser->setActivity($this);
        }

        return $this;
    }

    public function removeActivityUser(ActivityUser $activityUser): self
    {
        if ($this->activityUsers->removeElement($activityUser)) {
            // set the owning side to null (unless already changed)
            if ($activityUser->getActivity() === $this) {
                $activityUser->setActivity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ActivityProgress[]
     */
    public function getActivityProgress(): Collection
    {
        return $this->activityProgress;
    }

    public function addActivityProgress(ActivityProgress $activityProgress): self
    {
        if (!$this->activityProgress->contains($activityProgress)) {
            $this->activityProgress[] = $activityProgress;
            $activityProgress->setActivity($this);
        }

        return $this;
    }

    public function removeActivityProgress(ActivityProgress $activityProgress): self
    {
        if ($this->activityProgress->removeElement($activityProgress)) {
            // set the owning side to null (unless already changed)
            if ($activityProgress->getActivity() === $this) {
                $activityProgress->setActivity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ActivityChat[]
     */
    public function getActivityChats(): Collection
    {
        return $this->activityChats;
    }

    public function addActivityChat(ActivityChat $activityChat): self
    {
        if (!$this->activityChats->contains($activityChat)) {
            $this->activityChats[] = $activityChat;
            $activityChat->setActivity($this);
        }

        return $this;
    }

    public function removeActivityChat(ActivityChat $activityChat): self
    {
        if ($this->activityChats->removeElement($activityChat)) {
            // set the owning side to null (unless already changed)
            if ($activityChat->getActivity() === $this) {
                $activityChat->setActivity(null);
            }
        }

        return $this;
    }
}
