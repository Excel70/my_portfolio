<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreatePortfolioAdmin extends Command
{
    protected $signature = 'portfolio:create-admin {--name=} {--email=}';
    protected $description = 'Create a private administrator account for the portfolio CMS';

    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Administrator name');
        $email = $this->option('email') ?: $this->ask('Administrator email');
        $password = $this->secret('Administrator password (at least 12 characters)');
        if (mb_strlen($password) < 12) { $this->error('Choose a password of at least 12 characters.'); return self::FAILURE; }
        if (User::where('email', $email)->exists()) { $this->error('An account with that email already exists.'); return self::FAILURE; }
        User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password), 'is_admin' => true]);
        $this->info('Administrator account created.');
        return self::SUCCESS;
    }
}
