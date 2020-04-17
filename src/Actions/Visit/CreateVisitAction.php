<?php
declare(strict_types=1);

namespace App\Actions\Visit;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;

class CreateVisitAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $formData = $this->getFormData();

        return $this->respondWithData($formData);
    }
}
