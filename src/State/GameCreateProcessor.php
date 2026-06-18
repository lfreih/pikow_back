<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Game;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class GameCreateProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private Security $security
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof Game) {
            // 1. On récupère l'utilisateur connecté via le JWT Token
            $user = $this->security->getUser();

            if (!$user) {
                throw new AccessDeniedHttpException("Vous devez être authentifié pour créer une partie.");
            }

            $data->setUser($user);

            // 2. Sécurité anti-NULL : On force la date à "maintenant" si elle est absente
            if ($data->getDate() === null) {
                $data->setDate(new \DateTime());
            }

            // 3. Sécurité anti-NULL : On force le statut initial
            if ($data->getStatus() === null) {
                $data->setStatus('in_progress');
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
