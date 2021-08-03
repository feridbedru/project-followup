<?php

namespace App\Entity;

use App\Repository\ProjectCollaborationTopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectCollaborationTopicRepository::class)
 */
class ProjectCollaborationTopic
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="projectCollaborationTopics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

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
     * @ORM\OneToMany(targetEntity=ActivityChat::class, mappedBy="topic")
     */
    private $activityChats;

    public function __construct()
    {
        $this->activityChats = new ArrayCollection();
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
            $activityChat->setTopic($this);
        }

        return $this;
    }

    public function removeActivityChat(ActivityChat $activityChat): self
    {
        if ($this->activityChats->removeElement($activityChat)) {
            // set the owning side to null (unless already changed)
            if ($activityChat->getTopic() === $this) {
                $activityChat->setTopic(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->title;
    }
}
