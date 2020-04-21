<?php
declare(strict_types=1);

namespace App\Actions\Dev;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use OpenAPI;

class GenerateOpenAPIDocs extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $openapi = OpenAPI\scan(APP_ROOT.'/src/Actions');
        $this->response->getBody()->write($openapi->toYaml());
        return $this->response->withHeader('Content-Type', 'application/x-yaml');
    }
}
