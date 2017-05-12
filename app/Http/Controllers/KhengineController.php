<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Update;
use Illuminate\Support\Facades\Storage;
use App\Cleaning;
use App\KanjiHybrid;
use App\KanjiHybridPhrase;
use App\MorningSun;
use DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class KhengineController extends Controller
{
    public function __construct() {
    
    }
    
    private static function isAuthorized() {
        if (!auth()->check()) {
            return false;
        }
        elseif(auth()->user()->admin || auth()->user()->managedb) {
            return true;
        }
        else {
            return false;
        }
    }
    
    
    public function get() {
        if (KhengineController::isAuthorized()){
            $cleaning = Update::where('type', 'cleaning')->orderBy('created_date', 'desc')->first();
            $kanji_hybrid_phrase = Update::where('type', 'kanji_hybrid_phrase')->orderBy('created_date', 'desc')->first();
            $kanji_hybrid = Update::where('type', 'kanji_hybrid')->orderBy('created_date', 'desc')->first();
            
            return view('databases.index', compact('cleaning', 'kanji_hybrid_phrase', 'kanji_hybrid'));
        }
        else {
            return view('unauthorized');
        }
    }
    
    public function store() {
        //DB::connection()->disableQueryLog();
        if (KhengineController::isAuthorized()){
            // validation
            $this->validate(request(), [
                    'cleaning' => 'required_without_all:kanji_hybrid,kanji_hybrid_phrase',
                    'kanji_hybrid_phrase' => 'required_without_all:kanji_hybrid,cleaning',
                    'kanji_hybrid' => 'required_without_all:kanji_hybrid_phrase,cleaning',
            ]);
            
            $m_errors = [];
            
            if (request('cleaning')) {
                $path = request()->file('cleaning')->storeAs('cleaning', 
                        Carbon::now()->format('Y-m-d_His') . '_' . request()->file('cleaning')->getClientOriginalName(),
                        'local');
                $file_parts = pathinfo($path);
                if ($file_parts['extension'] != "csv") {
                    array_push($m_errors, "Cleaning file must be csv");
                }
                else {
                    // validate columns of file
                    $file_n = Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix($path);
                    storage_path($path);
                    $file = fopen($file_n, "r");
                    $is_size_checked = false;
                    $db_data = array();
                    while ( ($data = fgetcsv($file, 0, ",")) !== FALSE) {
                        if (!$is_size_checked) {
                            $is_size_checked = true;
                            if (sizeof($data) != 2) {
                                array_push($m_errors, "Error uploading cleaning file: 2 column CSV required");
                                break;
                            }
                            else {
                                Cleaning::truncate();
                            }
                        }
                        $new = array(
                                'cleaned' => $data[0],
                                'compound_cleaned' => $data[1]
                        );
                        array_push($db_data, $new);
                    }
                    Cleaning::insert($db_data);
                    fclose($file);
                    
                    $update = new Update;
                    $update->created_by = auth()->user()->id;
                    $update->type = 'cleaning';
                    $update->location = $path;
                    $update->save();
                }
            }
            
            if (request('kanji_hybrid_phrase')) {
                $path = request()->file('kanji_hybrid_phrase')->storeAs('kanji_hybrid_phrase',
                        Carbon::now()->format('Y-m-d_His') . '_' . request()->file('kanji_hybrid_phrase')->getClientOriginalName(),
                        'local');
                $file_parts = pathinfo($path);
                if ($file_parts['extension'] != "csv") {
                    array_push($m_errors, "Phrases file must be csv");
                }
                else {
                    // validate columns of file
                    $file_n = Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix($path);
                    storage_path($path);
                    $file = fopen($file_n, "r");
                    $is_size_checked = false;
                    $db_data = array();
                    $counter = 1;
                    while ( ($data = fgetcsv($file, 0, ",")) !== FALSE) {
                        $counter = $counter + 1;
                        if (!$is_size_checked) {
                            $is_size_checked = true;
                            if (sizeof($data) != 2) {
                                array_push($m_errors, "Error uploading phrases file: 2 column CSV required");
                                break;
                            }
                            else {
                                KanjiHybridPhrase::truncate();
                            }
                        }
                        $new = array(
                                'english' => $data[0],
                                'hybrid' => $data[1]
                        );
                        array_push($db_data, $new);
                        if ($counter % 1000 == 0) {
                            Log::debug($counter);
                            KanjiHybridPhrase::insert($db_data);
                            $db_data = array();
                        }
                    }
                    KanjiHybridPhrase::insert($db_data);
                    fclose($file);
        
                    $update = new Update;
                    $update->created_by = auth()->user()->id;
                    $update->type = 'kanji_hybrid_phrase';
                    $update->location = $path;
                    $update->save();
                }
            }
            
            if (request('kanji_hybrid')) {
                $path = request()->file('kanji_hybrid')->storeAs('kanji_hybrid',
                        Carbon::now()->format('Y-m-d_His') . '_' . request()->file('kanji_hybrid')->getClientOriginalName(),
                        'local');
                $file_parts = pathinfo($path);
                if ($file_parts['extension'] != "csv") {
                    array_push($m_errors, "KanjiHybrid file must be csv");
                }
                else {
                    // validate columns of file
                    $file_n = Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix($path);
                    storage_path($path);
                    $file = fopen($file_n, "r");
                    $is_size_checked = false;
                    $db_data = array();
                    $counter = 1;
                    while ( ($data = fgetcsv($file, 0, ",", '"')) !== FALSE) {
                        $counter = $counter + 1;
                        if (!$is_size_checked) {
                            $is_size_checked = true;
                            if (sizeof($data) != 14) {
                                array_push($m_errors, "Error uploading kanji_hybrid file: 14 column CSV required");
                                break;
                            }
                            else {
                                KanjiHybrid::truncate();
                            }
                        }
                        $new = array(
                                'jlpt' => $data[0],
                                'bias' => $data[1],
                                'chinese' => $data[2],
                                'core_meanings' => $data[3],
                                'english' => $data[4],
                                'grammar' => $data[5],
                                'kanji1' => $data[6],
                                'kunyomi' => $data[7],
                                'onyomi' => $data[8],
                                'radical' => $data[9],
                                'ref_id' => $data[10],
                                'setting' => $data[11],
                                'unicode_id' => $data[12],
                                'hybrid' => $data[13]
                        );
                        array_push($db_data, $new);
                        if ($counter % 1000 == 0) {
                            Log::debug($counter);
                            KanjiHybrid::insert($db_data);
                            $db_data = array();
                        }
                    }
                    KanjiHybrid::insert($db_data);
                    fclose($file);
        
                    $update = new Update;
                    $update->created_by = auth()->user()->id;
                    $update->type = 'kanji_hybrid';
                    $update->location = $path;
                    $update->save();
                }
            }
            
            if (sizeof($m_errors) > 0 ){
                request()->session()->flash('m_errors', $m_errors);
            }
            else {
                request()->session()->flash('success', 'Update successful!');
            }
            return redirect()->back();
        }
        else {
            return view('unauthorized');
        }

    }
    
    public function history() {
        if(KhengineController::isAuthorized()) {
            if (request('q')) {
                $cleaning = Update::where('type', 'cleaning')
                ->whereDate('created_date', '<=', request('q'))->orderBy('created_date', 'desc')->first();
                $kanji_hybrid_phrase = Update::where('type', 'kanji_hybrid_phrase')
                ->whereDate('created_date', '<=', request('q'))->orderBy('created_date', 'desc')->first();
                $kanji_hybrid = Update::where('type', 'kanji_hybrid')
                ->whereDate('created_date', '<=', request('q'))->orderBy('created_date', 'desc')->first();
            }
            else {
                $cleaning = Update::where('type', 'cleaning')->orderBy('created_date', 'desc')->first();
                $kanji_hybrid_phrase = Update::where('type', 'kanji_hybrid_phrase')->orderBy('created_date', 'desc')->first();
                $kanji_hybrid = Update::where('type', 'kanji_hybrid')->orderBy('created_date', 'desc')->first();
            }
            return view('databases.history', compact('cleaning', 'kanji_hybrid_phrase', 'kanji_hybrid'));
        }
        else {
            return view('unauthorized');
        }
    }
    
    public function download(Update $update) {
        if (KhengineController::isAuthorized()) {
            $path = Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix($update->location);
            return response()->download($path, $update->name);
        }
        else {
            return view('unauthorized');
        }
    }
}
