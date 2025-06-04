<?php

namespace App\Jobs;

use App\Models\Log\LogActivity;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RemoveLogJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            DB::beginTransaction();

            $date = date("Y-m-d", strtotime("-1 months"));

            $log_activity = LogActivity::whereDate('created_at', '<=', $date)->get();

            $log_activity->delete();

            DB::commit();

            Log::info('REMOVE-LOG-ACTIVITY JOB SUCCESS');

        } catch (Exception $e) {

            DB::rollBack();
            
            Log::info('REMOVE-LOG-ACTIVITY Job is failed => ' . substr($e->getMessage(), 0, 1000) . ' at ' . $e->getFile() . ' in line' . $e->getLine());
            
        }
    }
}
