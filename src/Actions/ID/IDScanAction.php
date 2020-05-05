<?php
declare(strict_types=1);

namespace App\Actions\ID;

use Psr\Http\Message\ResponseInterface as Response;
use App\Actions\Action;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use App\Exceptions;

class IDScanAction extends Action
{
    protected function action(): Response
    {
        $this->logger->info("Id Scan");

        $contentType = $this->request->getHeaderLine('Content-Type');

        $this->logger->info("contentType: $contentType");

        if (strstr($contentType, 'application/json')) {
            $formData = $this->getFormData();

            if (isset($formData->imageBase64)) {
                $imageBase64 = ($formData->imageBase64);

                $data = [
                    'recognizerType' => 'MRTD',
                    'imageBase64' => $imageBase64
                ];
            }
            else {
                throw new BadRequestException('Please provide imageBase64 in request.');
            }
        }
        else if (strstr($contentType, 'multipart/form-data')) {
            throw new BadRequestException('Please provide imageBase64 in request.');

            /* $files = $this->request->getUploadedFiles();

            if (isset($files['image'])) {
                $imageFile = $files['image'];
                $imagePath = $imageFile->getClientFilename();

                $data = [
                    'recognizerType' => 'MRTD',
                    'imagePath' => $imagePath
                ];
            }
            else {
                throw new BadRequestException('Please provide a file named "image" in request.');
            } */
        }
        
        $client = new Client(['base_uri' => 'http://a7dbaf1d9856446c5962ea746b898e0c-1101245835.us-east-2.elb.amazonaws.com/']);

        try {
            $response = $client->post('recognize/execute', [
                'json' => $data
            ]);

            $payload = json_decode($response->getBody()->getContents());
            return $this->respondWithData($payload->data->result);
        }
        catch(ClientException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());

            throw new Exceptions\BadRequestException($response->summary);
        }
        catch(ServerException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());
            throw new Exceptions\ServiceUnavailableException($response->error->userMessage);
        }
    }

    /**
     * @OA\Post(
     *     path="/id-scan",
     *     tags={"ids"},
     *     @OA\Response(
     *         response=200,
     *         description="Scan ID",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={"id": 10, "name": "Jessica Smith"}
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             example={"name": "Jessica Smith"}
     *         )
     *     )
     * )
     */
}
