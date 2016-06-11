<?php

namespace App\Events;

use App\User;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class RegistrationEvent extends Event {
  use SerializesModels;

  public $user;

  public function __construct(User $user) {
    $this->user = $user;
  }
}
