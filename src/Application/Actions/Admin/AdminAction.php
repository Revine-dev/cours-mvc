<?php

declare(strict_types=1);

namespace App\Application\Actions\Admin;

use App\Application\Actions\Action;
use App\Application\Response\Response;
use App\Entity\Property\PropertyRepository;
use App\Entity\User\UserRepository;

use App\Application\Helpers\Helper;
use Psr\Log\LoggerInterface;

use Psr\Http\Message\ServerRequestInterface as Request;

class AdminAction extends Action
{
    private PropertyRepository $propertyRepository;
    private UserRepository $usersRepository;

    private array $models = [];

    public function __construct(
        LoggerInterface $logger,
        Helper $helper,
        PropertyRepository $propertyRepository,
        UserRepository $usersRepository
    ) {
        parent::__construct($logger, $helper);
        $this->propertyRepository = $propertyRepository;
        $this->usersRepository = $usersRepository;
        $this->models["property"] = $propertyRepository;
        $this->models["users"] = $usersRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        return $this->render("admin/dashboard");
    }

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

    public function create(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        $ad = new \App\Entity\Property\Property([
            'id' => 0,
            'title' => '',
            'description' => '',
            'price' => 0,
            'status' => 'draft',
            'location' => ['address' => '', 'city' => '', 'postal_code' => ''],
            'features' => ['area' => 0, 'bedrooms' => 0, 'year_built' => date('Y')]
        ]);

        return $this->render("admin/edit", ["ad" => $ad, "isNew" => true]);
    }

    public function store(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        // Simulation of creation
        return $this->redirect("ads");
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        $id = (int) $this->resolveArg('id');
        $ad = $this->propertyRepository->findPropertyOfId($id);

        return $this->render("admin/edit", ["ad" => $ad, "isNew" => false]);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        // Simulation of update
        return $this->redirect("ads");
    }

    public function preview(Request $request, Response $response, array $args): Response
    {
        $this->init($request, $response, $args);
        $id = (int) $this->resolveArg('id');
        $ad = $this->propertyRepository->findPropertyOfId($id);

        return $this->render("admin/preview", ["ad" => $ad]);
    }
}
