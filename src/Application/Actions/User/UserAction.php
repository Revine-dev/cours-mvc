<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;

use App\Application\Helpers\Helper;

abstract class UserAction extends Action
{
    protected UserRepository $userRepository;

    public function __construct(LoggerInterface $logger, Helper $helper, UserRepository $userRepository)
    {
        parent::__construct($logger, $helper);
        $this->userRepository = $userRepository;
    }
}
