<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cleaning;
use App\KanjiHybrid;
use App\KanjiHybridPhrase;
use App\MorningSun;

class ConversionController extends Controller
{
    const NGRAM_LEN = 3;
    
    public function kanjihybrid() {
        return response()->json([
            'text' => request('text'),
            //'ngrams' => $this->_getNGrams(request('text'),1),
            //'cleaning' => Cleaning::whereIn('cleaned', $this->_getNGrams(request('text'),1))->get(),
            //'kanjihybrid' => KanjiHybrid::first(),
            //'kanjihybridphrase' => KanjiHybridPhrase::first(),
            //'_getSentences' => $this->_getKanjiHybrid($this->_getNGrams(request('text'),2)),
            'conversion' => $this->_convertKanjiHybrid(request('text'))
        ]);
    }

    
    private function _getNGrams($text, $count) {
        $words = explode(" ", $text);
        $result = [];
        for ($i=0; $i<sizeof($words); $i++) {
            $ngram = '';
            for ($j=0; $j < $count && $j+$i < sizeof($words); $j++) {
                if ($ngram == ''){
                    $ngram = $words[$i + $j];
                }
                else {
                    $ngram = $ngram . ' ' . $words[$i + $j];
                }
                array_push($result, $ngram);
            }
        }
        return $result;
    }
    
    private function _getSentences($text) {
        return $sentences = preg_split('/(?<=[.?!,])\s+(?=[a-z])/i', $text);
    }
    
    private function _removePunct($text) {
        return preg_replace('/[^\w]+/', ' ', $text);
    }
    
    
    
    public function _convertKanjiHybrid($text, $onlySingle) {
        // find the relevant words needed from the database
        $sentences = $this->_getSentences($text);
        $multiKeys = [];
        $singleKeys = [];
        foreach ($sentences as $sentence) {
            $sentence = $this->_removePunct(strtolower($sentence));
            $multiKeys = array_merge($multiKeys, $this->_getNGrams($sentence, self::NGRAM_LEN));
            $singleKeys = array_merge($singleKeys, $this->_getNGrams($sentence, 1));
        }
        
        $cleaning = $this->_getCleaning($multiKeys);
        $reverse_cleaning = $this->_getCleaningReverse($multiKeys);
        $kanji_hybrid_phrase = $this->_getKanjiHybridPhrase($multiKeys, $onlySingle);
        $kanji_hybrid = $this->_getKanjiHybrid($singleKeys, $onlySingle);
        
        foreach ($cleaning as $key => $value) {
            $text = preg_replace('/\b' . $key . '\b/ui', $value, $text);
        }
        foreach ($kanji_hybrid_phrase as $key => $value) {
            $text = preg_replace('/\b' . $key . '\b/ui', $value, $text);
        }
        foreach ($kanji_hybrid as $key => $value) {
            $text = preg_replace('/\b' . $key . '\b/ui', $value, $text);
        }
        foreach ($reverse_cleaning as $key => $value) {
            if ($key == '’') {
                null;
            }
            else {
                $text = preg_replace('/\b' . $key . '\b/ui', $value, $text);
            }
        }
        
        return $this->_capitalizeSentences($text);
        
    }
    
    private function _getCleaning($keys) {
        $items = Cleaning::whereIn('cleaned', $keys)->get();
        $result = [];
        foreach ($items as $item) {
            if (in_array($item->cleaned, array(" ", null))) {
                #skip
                null;
            }
            elseif (! array_key_exists($item->cleaned, $result)) {
                $result[$item->cleaned] = preg_replace('/-/', '_', $item->compound_cleaned);
            }
        }
        return $result;
    }
    private function _getCleaningReverse($keys) {
        $items = Cleaning::whereIn('cleaned', $keys)->get();
        $result = [];
        foreach ($items as $item) {
            $item->compound_cleaned = preg_replace('/-/', '_', $item->compound_cleaned);
            if (! array_key_exists($item->compound_cleaned, $result)) {
                $result[$item->compound_cleaned] = $item->cleaned;
            }
        }
        return $result;
    }
    
    private function _getKanjiHybridPhrase($keys, $onlySingle=false) {
        $items = KanjiHybridPhrase::whereIn('english', $keys)->get();
        $result = [];
        foreach ($items as $item) {
            if ($onlySingle){
                if (! array_key_exists($item->english, $result)) {
                    $result[$item->english] = $item->hybrid;
                }
            }
            else {
                $result[$item->english] = $item->english;
            }
        }
        return $result;
    }
    private function _getKanjiHybrid($keys, $onlySingle=false) {
        // if onlySingle is true, no translation is performed but the word is tagged with the | delimeter
        $items = KanjiHybrid::whereIn('english', $keys)->get();
        $result = [];
        foreach ($items as $item) {
            if ($onlySingle) {
                if (! array_key_exists($item->english, $result)) {
                    $result[$item->english] = $item->hybrid;
                }
                else {
                    $result[$item->english] = $item->english;
                }
            }
            else {
                $result[$item->english] = '|' . $item->english . '|';
            }
           
        }
        return $result;
    }
    
    public function _capitalizeSentences($string) {
        $txt = ucfirst(preg_replace_callback('/[.!?\n].*?\w/', create_function('$matches', 'return strtoupper($matches[0]);'),$string));
        if (starts_with($string, '|') || starts_with($string, '{')){
            return strtoupper( substr( $txt, 0, 2 ) ).substr( $txt, 2 );
        }
        else {
            return $txt;
        }
    }
    
}
