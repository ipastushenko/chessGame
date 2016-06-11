<?php

namespace App\Listeners;

use App\Events\RegistrationEvent;
use Log;

class EmailConfirmation {
    public function __construct() {
    }

    public function handle(RegistrationEvent $event) {
        Log::info(['user' => $event->user]);
    }
}
