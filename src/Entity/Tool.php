<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ToolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ToolRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:tool']],
    collectionOperations: [
        'get' => [
            'pagination_enabled' => false,
        ],
    ],
    itemOperations: [
        'get',
    ],
)]
class Tool
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:project','read:tool'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:project','read:tool'])]
    private $name;

    #[ORM\Column(type: 'text')]
    #[Groups(['read:project','read:tool'])]
    private $description;

    #[ORM\OneToOne(targetEntity: File::class, cascade: ['persist', 'remove'])]
    #[Groups(['read:project','read:tool'])]
    private $image;

    #[Assert\Image]
    private $imageTemp;

    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'tools')]
    #[Groups(['read:tool'])]
    private $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageTemp()
    {
        return $this->imageTemp;
    }

    public function setImageTemp($imageTemp): self
    {
        $this->imageTemp = $imageTemp;

        return $this;
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?file
    {
        return $this->image;
    }

    public function setImage(?file $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->addTool($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            $project->removeTool($this);
        }

        return $this;
    }
}
