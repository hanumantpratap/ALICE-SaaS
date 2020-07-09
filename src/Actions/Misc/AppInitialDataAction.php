<?php
declare(strict_types=1);

namespace App\Actions\Misc;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Building\BuildingRepository;
use App\Domain\Visit\VisitorTypeRepository;
use App\Domain\Visit\VisitReasonRepository;

class AppInitialDataAction extends Action
{
    public function __construct(LoggerInterface $logger, BuildingRepository $buildingRepository, VisitorTypeRepository $visitorTypeRepository, VisitReasonRepository $visitReasonRepository)
    {
        $this->buildingRepository = $buildingRepository;
        $this->visitorTypeRepository = $visitorTypeRepository;
        $this->visitReasonRepository = $visitReasonRepository;
        parent::__construct($logger);
    }

    protected function action(): Response
    {
        // get user's building
        $building = $this->buildingRepository->findBuildingOfId((int) $this->token->building);

        // get visit types/reasons
        $visitorTypes = $this->visitorTypeRepository->findAll();
        $visitReasons = $this->visitReasonRepository->findAll();

        return $this->respondWithData(['building' => $building, 'visitorTypes' => $visitorTypes, 'visitReasons' => $visitReasons]);
    }
    /**
     * @OA\Get(
     *     path="/app-init",
     *     tags={"misc"},
     *      @OA\Response(
     *         response=200,
     *         description="Return Initial State for App",
     *     )
     * )
     */
}