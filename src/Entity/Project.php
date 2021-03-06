<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:project']],
    collectionOperations: [
        'get'=> [
            'pagination_enabled' => false,
        ],
    ],
    itemOperations: [
        'get',
    ],
)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:project','read:techno','read:tool'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:project','read:techno','read:tool'])]
    private $name;

    #[ORM\Column(type: 'text')]
    #[Groups(['read:project'])]
    private $description;

    #[ORM\OneToOne(targetEntity: File::class, cascade: ['persist', 'remove'])]
    #[Groups(['read:project'])]
    private $overview;

    #[Assert\Image]
    private $overviewTemp;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: ProjectImage::class, orphanRemoval: true)]
    #[Groups(['read:project'])]
    private $images;

    private $imagesTemp;

    #[ORM\Column(type: 'date')]
    #[Groups(['read:project'])]
    private $dateStart;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(['read:project'])]
    private $dateEnd;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['read:project'])]
    private $duration;

    #[ORM\ManyToMany(targetEntity: Techno::class, inversedBy: 'projects')]
    #[Groups(['read:project'])]
    private $technos;

    private $technosTemp = [];

    #[ORM\ManyToMany(targetEntity: Tool::class, inversedBy: 'projects')]
    #[Groups(['read:project'])]
    private $tools;

    private $toolsTemp = [];

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->technos = new ArrayCollection();
        $this->tools = new ArrayCollection();
        $this->dateEnd = null;
        $this->dateStart = new \DateTime();
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

    public function getImagesTemp()
    {
        return $this->imagesTemp;
    }

    public function setImagesTemp($imagesTemp): self
    {
        $this->imagesTemp = $imagesTemp;

        return $this;
    }

    public function getOverviewTemp()
    {
        return $this->overviewTemp;
    }

    public function setOverviewTemp($overviewTemp): self
    {
        $this->overviewTemp = $overviewTemp;

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

    public function getOverview(): ?file
    {
        return $this->overview;
    }

    public function setOverview(?file $overview): self
    {
        $this->overview = $overview;

        return $this;
    }

    public function getTechnosTemp()
    {
        return $this->technosTemp;
    }

    public function addTechnosTemp(?Techno $techno): self
    {
        if ($techno !== null) {
            if (!in_array($techno,$this->technosTemp,true)) {
                $this->technosTemp[] = $techno;
            }
        }

        return $this;
    }

    public function getToolsTemp()
    {
        return $this->toolsTemp;
    }

    public function addToolsTemp(?Tool $tool): self
    {
        if ($tool !== null) {
            if (!in_array($tool,$this->toolsTemp,true)) {
                $this->toolsTemp[] = $tool;
            }
        }

        return $this;
    }

    public function copyToolsTemp(): self
    {
        $this->toolsTemp = $this->tools->getValues();

        return $this;
    }

    public function copyTechnosTemp(): self
    {
        $this->technosTemp = $this->technos->getValues();

        return $this;
    }

    /**
     * @return Collection<int, projectImage>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(projectImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProject($this);
        }

        return $this;
    }

    public function removeImage(projectImage $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProject() === $this) {
                $image->setProject(null);
            }
        }

        return $this;
    }

    public function removeTechnosTemp(Techno $techno): self
    {
        $array = [];
        foreach ($this->technosTemp as $tech){
           if ($techno != $tech) {
               $array[] = $tech;
           }
        }

        $this->technosTemp = $array;

        return $this;
    }

    public function removeToolsTemp(Tool $tool): self
    {
        $array = [];
        foreach ($this->toolsTemp as $too){
            if ($tool != $too) {
                $array[] = $too;
            }
        }

        $this->toolsTemp = $array;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection<int, techno>
     */
    public function getTechnos(): Collection
    {
        return $this->technos;
    }

    public function addTechno(techno $techno): self
    {
        if (!$this->technos->contains($techno)) {
            $this->technos[] = $techno;
        }

        return $this;
    }

    public function removeTechno(techno $techno): self
    {
        $this->technos->removeElement($techno);

        return $this;
    }

    /**
     * @return Collection<int, tool>
     */
    public function getTools(): Collection
    {
        return $this->tools;
    }

    public function addTool(tool $tool): self
    {
        if (!$this->tools->contains($tool)) {
            $this->tools[] = $tool;
        }

        return $this;
    }

    public function removeTool(tool $tool): self
    {
        $this->tools->removeElement($tool);

        return $this;
    }
}
