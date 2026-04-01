<?php

declare(strict_types=1);

namespace App\Application\Actions\Property;

use App\Application\Actions\Action;
use App\Application\Response\Response;
use App\Domain\Property\PropertyRepository;
use App\Application\Helpers\Helper;
use Psr\Log\LoggerInterface;

class ViewPropertyAction extends Action
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
        $slug = (string) $this->resolveArg('slug');
        $property = $this->propertyRepository->findPropertyOfSlug($slug);

        return $this->render("property", [
            'property' => $property
        ]);
    }
}
