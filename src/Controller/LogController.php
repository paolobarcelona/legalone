<?php
declare(strict_types=1);

namespace App\Controller;

use App\Data\LogCounterRequestData;
use App\Repository\LogRepositoryInterface;
use DateTime;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class LogController extends AbstractController
{
    public function __construct(private LogRepositoryInterface $logRepository) {}

    #[Route(
        '/logs/count', 
        name: 'app_log_counter', 
        methods: ['GET']
    )]
    #[OA\Response(
        response: 200,
        description: 'Searches logs and provides aggregated count of matches',
        content: new OA\JsonContent(
            type: 'string'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Error with filters',
        content: new OA\JsonContent(
            type: 'string'
        )
    )]    
    #[OA\Parameter(
        name: 'serviceNames',
        in: 'query',
        description: 'Array of service names',
        schema: new OA\Schema(
            type: 'array',
            example: ['USER-SERVICE', 'INVOICE-SERVICE'],
            items: new OA\Items(type: 'string')
        )
    )]
    #[OA\Parameter(
        name: 'statusCode',
        in: 'query',
        description: 'HTTP Status code',
        schema: new OA\Schema(type: 'int')
    )]
    #[OA\Parameter(
        name: 'startDate',
        in: 'query',
        description: 'Filter logs query by start Date',
        schema: new OA\Schema(type: 'string')
    )]       
    #[OA\Parameter(
        name: 'endDate',
        in: 'query',
        description: 'Filter logs query by end Date',
        schema: new OA\Schema(type: 'string')
    )]            
    public function count(Request $request): JsonResponse
    {
        $data = $request->query->all();

        $serviceNames = $data['serviceNames'] ?? [];
        
        if (isset($data['serviceNames']) && !is_array($serviceNames)) {
            // if its not array, possibly comma separated?
            $serviceNames = explode(',', $data['serviceNames']);
        }

        $requestData = (new LogCounterRequestData())
            ->setServiceNames($serviceNames)
            ->setStatusCode(isset($data['statusCode']) ? (int)$data['statusCode'] :  null)
            ->setStartDate(isset($data['startDate']) ? new DateTime((string)$data['startDate']) : null)
            ->setEndDate(isset($data['endDate']) ? new DateTime((string)$data['endDate']) : null);

        if ($requestData->getStartDate() !== null
            && $requestData->getEndDate() !== null 
            && $requestData->getStartDate() > $requestData->getEndDate()
        ) {
            return new JsonResponse([
                'error' => 'The `endDate` cannot be earlier than the `startDate`',
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'counter' => $this->logRepository->countLogsByFilters($requestData),
        ]);
    }

    #[Route('/logs/delete', name: 'app_log_deleter', methods: ['DELETE'])]
    #[OA\Response(
        response: 204,
        description: 'Deletes all saved log records',
        content: new OA\JsonContent()
    )]
    public function delete(): Response
    {
        $this->logRepository->deleteAllLogs();

        return new Response(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
    }
}
