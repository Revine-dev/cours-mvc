<?php

declare(strict_types=1);

namespace App\Application\Actions\Property;

use App\Application\Actions\Action;
use App\Application\Response\Response;
use App\Entity\Property\PropertyRepository;
use App\Application\Helpers\Helper;
use Psr\Log\LoggerInterface;

class ListPropertiesAction extends Action
{
    private PropertyRepository $propertyRepository;

    public function __construct(LoggerInterface $logger, Helper $helper, PropertyRepository $propertyRepository)
    {
        parent::__construct($logger, $helper);
        $this->propertyRepository = $propertyRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $queryParams = $this->request->getQueryParams();

        $page = (int)($queryParams['page'] ?? 1);
        $perPage = 10;

        $filters = $queryParams;
        $filters['status_in'] = ['for_sale', 'compromise'];

        $pagination = $this->propertyRepository->search($filters, $page, $perPage);

        return $this->render("properties", [
            'properties' => $pagination['items'],
            'pagination' => $pagination,
            'filters' => $queryParams
        ]);
    }
}
