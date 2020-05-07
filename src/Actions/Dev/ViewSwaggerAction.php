<?php
declare(strict_types=1);

namespace App\Actions\Dev;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use Slim\Views\PhpRenderer;
use OpenAPI;

class ViewSwaggerAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $renderer = new PhpRenderer(APP_ROOT. '/templates');
        
        $openapi = OpenAPI\scan(APP_ROOT.'/src/Actions');
        $yaml = $openapi->toYaml();

        return $renderer->render($this->response, "swagger.phtml", ['yaml' => $yaml]);
    }
}
