<?php

namespace App\Interfaces;

use App\Models\Condition;

interface ConditionRepositoryInterface
{
    public function deleteCondition(Condition $condition);
}
