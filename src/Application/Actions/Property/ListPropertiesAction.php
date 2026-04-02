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

        $query = $this->propertyRepository;

        // Filter by type
        if (!empty($queryParams['type'] ?? null)) {
            $query->where('type', $queryParams['type']);
        }

        // Filter by city (case-insensitive)
        if (!empty($queryParams['city'] ?? null)) {
            $query->whereLike('l.city', $queryParams['city']);
        }

        // Filter by minimum price
        if (!empty($queryParams['min_price'] ?? null)) {
            $query->whereGreaterThanOrEqual('price', (int)$queryParams['min_price']);
        }

        // Filter by maximum price
        if (!empty($queryParams['max_price'] ?? null)) {
            $query->whereLessThanOrEqual('price', (int)$queryParams['max_price']);
        }

        $properties = $query->latest()->get();

        return $this->render("properties", [
            'properties' => $properties,
            'filters' => $queryParams
        ]);
    }
}
