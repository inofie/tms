<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\UserRegistration;
use Illuminate\Support\Facades\Mail;

class UserRegistrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data2)
    {
        $this->data2 = $data2;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->data2->notify(new UserRegistration($this->data2));
    }
}
