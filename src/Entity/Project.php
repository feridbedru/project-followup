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
     * @ORM\OneToMany(targetEntity=ProjectDeliverable::class, mappedBy="project")
     */
    private $projectDeliverables;

    /**
     * @ORM\OneToMany(targetEntity=ProjectMilestone::class, mappedBy="project")
     */
    private $projectMilestones;

    /**
     * @ORM\OneToMany(targetEntity=ProjectMemberHistory::class, mappedBy="project")
     */
    private $projectMemberHistories;

    /**
     * @ORM\OneToMany(targetEntity=ProjectMembers::class, mappedBy="project")
     */
    private $projectMembers;

    /**
     * @ORM\OneToMany(targetEntity=ProjectActivity::class, mappedBy="project")
     */
    private $projectActivities;

    /**
     * @ORM\OneToMany(targetEntity=ProjectCollaborationTopic::class, mappedBy="project")
     */
    private $projectCollaborationTopics;

    /**
     * @ORM\OneToMany(targetEntity=Stakeholder::class, mappedBy="project")
     */
    private $stakeholder;

    /**
     * @ORM\OneToMany(targetEntity=ProjectStructure::class, mappedBy="project")
     */
    private $projectStructures;

    public function __construct()
    {
        $this->projectVersions = new ArrayCollection();
        $this->projectSponsors = new ArrayCollection();
        $this->projectResources = new ArrayCollection();
        $this->projectDeliverables = new ArrayCollection();
        $this->projectMilestones = new ArrayCollection();
        $this->projectMemberHistories = new ArrayCollection();
        $this->projectMembers = new ArrayCollection();
        $this->projectActivities = new ArrayCollection();
        $this->projectCollaborationTopics = new ArrayCollection();
        $this->stakeholder = new ArrayCollection();
        $this->projectStructures = new ArrayCollection();
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

    /**
     * @return Collection|ProjectMemberHistory[]
     */
    public function getProjectMemberHistories(): Collection
    {
        return $this->projectMemberHistories;
    }

    public function addProjectMemberHistory(ProjectMemberHistory $projectMemberHistory): self
    {
        if (!$this->projectMemberHistories->contains($projectMemberHistory)) {
            $this->projectMemberHistories[] = $projectMemberHistory;
            $projectMemberHistory->setProject($this);
        }

        return $this;
    }

    public function removeProjectMemberHistory(ProjectMemberHistory $projectMemberHistory): self
    {
        if ($this->projectMemberHistories->removeElement($projectMemberHistory)) {
            // set the owning side to null (unless already changed)
            if ($projectMemberHistory->getProject() === $this) {
                $projectMemberHistory->setProject(null);
            }
        }

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
            $projectMember->setProject($this);
        }

        return $this;
    }

    public function removeProjectMember(ProjectMembers $projectMember): self
    {
        if ($this->projectMembers->removeElement($projectMember)) {
            // set the owning side to null (unless already changed)
            if ($projectMember->getProject() === $this) {
                $projectMember->setProject(null);
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
            $projectActivity->setProject($this);
        }

        return $this;
    }

    public function removeProjectActivity(ProjectActivity $projectActivity): self
    {
        if ($this->projectActivities->removeElement($projectActivity)) {
            // set the owning side to null (unless already changed)
            if ($projectActivity->getProject() === $this) {
                $projectActivity->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProjectCollaborationTopic[]
     */
    public function getProjectCollaborationTopics(): Collection
    {
        return $this->projectCollaborationTopics;
    }

    public function addProjectCollaborationTopic(ProjectCollaborationTopic $projectCollaborationTopic): self
    {
        if (!$this->projectCollaborationTopics->contains($projectCollaborationTopic)) {
            $this->projectCollaborationTopics[] = $projectCollaborationTopic;
            $projectCollaborationTopic->setProject($this);
        }

        return $this;
    }

    public function removeProjectCollaborationTopic(ProjectCollaborationTopic $projectCollaborationTopic): self
    {
        if ($this->projectCollaborationTopics->removeElement($projectCollaborationTopic)) {
            // set the owning side to null (unless already changed)
            if ($projectCollaborationTopic->getProject() === $this) {
                $projectCollaborationTopic->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Stakeholder[]
     */
    public function getStakeholder(): Collection
    {
        return $this->stakeholder;
    }

    public function addStakeholder(Stakeholder $stakeholder): self
    {
        if (!$this->stakeholder->contains($stakeholder)) {
            $this->stakeholder[] = $stakeholder;
            $stakeholder->setProject($this);
        }

        return $this;
    }

    public function removeStakeholder(Stakeholder $stakeholder): self
    {
        if ($this->stakeholder->removeElement($stakeholder)) {
            // set the owning side to null (unless already changed)
            if ($stakeholder->getProject() === $this) {
                $stakeholder->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProjectStructure[]
     */
    public function getProjectStructures(): Collection
    {
        return $this->projectStructures;
    }

    public function addProjectStructure(ProjectStructure $projectStructure): self
    {
        if (!$this->projectStructures->contains($projectStructure)) {
            $this->projectStructures[] = $projectStructure;
            $projectStructure->setProject($this);
        }

        return $this;
    }

    public function removeProjectStructure(ProjectStructure $projectStructure): self
    {
        if ($this->projectStructures->removeElement($projectStructure)) {
            // set the owning side to null (unless already changed)
            if ($projectStructure->getProject() === $this) {
                $projectStructure->setProject(null);
            }
        }

        return $this;
    }
}
