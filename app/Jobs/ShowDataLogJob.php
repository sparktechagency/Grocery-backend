<?php

namespace App\Jobs;

use App\Models\Location;
use App\Models\Term;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ShowDataLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
//        $locations = Location::select('locationId')->get();
//        $terms = Term::select('id')->get();
//
//        foreach ($locations as $location) {
//            foreach ($terms as $term) {
//                FetchKrogerProductsJob::dispatch(
//                    $location->locationId,
//                    $term->id
//                )->onQueue('kroger');
//            }
//        }
        $locations = Location::select('locationId')->get();
        $terms = Term::select('id')->get();

        $totalJobs = 0;

        Log::info('Kroger Dispatch Started', [
            'total_locations' => $locations->count(),
            'total_terms' => $terms->count(),
        ]);

        foreach ($locations as $location) {

            Log::info('Processing Location', [
                'locationId' => $location->locationId
            ]);

            foreach ($terms as $term) {

                FetchKrogerProductsJob::dispatch(
                    $location->locationId,
                    $term->id
                )->onQueue('kroger');

                $totalJobs++;

                Log::info('Job Dispatched', [
                    'locationId' => $location->locationId,
                    'termId' => $term->id,
                    'current_total_dispatched' => $totalJobs
                ]);
            }
        }

        Log::info('Kroger Dispatch Completed', [
            'total_jobs_dispatched' => $totalJobs
        ]);
    }
}
