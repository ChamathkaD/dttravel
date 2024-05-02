<?php

namespace App\Interfaces;

interface DashboardRepositoryInterface
{
    public function getTotalBookingCount();

    public function getAllTotalBookingCount();

    public function getTotalInProgressBookingCount();

    public function getTotalCancelBookingCount();

    public function getTotalCompletedBookingCount();

    public function getTotalTravelersCount();

    public function getPopularPackages();

    public function calculateTotalSales();

    public function calculateTotalRevenue();

    public function getSalesDataInLastYear();

    public function getRevenueDataInLastYear();
}
