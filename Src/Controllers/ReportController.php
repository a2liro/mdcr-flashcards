<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Services\Report\OverviewService;
use MDCR\core\Request;

class ReportController extends Controller
{


    public function apiOverview(Request $request)
    {
        $overviewService = new OverviewService();
        $overviewData = $overviewService->run();
        return $this->json( ['overview' => $overviewData]);
    }
}
