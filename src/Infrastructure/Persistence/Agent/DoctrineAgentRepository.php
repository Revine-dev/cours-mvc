<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Agent;

use App\Entity\Agent\Agent;
use App\Entity\Agent\AgentRepository;
use App\Entity\Agent\AgentNotFoundException;
use Doctrine\ORM\EntityRepository;

class DoctrineAgentRepository extends EntityRepository implements AgentRepository
{
    public function findAgentOfId(int $id): Agent
    {
        /** @var Agent|null $agent */
        $agent = $this->find($id);

        if (!$agent) {
            throw new AgentNotFoundException();
        }

        return $agent;
    }

    public function save(Agent $agent): void
    {
        $this->getEntityManager()->persist($agent);
        $this->getEntityManager()->flush();
    }

    public function delete(Agent $agent): void
    {
        $this->getEntityManager()->remove($agent);
        $this->getEntityManager()->flush();
    }
}
