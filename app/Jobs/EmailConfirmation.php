<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Services\AuthService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Mail;
use App\User;

class EmailConfirmation extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $info = AuthService::createConfirmationInfo($this->user);
        $user = $this->user;
        Mail::send(
            'emails.confirmation', 
            ['token' => $info['token'], 'user' => $user],
            function($m) use ($user) {
                $m->to($user->email, $user->name)->subject(
                    trans('auth.emailConfirmationSubject')
                );
            }
        );
    }
}
