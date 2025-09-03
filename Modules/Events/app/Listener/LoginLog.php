<?php
namespace Modules\Events\Listener;

class LoginLog
{
    public function handle()
    {
        \Log::info("Welcome email sent");
    }
}