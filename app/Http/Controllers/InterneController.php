<?php

namespace App\Http\Controllers;

use App\Models\InternVm;
use Illuminate\Http\Request;
use App\Imports\ImportInterne;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class InterneController extends Controller
{
    public function index(){
        try {
            $titre = "Liste Interne";
            $liste = InternVm::get()->unique('CIN');
            Log::channel('info')->info("Acces Liste Interne", ['user' => Auth::user()->name] );
            return view("admin.internVm.index",compact('titre','liste'));
        } catch (\Exception  $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }

    }

    public function import(Request $request){
        try {
            $validator = $request->validate([
                'file' => ['required',"mimes:xlsx,xls"],
            ]);
            $old = InternVm::count();
            $file = $request->file;
            Excel::import(new ImportInterne, $file);
            $new = InternVm::count();
            if($old == $new){
                return redirect()->back()->with(['status' => 'error' , 'message' => 'Veuillez vérifier la structure du fichier !']);
            }
            Log::channel('info')->info("Importé new InternListe", ['user' => Auth::user()->name] );
            return redirect()->back()->with(['status' => 'success' , 'message' => 'Fichier importé avec success!']);
        } catch (\Exception  $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name, 'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }
    }

    public function old(){
        try {
            $titre = "Ancien Liste Interne";
            $liste = InternVm::where('old',1)->get();
            Log::channel('info')->info("Acces Ancien Liste Interne", ['user' => Auth::user()->name] );
            return view("admin.internVm.index",compact('titre','liste'));
        } catch (\Exception  $e) {
            Log::channel('error')->error($e->getMessage(), ['user' => Auth::user()->name ,'line' => $e->getLine()]);
            return redirect()->back()->with( ['status' => 'error' , 'message' => $e->getMessage()] );
        }

    }
}
