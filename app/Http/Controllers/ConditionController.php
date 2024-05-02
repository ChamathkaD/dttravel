<?php

namespace App\Http\Controllers;

use App\Interfaces\ConditionRepositoryInterface;
use App\Models\Condition;

class ConditionController extends Controller
{
    private ConditionRepositoryInterface $conditionRepository;

    public function __construct(ConditionRepositoryInterface $conditionRepository)
    {
        $this->conditionRepository = $conditionRepository;
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Condition $condition)
    {
        $this->conditionRepository->deleteCondition($condition);
    }
}
