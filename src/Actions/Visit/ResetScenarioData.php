<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;

class ResetScenarioData extends VisitAction
{
    protected function action(): Response
    {
        $demoVisitorTypes = ["demoCleanVisitor", "demoSexOffender", "demoBlackList", "demoSexOffenderBlacklist"];

        foreach ($demoVisitorTypes as $type) {
            try {
            $person = $this->personRepository->findPersonByType($type);
                $this->personRepository->remove($person);
            } catch (\Exception $e) {
            }
        }

        return $this->respondWithData(['message' => 'Scenario Data Reset'], 200);
    }
}

/**
 * @OA\Delete(
 *     path="/visits/scenarioData",
 *     tags={"visits"},
 *     @OA\Response(
 *         response=200,
 *         description="Reset Scenario Data"
 *         )
 *     ),
 * )
 */
