<?php

namespace App\Twig\Runtime;

use App\Entity\Cours;
use App\Entity\Matiere;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\RuntimeExtensionInterface;

class MatiereExtensionRuntime implements RuntimeExtensionInterface
{
    private EntityManager $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getMinutesByMatiere(Matiere $matiere): int
    {
        $result = $this->entityManager->getRepository(Cours::class)->getMinutesConsumedByMatiere($matiere);
        return $result ? $result['minutes'] : 0;
    }
}
