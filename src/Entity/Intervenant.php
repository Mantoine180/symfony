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

    #[ORM\OneToMany(mappedBy: 'intervenant', targetEntity: Matiere::class,cascade: ['persist', 'remove'])]
    #[Ignore]
    private Collection $matieres;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->matieres = new ArrayCollection();
    }

    public function getFullName(): string
    {
        return $this->user->getFirstname() . ' ' . $this->user->getLastname();
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
