<?php

namespace App\Entity;

use App\Repository\EmailTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmailTemplateRepository::class)
 */
class EmailTemplate
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
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToMany(targetEntity=ProjectStructure::class, mappedBy="email_template")
     */
    private $projectStructures;

    public function __construct()
    {
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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
            $projectStructure->addEmailTemplate($this);
        }

        return $this;
    }

    public function removeProjectStructure(ProjectStructure $projectStructure): self
    {
        if ($this->projectStructures->removeElement($projectStructure)) {
            $projectStructure->removeEmailTemplate($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
