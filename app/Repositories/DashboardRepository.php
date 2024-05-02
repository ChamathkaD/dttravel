<?php

namespace App\Repositories;

use App\Interfaces\DashboardRepositoryInterface;
use App\Models\Booking;
use App\Models\TravelPackage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class DashboardRepository implements DashboardRepositoryInterface
{
    /**
     * Get the total count of bookings created within the last 30 days.
     *
     * @return int The total count of bookings within the specified time frame.
     */
    final public function getTotalBookingCount(): int
    {
        if (auth()->user()->hasRole('Travel Agent')) {
            return Booking::where('created_at', '>=', Carbon::now()->subDays(30))
                ->where('agent_id', auth()->id())
                ->count();
        }

        return Booking::where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();
    }

    /**
     * Get the total count of active travelers created within the last 30 days.
     *
     * @return int The total count of active travelers within the specified time frame.
     */
    final public function getTotalTravelersCount(): int
    {
        if (auth()->user()->hasRole('Travel Agent')) {
            return User::role('Traveler')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->where('status', 'Active')
                ->where('agent_id', auth()->id())
                ->count();
        }

        return User::role('Traveler')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->where('status', 'Active')
            ->count();
    }

    /**
     * Get the latest popular travel packages.
     *
     * @return Collection|TravelPackage[] The collection of the latest popular travel packages.
     */
    final public function getPopularPackages(): Collection|array
    {
        $packages = TravelPackage::latest()
            ->take(5)
            ->get();

        return $packages->each(function ($package) {
            $package->frontend_url = 'https://www.dttravelssrilanka.com/packages/' . $package->id;
        });
    }

    /**
     * Calculate the total sales amount for completed bookings within the last 30 days.
     *
     * @return string The formatted total sales amount as a string with two decimal places.
     */
    final public function calculateTotalSales(): string
    {
        if (auth()->user()->hasRole('Travel Agent')) {
            $bookings = Booking::where('created_at', '>=', Carbon::now()->subDays(30))
                ->where('status', 'Completed')
                ->where('agent_id', auth()->id())
                ->sum('total_price');

        } else {
            $bookings = Booking::where('created_at', '>=', Carbon::now()->subDays(30))
                ->where('status', 'Completed')
                ->sum('total_price');

        }

        return number_format($bookings, 2);
    }

    final public function calculateTotalRevenue(): string
    {
        if (auth()->user()->hasRole('Travel Agent')) {
            $sumOfTotalPrice = Booking::where('created_at', '>=', Carbon::now()->subDays(30))
                ->where('status', 'Completed')
                ->where('agent_id', auth()->id())
                ->sum('total_price');

            $sumOfTotalTax = Booking::where('created_at', '>=', Carbon::now()->subDays(30))
                ->where('status', 'Completed')
                ->where('agent_id', auth()->id())
                ->sum('total_tax');

            $sumOfTotalDiscount = Booking::where('created_at', '>=', Carbon::now()->subDays(30))
                ->where('status', 'Completed')
                ->where('agent_id', auth()->id())
                ->sum('total_discount');
        } else {
            $sumOfTotalPrice = Booking::where('created_at', '>=', Carbon::now()->subDays(30))
                ->where('status', 'Completed')
                ->sum('total_price');

            $sumOfTotalTax = Booking::where('created_at', '>=', Carbon::now()->subDays(30))
                ->where('status', 'Completed')
                ->sum('total_tax');

            $sumOfTotalDiscount = Booking::where('created_at', '>=', Carbon::now()->subDays(30))
                ->where('status', 'Completed')
                ->sum('total_discount');
        }

        return number_format($sumOfTotalPrice - ($sumOfTotalTax + $sumOfTotalDiscount), 2);
    }

    /**
     * Retrieve sales data for completed bookings in the last year, grouped by month.
     *
     * @return \Illuminate\Support\Collection An associative array where keys are short month names and values are total sales for each month.
     */
    public function getSalesDataInLastYear(): \Illuminate\Support\Collection
    {
        $allMonths = collect([
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
        ]);

        if (auth()->user()->hasRole('Travel Agent')) {
            $data = Booking::where('status', 'Completed')
                ->where('agent_id', auth()->id())
                ->where('created_at', '>=', Carbon::now()->subMonths(12))
                ->selectRaw('DATE_FORMAT(created_at, "%b") as month_name, SUM(total_price) as sales')
                ->groupBy('month_name')
                ->orderByRaw('FIELD(month_name, "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec")')
                ->pluck('sales', 'month_name');

        } else {
            $data = Booking::where('status', 'Completed')
                ->where('created_at', '>=', Carbon::now()->subMonths(12))
                ->selectRaw('DATE_FORMAT(created_at, "%b") as month_name, SUM(total_price) as sales')
                ->groupBy('month_name')
                ->orderByRaw('FIELD(month_name, "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec")')
                ->pluck('sales', 'month_name');

        }

        return $allMonths->map(function ($month) use ($data) {
            return [$month => $data->get($month, 0)];
        })->collapse();
    }

    public function getRevenueDataInLastYear(): \Illuminate\Support\Collection
    {
        $allMonths = collect([
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
        ]);

        if (auth()->user()->hasRole('Travel Agent')) {
            $data = Booking::where('status', 'Completed')
                ->where('created_at', '>=', Carbon::now()->subMonths(12))
                ->where('agent_id', auth()->id())
                ->selectRaw('DATE_FORMAT(created_at, "%b") as month_name, SUM(total_price) as sales')
                ->groupBy('month_name')
                ->orderByRaw('FIELD(month_name, "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec")')
                ->pluck('sales', 'month_name');
        } else {
            $data = Booking::where('status', 'Completed')
                ->where('created_at', '>=', Carbon::now()->subMonths(12))
                ->selectRaw('DATE_FORMAT(created_at, "%b") as month_name, SUM(total_price) as sales')
                ->groupBy('month_name')
                ->orderByRaw('FIELD(month_name, "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec")')
                ->pluck('sales', 'month_name');
        }

        return $allMonths->map(function ($month) use ($data) {
            return [$month => $data->get($month, 0)];
        })->collapse();
    }

    /**
     * Get the total count of bookings that are in progress.
     */
    public function getTotalInProgressBookingCount(): int
    {
        if (auth()->user()->hasRole('Travel Agent')) {
            return Booking::where('agent_id', auth()->id())
                ->where('status', Booking::STATUS_IN_PROGRESS)
                ->count();
        }
        return Booking::where('status', Booking::STATUS_IN_PROGRESS)
            ->count();
    }

    /**
     * Get the total count of canceled bookings.
     */
    public function getTotalCancelBookingCount(): int
    {
        if (auth()->user()->hasRole('Travel Agent')) {
            return Booking::where('agent_id', auth()->id())
                ->where('status', Booking::STATUS_CANCELED)
                ->count();
        }
        return Booking::where('status', Booking::STATUS_CANCELED)
            ->count();
    }

    /**
     * Get the total count of completed bookings.
     */
    public function getTotalCompletedBookingCount(): int
    {
        if (auth()->user()->hasRole('Travel Agent')) {
            return Booking::where('agent_id', auth()->id())
                ->where('status', Booking::STATUS_COMPLETED)
                ->count();
        }
        return Booking::where('status', Booking::STATUS_COMPLETED)
            ->count();
    }

    /**
     * Retrieves the total count of all bookings.
     *
     * @return int The total count of all bookings.
     */
    public function getAllTotalBookingCount(): int
    {
        if (auth()->user()->hasRole('Travel Agent')) {
            return Booking::where('agent_id', auth()->id())->count();
        }
        return Booking::count();
    }
}
