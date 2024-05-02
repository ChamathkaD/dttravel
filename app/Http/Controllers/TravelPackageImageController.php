<?php

namespace App\Http\Controllers;

use App\Interfaces\TravelPackageImageRepositoryInterface;

class TravelPackageImageController extends Controller
{
    private TravelPackageImageRepositoryInterface $travelPackageImageRepository;

    public function __construct(TravelPackageImageRepositoryInterface $travelPackageImageRepository)
    {
        $this->travelPackageImageRepository = $travelPackageImageRepository;
    }

    public function __invoke(string $id)
    {
        $this->travelPackageImageRepository->deleteTravelPackages($id);
    }
}
