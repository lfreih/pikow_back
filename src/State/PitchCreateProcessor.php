<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Element;
use App\Entity\Game;
use App\Entity\Pitch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PitchCreateProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private EntityManagerInterface $entityManager,
        private RequestStack $requestStack // 👈 On injecte la pile de requêtes Symfony
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof Pitch) {
            // 1. On récupère le contenu JSON brut de la requête courante
            $request = $this->requestStack->getCurrentRequest();
            if (!$request) {
                throw new BadRequestHttpException("Requête introuvable.");
            }

            $body = json_decode($request->getContent(), true);

            // 2. Extraction sécurisée des IDs du JSON
            $gameId  = $body['gameId'] ?? null;
            $word1Id = $body['word1Id'] ?? null;
            $word2Id = $body['word2Id'] ?? null;

            // 3. Validation et liaison de la partie (Game)
            if (!$gameId) {
                throw new BadRequestHttpException("Le champ 'gameId' est requis.");
            }

            $game = $this->entityManager->getRepository(Game::class)->find($gameId);
            if (!$game) {
                throw new NotFoundHttpException("Partie introuvable pour l'ID : " . $gameId);
            }
            $data->setGame($game);

            // 4. Validation et liaison du mot 1 (Element)
            if (!$word1Id) {
                throw new BadRequestHttpException("Le champ 'word1Id' est requis.");
            }
            $word1 = $this->entityManager->getRepository(Element::class)->find($word1Id);
            if (!$word1) {
                throw new NotFoundHttpException("Élément (word1) introuvable pour l'ID : " . $word1Id);
            }
            $data->setWord1($word1);

            // 5. Validation et liaison du mot 2 (Element)
            if (!$word2Id) {
                throw new BadRequestHttpException("Le champ 'word2Id' est requis.");
            }
            $word2 = $this->entityManager->getRepository(Element::class)->find($word2Id);
            if (!$word2) {
                throw new NotFoundHttpException("Élément (word2) introuvable pour l'ID : " . $word2Id);
            }
            $data->setWord2($word2);
        }

        // 6. Enregistrement final en BDD
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
