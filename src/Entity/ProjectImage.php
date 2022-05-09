<?php

namespace App\Entity;

use App\Repository\ProjectImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectImageRepository::class)]
class ProjectImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\OneToOne(targetEntity: File::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $image;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private $project;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?file
    {
        return $this->image;
    }

    public function setImage(file $image): self
    {
        $this->image = $image;

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
}
