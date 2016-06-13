<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Mail;
use DB;
use Carbon\Carbon;
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
        $uuid = uuid_create();
        $user = $this->user;
        DB::table('user_confirmation')->insert([
            'token' => $uuid,
            'email' => $user->email,
            'created_at' => new Carbon(),
        ]);
        Mail::send(
            'emails.confirmation', 
            ['token' => $uuid, 'user' => $user],
            function($m) use ($user) {
                $m->to($user->email, $user->name)->subject(
                    'Email confirmation'
                );
            }
        );
    }
}
