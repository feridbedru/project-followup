<?php

namespace App\Entity;

use App\Repository\ProjectStructureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectStructureRepository::class)
 */
class ProjectStructure
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
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="projectStructures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectStructure::class)
     */
    private $reports_to;

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
     * @ORM\ManyToMany(targetEntity=EmailTemplate::class, inversedBy="projectStructures")
     */
    private $email_template;

    /**
     * @ORM\OneToMany(targetEntity=ProjectMembers::class, mappedBy="role")
     */
    private $projectMembers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $one_person_only;

    public function __construct()
    {
        $this->email_template = new ArrayCollection();
        $this->projectMembers = new ArrayCollection();
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

    public function getReportsTo(): ?self
    {
        return $this->reports_to;
    }

    public function setReportsTo(?self $reports_to): self
    {
        $this->reports_to = $reports_to;

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
     * @return Collection|EmailTemplate[]
     */
    public function getEmailTemplate(): Collection
    {
        return $this->email_template;
    }

    public function addEmailTemplate(EmailTemplate $emailTemplate): self
    {
        if (!$this->email_template->contains($emailTemplate)) {
            $this->email_template[] = $emailTemplate;
        }

        return $this;
    }

    public function removeEmailTemplate(EmailTemplate $emailTemplate): self
    {
        $this->email_template->removeElement($emailTemplate);

        return $this;
    }

    public function __toString()
    {
        return $this->name;
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
            $projectMember->setRole($this);
        }

        return $this;
    }

    public function removeProjectMember(ProjectMembers $projectMember): self
    {
        if ($this->projectMembers->removeElement($projectMember)) {
            // set the owning side to null (unless already changed)
            if ($projectMember->getRole() === $this) {
                $projectMember->setRole(null);
            }
        }

        return $this;
    }

    public function getOnePersonOnly(): ?bool
    {
        return $this->one_person_only;
    }

    public function setOnePersonOnly(bool $one_person_only): self
    {
        $this->one_person_only = $one_person_only;

        return $this;
    }
}
