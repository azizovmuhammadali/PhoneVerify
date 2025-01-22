<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class UsersNotVerifiedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:users-not-verified-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereNull('email_verified_at')
        ->whereNull('phone_verified_at')
        ->where('created_at', '<=', Carbon::now()->subDays(3))
        ->get();
foreach ($users as $user) {
$user->delete();
$this->info("User with ID {$user->id} has been deleted.");
}
    }
}
