<?php

namespace App\Repositories;

use App\Interfaces\ConditionRepositoryInterface;
use App\Models\Condition;

class ConditionRepository implements ConditionRepositoryInterface
{
    /**
     * Delete a Condition record.
     *
     * This method is responsible for deleting a Condition record from the database.
     *
     * @param  Condition  $condition The Condition instance to be deleted.
     */
    public function deleteCondition(Condition $condition): void
    {
        $condition->delete();

        activity()
            ->withProperties(['condition' => $condition])
            ->log('Deleted Condition');
    }
}
