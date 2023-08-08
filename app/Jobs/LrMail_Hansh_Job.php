<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\LrMail_Hansh;
use Illuminate\Support\Facades\Mail;

class LrMail_Hansh_Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data2 = $this->data;
         Mail::send('hanshmail', $data2, function($message) use ($data2) {
         			$message->to($data2['email'])->subject('REGARDING LR DETAILS - '.$data2['shipment_no']);
         			$message->attach( public_path('/pdf').'/'.$data2['shipment_no'].'.pdf');
      			});
    }
}
