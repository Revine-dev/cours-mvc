<?php

declare(strict_types=1);

namespace App\Application\Actions\Property;

use App\Application\Actions\Action;
use App\Application\Response\Response;
use App\Domain\Property\PropertyRepository;
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

        // Filtre par type
        if (!empty($queryParams['type'])) {
            $query->where('type', $queryParams['type']);
        }

        // Filtre par ville (insensible à la casse simplifié)
        if (!empty($queryParams['city'])) {
            $query->filter(fn($item) => stripos($item['location']['city'], $queryParams['city']) !== false);
        }

        // Filtre par prix min
        if (!empty($queryParams['min_price'])) {
            $query->filter(fn($item) => $item['price'] >= (int)$queryParams['min_price']);
        }

        // Filtre par prix max
        if (!empty($queryParams['max_price'])) {
            $query->filter(fn($item) => $item['price'] <= (int)$queryParams['max_price']);
        }

        $properties = $query->latest()->get();

        return $this->render("properties", [
            'properties' => $properties,
            'filters' => $queryParams
        ]);
    }
}
