<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProjectImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProjectImageRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get'=> [
            'pagination_enabled' => false,
        ],
    ],
    itemOperations: [
        'get',
    ],
)]
class ProjectImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:project'])]
    private int $id;

    #[ORM\OneToOne(targetEntity: File::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:project'])]
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
