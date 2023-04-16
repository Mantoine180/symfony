<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\IntervenantRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: IntervenantRepository::class)]
class Intervenant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_intervenant = null;

    #[ORM\Column(nullable: true)]
    private ?int $nb_heure = null;

    #[ORM\OneToMany(mappedBy: 'intervenant', targetEntity: Cours::class)]
    #[Ignore]
    private Collection $cours;

    #[ORM\OneToMany(mappedBy: 'intervenant', targetEntity: Matiere::class)]
    #[Ignore]
    private Collection $matieres;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
        $this->matieres = new ArrayCollection();
    }

    public function getIdIntervenant(): ?int
    {
        return $this->id_intervenant;
    }

    public function setIdIntervenant(int $id_intervenant): self
    {
        $this->id_intervenant = $id_intervenant;

        return $this;
    }

    public function getNbHeure(): ?int
    {
        return $this->nb_heure;
    }

    public function setNbHeure(?int $nb_heure): self
    {
        $this->nb_heure = $nb_heure;

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
            $cour->setIntervenant($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getIntervenant() === $this) {
                $cour->setIntervenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Matiere>
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres->add($matiere);
            $matiere->setIntervenant($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        if ($this->matieres->removeElement($matiere)) {
            // set the owning side to null (unless already changed)
            if ($matiere->getIntervenant() === $this) {
                $matiere->setIntervenant(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
