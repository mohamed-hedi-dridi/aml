<?php

namespace App\Http\Controllers;

use App\Models\Blacklist;
use Illuminate\Http\Request;
use App\Imports\ImportBlackliste;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class BlacklistController extends Controller
{
    public function index()
    {
        try {
            $titre = "Liste Nationale des sanctions";
            $liste = Blacklist::get()->unique('CIN');
            Log::channel('info')->info("Acces BlackListe", ['user' => Auth::user()->name] );
            return view("admin.blacklist.index",compact('titre','liste'));
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }

    }
    public function import(Request $request){
        try {
            $validator = $request->validate([
                'file' => ['required',"mimes:xlsx,xls"],
            ]);
            $old = Blacklist::count();
            $file = $request->file;
            Excel::import(new ImportBlackliste, $file);
            $new = Blacklist::count();
            if($old == $new){
                return redirect()->back()->with(['status' => 'error' , 'message' => 'Veuillez vérifier les colonnes du fichier importé. !']);
            }
            Log::channel('info')->info("Importé new BlackListe", ['user' => Auth::user()->name] );
            return redirect()->back()->with(['status' => 'success' , 'message' => 'Fichier importer avec success!']);
        } catch (\Throwable $e) {
            //dd($e);
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function old(){
        try {
            $titre = "Ancienne Liste Nationale des sanctions";
            $liste = Blacklist::where('old',1)->get();
            Log::channel('info')->info("Acces Old BlackListe", ['user' => Auth::user()->name] );
            return view("admin.blacklist.index",compact('titre','liste'));
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }
}
