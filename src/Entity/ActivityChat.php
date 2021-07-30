<?php

namespace App\Entity;

use App\Repository\ActivityChatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityChatRepository::class)
 */
class ActivityChat
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
    private $topic;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectActivity::class, inversedBy="activityChats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activity;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $posted_by;

    /**
     * @ORM\Column(type="datetime")
     */
    private $posted_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

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

    public function getActivity(): ?ProjectActivity
    {
        return $this->activity;
    }

    public function setActivity(?ProjectActivity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getPostedBy(): ?User
    {
        return $this->posted_by;
    }

    public function setPostedBy(?User $posted_by): self
    {
        $this->posted_by = $posted_by;

        return $this;
    }

    public function getPostedAt(): ?\DateTimeInterface
    {
        return $this->posted_at;
    }

    public function setPostedAt(\DateTimeInterface $posted_at): self
    {
        $this->posted_at = $posted_at;

        return $this;
    }
}
