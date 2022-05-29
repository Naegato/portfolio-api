<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FileRepository::class)]
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
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:project','read:techno','read:tool','read:curriculum'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:curriculum'])]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:project','read:techno','read:tool','read:curriculum'])]
    private $extension;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:project','read:techno','read:tool','read:curriculum'])]
    private $path;

    #[ORM\Column(type: 'integer')]
    #[Groups(['read:project','read:techno','read:tool','read:curriculum'])]
    private $size;

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

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }
}
