<?php


use Carbon\Carbon;
use App\Models\Payment;
use App\Models\EasyTransfert;

 function getLastWeekDays(){
    $lastWeek = Carbon::now()->subWeek(); // il y a 7 jours

    $EasyTransfert = EasyTransfert::where('created_at', '>=', $lastWeek)->get();
 }
