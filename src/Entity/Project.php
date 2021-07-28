<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
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
     * @ORM\ManyToOne(targetEntity=ProjectCategory::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $project_manager;

    /**
     * @ORM\ManyToOne(targetEntity=Currency::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $currency;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="date")
     */
    private $start_date;

    /**
     * @ORM\Column(type="date")
     */
    private $end_date;

    /**
     * @ORM\ManyToOne(targetEntity=Program::class, inversedBy="projects")
     */
    private $program;

    /**
     * @ORM\Column(type="text")
     */
    private $stakeholders;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $outcome;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_At;

    /**
     * @ORM\OneToMany(targetEntity=ProjectVersion::class, mappedBy="project")
     */
    private $projectVersions;

    /**
     * @ORM\OneToMany(targetEntity=ProjectSponsor::class, mappedBy="project")
     */
    private $projectSponsors;

    /**
     * @ORM\OneToMany(targetEntity=ProjectResource::class, mappedBy="project")
     */
    private $projectResources;

    /**
     * @ORM\OneToMany(targetEntity=ProjectTeam::class, mappedBy="project")
     */
    private $projectTeams;

    /**
     * @ORM\OneToMany(targetEntity=ProjectDeliverable::class, mappedBy="project")
     */
    private $projectDeliverables;

    /**
     * @ORM\OneToMany(targetEntity=ProjectMilestone::class, mappedBy="project")
     */
    private $projectMilestones;

    public function __construct()
    {
        $this->projectVersions = new ArrayCollection();
        $this->projectSponsors = new ArrayCollection();
        $this->projectResources = new ArrayCollection();
        $this->projectTeams = new ArrayCollection();
        $this->projectDeliverables = new ArrayCollection();
        $this->projectMilestones = new ArrayCollection();
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

    public function getCategory(): ?ProjectCategory
    {
        return $this->category;
    }

    public function setCategory(?ProjectCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getProjectManager(): ?User
    {
        return $this->project_manager;
    }

    public function setProjectManager(?User $project_manager): self
    {
        $this->project_manager = $project_manager;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

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

    public function getProgram(): ?Program
    {
        return $this->program;
    }

    public function setProgram(?Program $program): self
    {
        $this->program = $program;

        return $this;
    }

    public function getStakeholders(): ?string
    {
        return $this->stakeholders;
    }

    public function setStakeholders(string $stakeholders): self
    {
        $this->stakeholders = $stakeholders;

        return $this;
    }

    public function getOutcome(): ?string
    {
        return $this->outcome;
    }

    public function setOutcome(?string $outcome): self
    {
        $this->outcome = $outcome;

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
        return $this->created_At;
    }

    public function setCreatedAt(\DateTimeInterface $created_At): self
    {
        $this->created_At = $created_At;

        return $this;
    }

    /**
     * @return Collection|ProjectVersion[]
     */
    public function getProjectVersions(): Collection
    {
        return $this->projectVersions;
    }

    public function addProjectVersion(ProjectVersion $projectVersion): self
    {
        if (!$this->projectVersions->contains($projectVersion)) {
            $this->projectVersions[] = $projectVersion;
            $projectVersion->setProject($this);
        }

        return $this;
    }

    public function removeProjectVersion(ProjectVersion $projectVersion): self
    {
        if ($this->projectVersions->removeElement($projectVersion)) {
            // set the owning side to null (unless already changed)
            if ($projectVersion->getProject() === $this) {
                $projectVersion->setProject(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|ProjectSponsor[]
     */
    public function getProjectSponsors(): Collection
    {
        return $this->projectSponsors;
    }

    public function addProjectSponsor(ProjectSponsor $projectSponsor): self
    {
        if (!$this->projectSponsors->contains($projectSponsor)) {
            $this->projectSponsors[] = $projectSponsor;
            $projectSponsor->setProject($this);
        }

        return $this;
    }

    public function removeProjectSponsor(ProjectSponsor $projectSponsor): self
    {
        if ($this->projectSponsors->removeElement($projectSponsor)) {
            // set the owning side to null (unless already changed)
            if ($projectSponsor->getProject() === $this) {
                $projectSponsor->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProjectResource[]
     */
    public function getProjectResources(): Collection
    {
        return $this->projectResources;
    }

    public function addProjectResource(ProjectResource $projectResource): self
    {
        if (!$this->projectResources->contains($projectResource)) {
            $this->projectResources[] = $projectResource;
            $projectResource->setProject($this);
        }

        return $this;
    }

    public function removeProjectResource(ProjectResource $projectResource): self
    {
        if ($this->projectResources->removeElement($projectResource)) {
            // set the owning side to null (unless already changed)
            if ($projectResource->getProject() === $this) {
                $projectResource->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProjectTeam[]
     */
    public function getProjectTeams(): Collection
    {
        return $this->projectTeams;
    }

    public function addProjectTeam(ProjectTeam $projectTeam): self
    {
        if (!$this->projectTeams->contains($projectTeam)) {
            $this->projectTeams[] = $projectTeam;
            $projectTeam->setProject($this);
        }

        return $this;
    }

    public function removeProjectTeam(ProjectTeam $projectTeam): self
    {
        if ($this->projectTeams->removeElement($projectTeam)) {
            // set the owning side to null (unless already changed)
            if ($projectTeam->getProject() === $this) {
                $projectTeam->setProject(null);
            }
        }

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
            $projectDeliverable->setProject($this);
        }

        return $this;
    }

    public function removeProjectDeliverable(ProjectDeliverable $projectDeliverable): self
    {
        if ($this->projectDeliverables->removeElement($projectDeliverable)) {
            // set the owning side to null (unless already changed)
            if ($projectDeliverable->getProject() === $this) {
                $projectDeliverable->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProjectMilestone[]
     */
    public function getProjectMilestones(): Collection
    {
        return $this->projectMilestones;
    }

    public function addProjectMilestone(ProjectMilestone $projectMilestone): self
    {
        if (!$this->projectMilestones->contains($projectMilestone)) {
            $this->projectMilestones[] = $projectMilestone;
            $projectMilestone->setProject($this);
        }

        return $this;
    }

    public function removeProjectMilestone(ProjectMilestone $projectMilestone): self
    {
        if ($this->projectMilestones->removeElement($projectMilestone)) {
            // set the owning side to null (unless already changed)
            if ($projectMilestone->getProject() === $this) {
                $projectMilestone->setProject(null);
            }
        }

        return $this;
    }
}
