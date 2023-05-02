<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $specialite = null;

    #[ORM\OneToMany(mappedBy: 'id_matiere', targetEntity: Cours::class,cascade: ['persist', 'remove'])]
    #[Ignore]
    private Collection $cours;

    #[ORM\ManyToOne(inversedBy: 'matieres')]
    #[ORM\JoinColumn(nullable: false,name: 'id_intervenant', referencedColumnName:'id_intervenant')]
    private ?Intervenant $intervenant = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?int $nbHours = null;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLibelle();
    }

    public function getFullName(): string 
    {
        return $this->getLibelle() . ' - ' . $this->getSpecialite();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setIdMatiere($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getIdMatiere() === $this) {
                $cour->setIdMatiere(null);
            }
        }

        return $this;
    }

    public function getIntervenant(): ?Intervenant
    {
        return $this->intervenant;
    }

    public function setIntervenant(?Intervenant $intervenant): self
    {
        $this->intervenant = $intervenant;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getNbHours(): ?int
    {
        return $this->nbHours;
    }

    public function setNbHours(int $nbHours): self
    {
        $this->nbHours = $nbHours;

        return $this;
    }
}
