<?php
declare(strict_types=1);

namespace App\Actions\Dev\Examples;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

class EntityFetchAll extends Action
{
    /**
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $coreDb
     */

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }
       
    protected function action(): Response
    {
        //$VisitRepo = $this->entityManager->getRepository('Visits');
        //$VisitRepo = $this->entityManager->getRepository('Doctrine\\ORM\\Mapping\\Entity\\Visits');
        $VisitRepo = $this->entityManager->getRepository('App\\Entity\\Visits');
        $visits = $VisitRepo->findAll();

        return $this->respondWithData(['visits' => $visits]);
    }
}
