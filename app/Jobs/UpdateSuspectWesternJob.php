<?php

namespace App\Jobs;

use App\Models\Setting;
use App\Models\Kyc;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UpdateSuspectWesternJob implements ShouldQueue
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
        Log::channel('info')->error("In Liste western Updated");
        try {
            $idIdentityCheck = Setting::find(1);
            $identityChecks = Kyc::where('id', '>', $idIdentityCheck->Western)->orderBy('id','desc')->get();
            if(COUNT($identityChecks)>0){
                foreach ($identityChecks as $identityCheck) {
                    $identityCheck->storeSuspect();
                }
                $idIdentityCheck->Western = $identityChecks[0]->id ;
                $idIdentityCheck->save();
            }
            Log::channel('info')->error("Liste western Updated");
        } catch (\Throwable $th) {
            Log::channel('error')->error($th->getMessage(),['line' => $th->getLine()]);
        }
    }
}
