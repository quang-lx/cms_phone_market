<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Mon\Entities\User;

class UpdateUserPoint implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user_id;
    public $point;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $point)
    {
        $this->user_id = $user_id;
        $this->point = $point;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::find($this->user_id);
        if ($user) {
        	$user->rank_point += $this->point;
        	$user->save();
		}
    }
}
