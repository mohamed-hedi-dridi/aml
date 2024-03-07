<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use App\Models\AgentReclamation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class AgentController extends Controller
{
    public function index (){
        try {
            $agents = Agent::all();
            $titre = "Liste des Agents";
            Log::channel('info')->info("Acces Liste Des Agents", ['user' => Auth::user()->name] );
            return view("admin.agents.index",compact('titre','agents'));
        } catch (\Exception  $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function store (Request $request){
        try {
            if(!$this->isExist($request->email)){
                $agent = new Agent();
                $agent->name = $request->name;
                $agent->email = $request->email;
                $agent->tel = $request->tel;
                $agent->state_id = $request->state_id;
                $agent->statut = 1;
                $agent->activity = "";
                $agent->wu = $request->wu;
                if($agent->save()){
                    $agentRec = new AgentReclamation();
                    $agentRec->nom = $request->name;
                    $agentRec->email = $request->email;
                    $agentRec->tel = $request->tel;
                    $agentRec->id_state = $request->state_id;
                    $agentRec->activity = "";
                    $agentRec->id_user = 10;
                    $agentRec->western = $request->wu;
                    $agentRec->save();
                }
            }else{
                return redirect()->back()->with( ['status' => 'error' , 'message' => 'Agent déja exist!'] );
            }
            Log::channel('info')->info("Ajouter Agent :".$request->name, ['user' => Auth::user()->name] );
            return redirect()->back()->with( ['status' => 'success' , 'message' => 'Agent ajouter avec success!'] );
        } catch (\Exception  $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function edit(Request $request){

    }

    public function updateKYC(Request $request) {
        //return ($request->email);
        try {
            $email = $request->email;
            $wu = 1;
            $agent = Agent::where('email', $email)->first();
            //return response()->json(['status' => 'success' , 'message' => $request->all()] );

            if($agent->wu == 1){
                $wu = 0 ;
            }

            $agentRec = AgentReclamation::where('email', $email)->first();
            $agentRec->western = $wu;
            $agentRec->save();
            $agent->wu = $wu;
            $agent->save();
            Log::channel('info')->info("Agent updated KYC acces", ['user' => Auth::user()->name] );
            return response()->json(['status' => 'success' , 'message' => 'Agent ajouter avec success!'] );
        } catch (\Exception $e) {
            return response()->json(['status' => 'success' , 'message' => $e->getMessage()]);
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    private function isExist($email){
        $agent = Agent::where('email', $email)->first();
        if ($agent != null) {
            return true;
        }
        return false ;
    }

    public function isExistAgent($email){
        $agent = Agent::where('email', $email)->first();
        if ($agent != null) {
            return response()->json($response["response"] = true);
        }
        return response()->json($response["response"] = false) ;
    }
}

