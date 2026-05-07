<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin;

use App\Application\Actions\Action;
use App\Application\Response\Response;
use App\Entity\Property\Property;
use App\Entity\Property\PropertyRepository;
use App\Entity\Agent\Agent;
use App\Entity\Agent\AgentRepository;
use App\Application\Helpers\Helper;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminAction extends Action
{
    private PropertyRepository $propertyRepository;
    private AgentRepository $agentRepository;
    private \Doctrine\ORM\EntityManager $em;

    /**
     * AdminAction constructor.
     *
     * @param LoggerInterface $logger
     * @param Helper $helper
     * @param PropertyRepository $propertyRepository
     * @param AgentRepository $agentRepository
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(
        LoggerInterface $logger,
        Helper $helper,
        PropertyRepository $propertyRepository,
        AgentRepository $agentRepository,
        \Doctrine\ORM\EntityManager $em
    ) {
        parent::__construct($logger, $helper);
        $this->propertyRepository = $propertyRepository;
        $this->agentRepository = $agentRepository;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    protected function init(Request $request, Response $response, array $args): void
    {
        parent::init($request, $response, $args);

        if (!$this->isAuth() || $this->auth()->role !== \App\Entity\User\UserRole::ADMIN) {
            $this->response->unauthorized("Accès réservé aux administrateurs.");
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $allProperties = $this->propertyRepository->findAll();
        $totalAds = count($allProperties);
        $activeAds = count(array_filter($allProperties, fn($p) => $p->status === 'for_sale'));
        $totalAgents = count($this->agentRepository->findAll());
        $totalValue = array_reduce($allProperties, fn($carry, $p) => $carry + (float) $p->price, 0);

        $latestAds = $this->propertyRepository->findLatest(5);

        // Chart data: properties per month for the last 6 months
        $chartData = [];
        $monthsFr = ['Jan' => 'Jan', 'Feb' => 'Fév', 'Mar' => 'Mar', 'Apr' => 'Avr', 'May' => 'Mai', 'Jun' => 'Juin', 'Jul' => 'Juil', 'Aug' => 'Août', 'Sep' => 'Sept', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Déc'];

        for ($i = 5; $i >= 0; $i--) {
            $date = new \DateTime("-$i months");
            $monthAbbr = $date->format('M');
            $monthName = $monthsFr[$monthAbbr] ?? $monthAbbr;
            $monthNum = $date->format('m');
            $yearNum = $date->format('Y');

            $count = count(array_filter($allProperties, function ($p) use ($monthNum, $yearNum) {
                return $p->createdAt->format('m') === $monthNum && $p->createdAt->format('Y') === $yearNum;
            }));

            $chartData[] = [
                'label' => $monthName,
                'count' => $count,
                'isCurrent' => $i === 0
            ];
        }

        return $this->render("admin/dashboard", [
            "stats" => [
                "totalAds" => $totalAds,
                "activeAds" => $activeAds,
                "totalAgents" => $totalAgents,
                "totalValue" => $totalValue
            ],
            "latestAds" => $latestAds,
            "chartData" => $chartData
        ]);
    }

    /**
     * Lists all properties with pagination.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function list(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        $page = (int) ($request->getQueryParams()['page'] ?? 1);
        $perPage = 10;

        $paginate = $this->propertyRepository->search([], $page, $perPage);

        return $this->render("admin/ads", [
            "ads" => $paginate['items'],
            "pagination" => [
                "current" => $paginate['current_page'],
                "total" => $paginate['last_page'],
                "count" => $paginate['total'],
                "from" => ($paginate['current_page'] - 1) * $perPage + 1,
                "to" => min($paginate['current_page'] * $perPage, $paginate['total'])
            ]
        ]);
    }

    /**
     * Displays the form for creating a new property.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function create(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        $ad = new Property();
        $ad->status = 'draft';
        $agents = $this->agentRepository->findAll();

        return $this->render("admin/edit", ["ad" => $ad, "isNew" => true, "agents" => $agents]);
    }

    /**
     * Stores a newly created property.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function store(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        $data = (array) $request->getParsedBody();

        if (empty($data['title'])) {
            return $this->render("admin/edit", [
                "ad" => new Property(),
                "isNew" => true,
                "agents" => $this->agentRepository->findAll(),
                "error" => "Le titre est obligatoire."
            ]);
        }

        $this->em->wrapInTransaction(function () use ($data) {
            $ad = new Property();
            $ad->fromArray($data);

            if (empty($ad->slug)) {
                $ad->slug = $this->slugify($ad->title);
            }

            if (isset($data['agent_id']) && !empty($data['agent_id'])) {
                $ad->agent = $this->agentRepository->findAgentOfId((int)$data['agent_id']);
            }

            $this->propertyRepository->save($ad);
        });

        return $this->redirect("ads");
    }

    /**
     * Displays the form for editing an existing property.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function edit(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        $id = (int) $this->resolveArg('id');
        $ad = $this->propertyRepository->findPropertyOfId($id);
        $agents = $this->agentRepository->findAll();

        return $this->render("admin/edit", ["ad" => $ad, "isNew" => false, "agents" => $agents]);
    }

    /**
     * Updates an existing property.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        $id = (int) $this->resolveArg('id');
        $data = (array) $request->getParsedBody();

        $this->em->wrapInTransaction(function () use ($id, $data) {
            $ad = $this->propertyRepository->findPropertyOfId($id);
            $this->hydrateProperty($ad, $data);
            $this->propertyRepository->save($ad);
        });

        return $this->redirect("ads");
    }

    /**
     * Hydrates a Property entity from an associative array of data.
     *
     * @param Property $ad
     * @param array $data
     * @return void
     */
    private function hydrateProperty(Property $ad, array $data): void
    {
        $ad->title = $data['title'] ?? $ad->title;
        $ad->description = $data['description'] ?? $ad->description;
        $ad->price = $data['price'] ?? $ad->price;
        $ad->status = $data['status'] ?? $ad->status;
        $ad->type = $data['type'] ?? $ad->type;

        if (isset($data['slug']) && !empty($data['slug'])) {
            $ad->slug = $this->slugify($data['slug']);
        } elseif (isset($data['title'])) {
            $ad->slug = $this->slugify($data['title']);
        }

        $location = $ad->location;
        $location->address = $data['address'] ?? $location->address;
        $location->city = $data['city'] ?? $location->city;
        $location->postal_code = $data['postal_code'] ?? $location->postal_code;
        $location->country = 'France';
        $ad->location = $location;

        if (isset($data['agent_id']) && !empty($data['agent_id'])) {
            try {
                $agent = $this->agentRepository->findAgentOfId((int)$data['agent_id']);
                $ad->agent = $agent;
            } catch (\Exception $e) {
                // Agent not found
            }
        }

        // Handle images (if missing in data, it means they were all removed in the UI)
        $images = isset($data['images']) && is_array($data['images']) ? $data['images'] : [];
        $ad->setImages($images);

        if (isset($data['features'])) {
            $ad->features = $data['features'];
        }

        $ad->updatedAt = new \DateTime();
    }

    /**
     * Lists all agents.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function listAgents(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        $agents = $this->agentRepository->findAll();

        return $this->render("admin/agents", [
            "agents" => $agents
        ]);
    }

    /**
     * Displays the form for creating a new agent.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function createAgent(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        $agent = new Agent();

        return $this->render("admin/edit_agent", ["agent" => $agent, "isNew" => true]);
    }

    /**
     * Stores a newly created agent.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function storeAgent(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        $data = (array) $request->getParsedBody();

        if (empty($data['name'])) {
            return $this->render("admin/edit_agent", [
                "agent" => new Agent(),
                "isNew" => true,
                "error" => "Le nom de l'agent est obligatoire."
            ]);
        }

        $this->em->wrapInTransaction(function () use ($data) {
            $agent = new Agent();
            $agent->fromArray($data);
            $this->agentRepository->save($agent);
        });

        return $this->redirect("agents");
    }

    /**
     * Displays the form for editing an existing agent.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function editAgent(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        $id = (int) $this->resolveArg('id');
        $agent = $this->agentRepository->findAgentOfId($id);

        return $this->render("admin/edit_agent", ["agent" => $agent, "isNew" => false]);
    }

    /**
     * Updates an existing agent.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function updateAgent(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        $id = (int) $this->resolveArg('id');
        $data = (array) $request->getParsedBody();

        $this->em->wrapInTransaction(function () use ($id, $data) {
            $agent = $this->agentRepository->findAgentOfId($id);
            $agent->fromArray($data);
            $this->agentRepository->save($agent);
        });

        return $this->redirect("agents");
    }

    /**
     * Deletes an agent.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function deleteAgent(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        $id = (int) $this->resolveArg('id');
        $agent = $this->agentRepository->findAgentOfId($id);

        $this->agentRepository->delete($agent);

        return $this->redirect("agents");
    }

    /**
     * Previews a property with current form data.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function preview(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        $id = isset($args['id']) ? (int)$args['id'] : null;
        $data = (array) $request->getParsedBody();

        if ($id) {
            $ad = $this->propertyRepository->findPropertyOfId($id);
            // Clone to avoid accidentally modifying the repository instance
            $ad = clone $ad;
        } else {
            $ad = new Property();
        }

        $this->hydrateProperty($ad, $data);

        // Handle images from POST for preview
        if (isset($data['images'])) {
            $ad->images = $data['images'];
        }

        return $this->render("admin/preview", ["ad" => $ad]);
    }
}
