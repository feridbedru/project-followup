<?php

namespace App\Entity;

use App\Repository\ProjectTeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectTeamRepository::class)
 */
class ProjectTeam
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="projectTeams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

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
     * @ORM\OneToMany(targetEntity=ProjectMembers::class, mappedBy="team")
     */
    private $projectMembers;

    public function __construct()
    {
        $this->projectMembers = new ArrayCollection();
    }

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
     * @return Collection|ProjectMembers[]
     */
    public function getProjectMembers(): Collection
    {
        return $this->projectMembers;
    }

    public function addProjectMember(ProjectMembers $projectMember): self
    {
        if (!$this->projectMembers->contains($projectMember)) {
            $this->projectMembers[] = $projectMember;
            $projectMember->setTeam($this);
        }

        return $this;
    }

    public function removeProjectMember(ProjectMembers $projectMember): self
    {
        if ($this->projectMembers->removeElement($projectMember)) {
            // set the owning side to null (unless already changed)
            if ($projectMember->getTeam() === $this) {
                $projectMember->setTeam(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
