<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;

class CatalogController extends Controller
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->catalogService->getCatalog());
    }
}
