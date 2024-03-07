<?php

namespace App\Http\Controllers;

use App\Models\Ctaf;
use App\Imports\ImportCTAF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class CtafController extends Controller
{
    public function index(){
        try {
            $titre = "CTAF";
            $liste = Ctaf::where('old',0)->get();
            return view("admin.ctaf.index",compact('titre','liste'));
            Log::channel('info')->info("Acces Liste CTAF", ['user' => Auth::user()->name] );
        } catch (\Throwable $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );

        }

    }

    public function import(Request $request){
        try {
            $validator = $request->validate([
                'file' => ['required',"mimes:xlsx,xls"],
            ]);
            $file = $request->file;
            $old = Ctaf::count();
            $import = Excel::import(new ImportCTAF, $file);
            $new = Ctaf::count();
            if($old == $new){
                return redirect()->route('admin.CTAF.index')->with(['status' => 'error' , 'message' => 'Veuillez vérifier les colonnes du fichier importé. !']);
            }
            Log::channel('info')->info("Importé new CTAF Liste", ['user' => Auth::user()->name] );
            return redirect()->route('admin.CTAF.index')->with(['status' => 'success' , 'message' => 'Fichier importer avec success!']);
        } catch (\Throwable $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function old(){
        try {
            $titre = "Ancien CTAF";
            $liste = Ctaf::where('old',1)->get();
            return view("admin.ctaf.index",compact('titre','liste'));
            Log::channel('info')->info("Acces Liste Old CTAF", ['user' => Auth::user()->name] );
        } catch (\Throwable $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );

        }

    }
}
