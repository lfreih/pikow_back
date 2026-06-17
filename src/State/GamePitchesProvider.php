<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GamePitchesProvider implements ProviderInterface
{
    public function __construct(
        private GameRepository $gameRepository,
        private Security $security
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $game = $this->gameRepository->find($uriVariables['id']);

        if (!$game) {
            throw new NotFoundHttpException("Partie introuvable.");
        }

        if ($game->getUser() !== $this->security->getUser()) {
            throw new AccessDeniedHttpException("Accès interdit.");
        }

        // Retourner un tableau brut plutôt que des objets
        return array_map(function($pitch) {
            return [
                'id' => (string) $pitch->getId(),
                'playerName' => $pitch->getPlayerName(),
                'playerAge' => $pitch->getPlayerAge(),
                'turnNumber' => $pitch->getTurnNumber(),
                'duration' => $pitch->getDuration(),
                'score' => $pitch->getScore(),
                'word1' => [
                    'id' => $pitch->getWord1()->getId(),
                    'value' => $pitch->getWord1()->getValue(),
                ],
                'word2' => [
                    'id' => $pitch->getWord2()->getId(),
                    'value' => $pitch->getWord2()->getValue(),
                ],
            ];
        }, $game->getPitches()->toArray());
    }
}
