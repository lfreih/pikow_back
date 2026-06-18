<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Element;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ElementRandomProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $theme = $context['filters']['theme'] ?? null;
        $age = $context['filters']['age'] ?? null;

        if (!$theme || !$age) {
            throw new BadRequestHttpException("Les paramètres 'theme' et 'age' sont requis.");
        }

        // Requête SQL native ultra simple et rapide
        $sql = "SELECT * FROM element e
                WHERE e.theme = :theme
                AND e.age_min <= :age
                AND (e.age_max >= :age OR e.age_max IS NULL)
                ORDER BY RAND()
                LIMIT 2";

        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->entityManager);
        // On dit à Doctrine de mapper les résultats SQL directement en objets 'Element'
        $rsm->addRootEntityFromClassMetadata(Element::class, 'e');

        $query = $this->entityManager->createNativeQuery($sql, $rsm);
        $query->setParameter('theme', $theme);
        $query->setParameter('age', (int)$age);

        return $query->getResult();
    }
}
