<?php

declare(strict_types=1);

namespace App\Entity\Agent;

interface AgentRepository
{
    /**
     * @return Agent[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Agent
     * @throws AgentNotFoundException
     */
    public function findAgentOfId(int $id): Agent;

    public function save(Agent $agent): void;

    public function delete(Agent $agent): void;
}
