<?php

namespace App\Console\Commands;

use App\Jobs\RemoveLogJob;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RemoveLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Log Activity in DB';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            dispatch(new RemoveLogJob);
            Log::info('REMOVE-LOG-ACTIVITY COMMAND SUCCESS');
        } catch (Exception $e) {
            Log::info('REMOVE-LOG-ACTIVITY FAILED DETAIL : ' . substr($e->getMessage(), 0, 1000) . ' at ' . $e->getFile() . ' in line ' . $e->getLine());
        }
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        Log::info('REMOVE-LOG-ACTIVITY COMMAND FAILED ' . substr($exception->getMessage(), 0, 1000) . ' at ' . $exception->getFile() . ' in line ' . $exception->getLine());
    }
}
