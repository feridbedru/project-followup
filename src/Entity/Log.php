<?php

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogRepository::class)
 */
class Log
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="logs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $record_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $actiontime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ipaddress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $action;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $target;

    /**
     * @ORM\Column(type="text")
     */
    private $original;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $modified;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRecordId(): ?int
    {
        return $this->record_id;
    }

    public function setRecordId(?int $record_id): self
    {
        $this->record_id = $record_id;

        return $this;
    }

    public function getActiontime(): ?string
    {
        return $this->actiontime;
    }

    public function setActiontime(string $actiontime): self
    {
        $this->actiontime = $actiontime;

        return $this;
    }

    public function getIpaddress(): ?string
    {
        return $this->ipaddress;
    }

    public function setIpaddress(string $ipaddress): self
    {
        $this->ipaddress = $ipaddress;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(string $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getOriginal(): ?string
    {
        return $this->original;
    }

    public function setOriginal(string $original): self
    {
        $this->original = $original;

        return $this;
    }

    public function getModified(): ?string
    {
        return $this->modified;
    }

    public function setModified(?string $modified): self
    {
        $this->modified = $modified;

        return $this;
    }
}
