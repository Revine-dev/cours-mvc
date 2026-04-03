<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Agent;

use App\Entity\Agent\Agent;
use App\Entity\Agent\AgentRepository;
use App\Entity\Agent\AgentNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DoctrineAgentRepository implements AgentRepository
{
    private EntityRepository $repository;

    public function __construct(private EntityManager $em)
    {
        $this->repository = $em->getRepository(Agent::class);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findAgentOfId(int $id): Agent
    {
        /** @var Agent|null $agent */
        $agent = $this->repository->find($id);

        if (!$agent) {
            throw new AgentNotFoundException();
        }

        return $agent;
    }

    public function save(Agent $agent): void
    {
        $this->em->persist($agent);
        $this->em->flush();
    }

    public function delete(Agent $agent): void
    {
        $this->em->remove($agent);
        $this->em->flush();
    }
}
