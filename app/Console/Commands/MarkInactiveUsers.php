<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MarkInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:mark-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically identifies and sets user accounts to an inactive status if they haven\'t logged in for more than 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $inactiveUsers = User::where('last_login_at', '<', now()->subDays(30))->get();

        foreach ($inactiveUsers as $user) {
            $user->update(['active' => User::STATUS_INACTIVE]);
        }

        $this->info('Inactive users marked successfully.');
    }
}
