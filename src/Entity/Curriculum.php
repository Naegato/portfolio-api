<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CurriculumRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CurriculumRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:curriculum']],
    collectionOperations: [
        'get'=> [
            'pagination_enabled' => false,
        ],
    ],
    itemOperations: [
        'get',
    ],
)]
class Curriculum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:curriculum'])]
    private $id;

    #[ORM\OneToOne(targetEntity: File::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['read:curriculum'])]
    private $file;

    #[Assert\File(
        maxSize: '4096k',
        mimeTypes: ['application/pdf', 'application/x-pdf'],
        mimeTypesMessage: 'Please upload a valid PDF',
    )]
    private $fileTemp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getFileTemp()
    {
        return $this->fileTemp;
    }

    public function setFileTemp($fileTemp): self
    {
        $this->fileTemp = $fileTemp;

        return $this;
    }
}
