<?php

namespace App\Http\Controllers;

use App\Models\NationUnis;
use Illuminate\Http\Request;
use App\Models\NationUnisProd;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class NationUnisController extends Controller
{
    public function index(){
        try {
            $titre = "Liste des Nation Unis ";
            $liste = NationUnis::all();
            Log::channel('info')->info("Acces Liste des Nation Unis ", ['user' => Auth::user()->name] );
            return view('admin.nationunis.index',compact('titre', 'liste'));
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }


    public function import(Request $request){
        try {
            $validator = $request->validate([
                'url' => ['required'],
            ]);
            $url = $request->input('url');
            $titre = "Liste des Nation Unis à valider";

            $response = Http::get('http://10.90.90.68:5050/get_UN_DATA',
                                    ['url' => $url]);
            if($response->getStatusCode() != 200){
                return redirect()->route('admin.NationUnis.index')->with( ['status' => 'error' , 'message' => "Opération échoué"] );
            }
            $liste  = json_decode(json_decode($response->body()) , true);

            Log::channel('info')->info("Importé new liste NationUnis", ['user' => Auth::user()->name] );
            return view('admin.nationunis.listeAvalider',compact('url','titre', 'liste'));
        } catch (\Exception  $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function Valider(Request $request) {
        DB::connection('mysql')->beginTransaction();
        DB::connection('DB_Prod')->beginTransaction();
        try {
            $response = Http::get('http://10.90.90.68:5050/get_UN_DATA',
                                    ['url' => $request->url]);
            if($response->getStatusCode() != 200){
                return redirect()->route('admin.NationUnis.index')->with( ['status' => 'error' , 'message' => "Opération échoué"] );
            }
            $liste  = json_decode(json_decode($response->body()) , true);
            NationUnis::truncate();
            NationUnisProd::truncate();
            foreach ($liste as $key => $nation) {
                $nationality = null ;
                if($nation["NATIONALITY"] != "" && $nation["NATIONALITY"] != null ){
                    $nationality = implode(',', $nation["NATIONALITY"]);
                }
                NationUnis::create ([
                    'NAME' => $nation["NAME"],
                    'NAME_ORIGINAL_SCRIPT' => $nation["NAME_ORIGINAL_SCRIPT"],
                    'NATIONALITY' => $nationality,
                    'TYPE_OF_DOCUMENT' => $nation["TYPE_OF_DOCUMENT"],
                    'NUMBER' => $nation["NUMBER"],
                    'COUNTRY_OF_ISSUE' => $nation["COUNTRY_OF_ISSUE"],
                    'TYPE_OF_DATE' => $nation["TYPE_OF_DATE"],
                    'DATE_BIRTH' => $nation["DATE_BIRTH"],
                ]);
                NationUnisProd::create ([
                    'NAME' => $nation["NAME"],
                    'NAME_ORIGINAL_SCRIPT' => $nation["NAME_ORIGINAL_SCRIPT"],
                    'NATIONALITY' => $nationality,
                    'TYPE_OF_DOCUMENT' => $nation["TYPE_OF_DOCUMENT"],
                    'NUMBER' => $nation["NUMBER"],
                    'COUNTRY_OF_ISSUE' => $nation["COUNTRY_OF_ISSUE"],
                    'TYPE_OF_DATE' => $nation["TYPE_OF_DATE"],
                    'DATE_BIRTH' => $nation["DATE_BIRTH"],
                ]);
            }
            //DB::connection('DB_Prod')->commit();
            //DB::connection('mysql')->commit();
            Log::channel('info')->info("Liste Nation Unis Updated ", ['user' => Auth::user()->name]);
            return redirect()->route('admin.NationUnis.index')->with( ['status' => 'success' , 'message' => 'Mis à jour avec succés!'] );
            Log::channel('info')->info("Liste Nation Unis not Updated ", ['user' => Auth::user()->name]);
            return redirect()->route('admin.NationUnis.index')->with( ['status' => 'error' , 'message' => 'Liste Vide!'] );
        } catch (\Exception $e) {
            DB::connection('mysql')->rollBack();
            DB::connection('DB_Prod')->rollBack();
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->route('admin.NationUnis.index')->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }
}
