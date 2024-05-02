<?php

namespace App\Http\Controllers;

use App\Interfaces\DashboardRepositoryInterface;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    private DashboardRepositoryInterface $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'bookingCount' => $this->dashboardRepository->getTotalBookingCount(),
            'travelersCount' => $this->dashboardRepository->getTotalTravelersCount(),
            'popularPackages' => $this->dashboardRepository->getPopularPackages(),
            'totalSales' => $this->dashboardRepository->calculateTotalSales(),
            'totalRevenue' => $this->dashboardRepository->calculateTotalRevenue(),
            'salesData' => $this->dashboardRepository->getSalesDataInLastYear(),
            'revenueData' => $this->dashboardRepository->getRevenueDataInLastYear(),
            'totalBookingCount' => $this->dashboardRepository->getAllTotalBookingCount(),
            'totalInProgressBookingCount' => $this->dashboardRepository->getTotalInProgressBookingCount(),
            'totalCancelBookingCount' => $this->dashboardRepository->getTotalCancelBookingCount(),
            'totalCompletedBookingCount' => $this->dashboardRepository->getTotalCompletedBookingCount(),
        ]);
    }
}
