<?php

namespace App\Entity;

use App\Repository\ProjectMembersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectMembersRepository::class)
 */
class ProjectMembers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectTeam::class, inversedBy="projectMembers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectRole::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_working_on_task;

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

    public function getTeam(): ?ProjectTeam
    {
        return $this->team;
    }

    public function setTeam(?ProjectTeam $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRole(): ?ProjectRole
    {
        return $this->role;
    }

    public function setRole(?ProjectRole $role): self
    {
        $this->role = $role;

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

    public function getIsWorkingOnTask(): ?bool
    {
        return $this->is_working_on_task;
    }

    public function setIsWorkingOnTask(bool $is_working_on_task): self
    {
        $this->is_working_on_task = $is_working_on_task;

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
