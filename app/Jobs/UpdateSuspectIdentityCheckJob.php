<?php

namespace App\Jobs;

use App\Models\Setting;
use App\Models\IdentityCheck;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UpdateSuspectIdentityCheckJob implements ShouldQueue
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
        Log::channel('info')->error("In Liste IdentityCheck Updated");
        try {
            $idIdentityCheck = Setting::find(1);
            $identityChecks = IdentityCheck::where('id', '>', $idIdentityCheck->IdentityCheck)->orderBy('id','desc')->get();

            if(COUNT($identityChecks)>0){
                foreach ($identityChecks as $identityCheck) {
                    $identityCheck->storeSuspect();
                }
                $idIdentityCheck->IdentityCheck = $identityChecks[0]->id ;
                $idIdentityCheck->save();
            }
            Log::channel('info')->error("Liste IdentityCheck Updated");
        } catch (\Throwable $th) {
            Log::channel('error')->error($th->getMessage(),['line' => $th->getLine()]);
        }
    }
}
