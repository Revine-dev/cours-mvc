<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin;

use App\Application\Actions\Action;
use App\Application\Response\Response;
use App\Entity\Property\Property;
use App\Entity\Property\PropertyRepository;
use App\Entity\User\UserRepository;
use App\Entity\Agent\Agent;
use App\Entity\Agent\AgentRepository;

use App\Application\Helpers\Helper;
use Psr\Log\LoggerInterface;

use Psr\Http\Message\ServerRequestInterface as Request;

class AdminAction extends Action
{
    private PropertyRepository $propertyRepository;
    private UserRepository $usersRepository;
    private AgentRepository $agentRepository;

    private array $models = [];

    /**
     * AdminAction constructor.
     *
     * @param LoggerInterface $logger
     * @param Helper $helper
     * @param PropertyRepository $propertyRepository
     * @param UserRepository $usersRepository
     * @param AgentRepository $agentRepository
     */
    public function __construct(
        LoggerInterface $logger,
        Helper $helper,
        PropertyRepository $propertyRepository,
        UserRepository $usersRepository,
        AgentRepository $agentRepository
    ) {
        parent::__construct($logger, $helper);
        $this->propertyRepository = $propertyRepository;
        $this->usersRepository = $usersRepository;
        $this->agentRepository = $agentRepository;
        $this->models["property"] = $propertyRepository;
        $this->models["users"] = $usersRepository;
        $this->models["agents"] = $agentRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        return $this->render("admin/dashboard");
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
        $perPage = 5;

        $allAds = $this->propertyRepository->findAll();
        $totalAds = count($allAds);
        $totalPages = (int) ceil($totalAds / $perPage);
        $page = max(1, min($page, $totalPages));

        $ads = array_slice($allAds, ($page - 1) * $perPage, $perPage);

        return $this->render("admin/ads", [
            "ads" => $ads,
            "pagination" => [
                "current" => $page,
                "total" => $totalPages,
                "count" => $totalAds,
                "from" => ($page - 1) * $perPage + 1,
                "to" => min($page * $perPage, $totalAds)
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

        $ad = new Property();
        $this->hydrateProperty($ad, $data);

        $this->propertyRepository->save($ad);

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

        $ad = $this->propertyRepository->findPropertyOfId($id);
        $this->hydrateProperty($ad, $data);

        $this->propertyRepository->save($ad);

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
                // Agent not found, keep existing or set to null
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

        $agent = new Agent();
        $this->hydrateAgent($agent, $data);

        $this->agentRepository->save($agent);

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

        $agent = $this->agentRepository->findAgentOfId($id);
        $this->hydrateAgent($agent, $data);

        $this->agentRepository->save($agent);

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
     * Hydrates an Agent entity.
     *
     * @param Agent $agent
     * @param array $data
     * @return void
     */
    private function hydrateAgent(Agent $agent, array $data): void
    {
        if (isset($data['id'])) {
            $agent->id = (int)$data['id'];
        }
        $agent->name = $data['name'] ?? $agent->name;
        $agent->email = $data['email'] ?? $agent->email;
        $agent->phone = $data['phone'] ?? $agent->phone;
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
