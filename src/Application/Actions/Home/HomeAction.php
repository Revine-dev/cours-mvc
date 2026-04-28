<?php

declare(strict_types=1);

namespace App\Application\Actions\Home;

use App\Application\Actions\Action;
use App\Application\Response\Response;
use App\Entity\Property\PropertyRepository;
use App\Application\Helpers\Helper;
use App\Entity\User\UserRepository;
use Psr\Log\LoggerInterface;

class HomeAction extends Action
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
        return $this->render("home", ['properties' => $this->propertyRepository->findLatest()]);
    }
}
