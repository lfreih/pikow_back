<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\GameRepository;
use Symfony\Bundle\SecurityBundle\Security;

class GameCollectionProvider implements ProviderInterface
{
    public function __construct(
        private GameRepository $gameRepository,
        private Security $security
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $user = $this->security->getUser();
        return $this->gameRepository->findBy(
            ['user' => $user],
            ['date' => 'DESC']
        );
    }
}
