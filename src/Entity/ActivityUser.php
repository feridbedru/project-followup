<?php

namespace App\Entity;

use App\Repository\ActivityUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityUserRepository::class)
 */
class ActivityUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectActivity::class, inversedBy="activityUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activity;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectMembers::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $assignment_description;

    /**
     * @ORM\Column(type="date")
     */
    private $start_date;

    /**
     * @ORM\Column(type="date")
     */
    private $end_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $assigned_by;

    /**
     * @ORM\Column(type="datetime")
     */
    private $assigned_at;

    /**
     * @ORM\OneToMany(targetEntity=ActivityVerification::class, mappedBy="activity_user")
     */
    private $activityVerifications;

    public function __construct()
    {
        $this->activityVerifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivity(): ?ProjectActivity
    {
        return $this->activity;
    }

    public function setActivity(?ProjectActivity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getUser(): ?ProjectMembers
    {
        return $this->user;
    }

    public function setUser(?ProjectMembers $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAssignmentDescription(): ?string
    {
        return $this->assignment_description;
    }

    public function setAssignmentDescription(?string $assignment_description): self
    {
        $this->assignment_description = $assignment_description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

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

    public function getAssignedBy(): ?User
    {
        return $this->assigned_by;
    }

    public function setAssignedBy(?User $assigned_by): self
    {
        $this->assigned_by = $assigned_by;

        return $this;
    }

    public function getAssignedAt(): ?\DateTimeInterface
    {
        return $this->assigned_at;
    }

    public function setAssignedAt(\DateTimeInterface $assigned_at): self
    {
        $this->assigned_at = $assigned_at;

        return $this;
    }

    /**
     * @return Collection|ActivityVerification[]
     */
    public function getActivityVerifications(): Collection
    {
        return $this->activityVerifications;
    }

    public function addActivityVerification(ActivityVerification $activityVerification): self
    {
        if (!$this->activityVerifications->contains($activityVerification)) {
            $this->activityVerifications[] = $activityVerification;
            $activityVerification->setActivityUser($this);
        }

        return $this;
    }

    public function removeActivityVerification(ActivityVerification $activityVerification): self
    {
        if ($this->activityVerifications->removeElement($activityVerification)) {
            // set the owning side to null (unless already changed)
            if ($activityVerification->getActivityUser() === $this) {
                $activityVerification->setActivityUser(null);
            }
        }

        return $this;
    }
}
