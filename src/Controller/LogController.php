<?php
declare(strict_types=1);

namespace App\Controller;

use App\Data\LogCounterRequestData;
use App\Repository\LogRepositoryInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class LogController extends AbstractController
{
    public function __construct(private LogRepositoryInterface $logRepository) {}

    #[Route('/logs/count', name: 'app_log_counter', methods: ['GET'])]
    public function count(Request $request): JsonResponse
    {
        return new JsonResponse([
            'counter' => $this->getTotalLogs($request),
        ]);
    }

    #[Route('/logs/delete', name: 'app_log_deleter', methods: ['DELETE'])]
    public function delete(): Response
    {
        $this->logRepository->deleteAllLogs();

        return new Response(Response::$statusTexts[Response::HTTP_NO_CONTENT], Response::HTTP_NO_CONTENT);
    }

    private function getTotalLogs(Request $request): int
    {
        $data = $request->query->all();

        $requestData = (new LogCounterRequestData())
            ->setServiceNames(isset($data['serviceNames']) ? explode(',', $data['serviceNames']) : null)
            ->setStatusCode(isset($data['statusCode']) ? (int)$data['statusCode'] :  null)
            ->setStartDate(isset($data['startDate']) ? new DateTime((string)$data['startDate']) : null)
            ->setEndDate(isset($data['endDate']) ? new DateTime((string)$data['endDate']) : null);

        return $this->logRepository->countLogsByFilters($requestData);
    }
}
