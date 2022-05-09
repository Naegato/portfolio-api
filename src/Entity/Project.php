<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\OneToOne(targetEntity: File::class, cascade: ['persist', 'remove'])]
    private $overview;

    private $overviewTemp;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: ProjectImage::class, orphanRemoval: true)]
    private $images;

    #[ORM\Column(type: 'datetime')]
    private $dateStart;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateEnd;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $duration;

    #[ORM\ManyToMany(targetEntity: Techno::class, inversedBy: 'projects')]
    private $technos;

    #[ORM\ManyToMany(targetEntity: Tool::class, inversedBy: 'projects')]
    private $tools;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->technos = new ArrayCollection();
        $this->tools = new ArrayCollection();
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