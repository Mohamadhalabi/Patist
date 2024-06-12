<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddReminderForNextYearJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $renewal;
    public $workflow;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($renewal, $workflow)
    {
        $this->renewal = $renewal;
        $this->workflow = $workflow;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->workflow->start($this->renewal);
    }
}
