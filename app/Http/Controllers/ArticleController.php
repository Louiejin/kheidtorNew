<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Article;
use App\KanjiHybrid;
use App\KanjiHybridPhrase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;


class ArticleController extends Controller
{
    const NUM_PER_PAGE = 8;
    
    public function __construct() {
    }
    
    
    private static function isAuthorized() {
        if (!auth()->check()) {
            return false;
        }
        elseif(auth()->user()->admin || auth()->user()->publish || auth()->user()->edit) {
            return true;
        }
        else {
            return false;
        }
    }
    
    private static function canAccess($article) {
        if (auth()->user()->admin || auth()->user()->publish) {
            return true;
        }
        elseif(auth()->user()->edit && auth()->user()->id == $article->creator->id) {
            return true;
        }
        else {
            return false;
        }
    }
    
    public function readAll() {
        if (request('p')) {
            $page = request('p');
        }
        else {
            $page = 1;
        }
        $offset = ($page-1) * self::NUM_PER_PAGE;
        
        if (ArticleController::isAuthorized()) {
            $showUserOnly = !(auth()->user()->admin || auth()->user()->publish);
            if (request('q')) {
                // TODO: refactor this query to use Eloquent
                $articles = DB::select('select id from articles where LOWER(title) LIKE ?', 
                        ['%' . strtolower(request('q')) . '%']);
                $ids = [];
                foreach ($articles as $article) {
                    array_push($ids, $article->id);
                }
                $query = Article::whereIn('id', $ids);
                if ($showUserOnly) {
                    $query = $query->where('created_by', auth()->user()->id);
                }
                $totalPages = $query->count();
                $query = $query->skip($offset)->take(self::NUM_PER_PAGE);
                $articles = $query->orderByDesc("created_date")->get();
            }
            else {
                $query = Article::where('id', '>', 0);
                if ($showUserOnly) {
                    $query = $query->where('created_by', auth()->user()->id);
                }
                $totalPages = $query->count();
                $query = $query->skip($offset)->take(self::NUM_PER_PAGE);
                $articles = $query->orderByDesc("created_date")->get();
                
            }
            
            $pagination = [
                    'page' => $page,
                    'total' => ceil($totalPages/self::NUM_PER_PAGE)
            ];
            return view('articles.list', compact('articles', 'pagination'));
        }
        else {
            return view('unauthorized');
        }
    }
    
    public function create() {
        if (ArticleController::isAuthorized()) {
            return view('articles.create');
        }
        else {
            return view('unauthorized');
        }
    }
    
    public function get($id) {
        // TODO: Placeholder
       ($article = Article::find($id));
       $article->title;
       $article->author;
       $article->translated_body = $this->_transformBodyForPreview($article->translated_body);
       $article->caption;
       $article->image;
       $article->url;
       
        
        return view('articles.preview', ['article' => $article]);
    }
    
    public function edit(Article $article) {
        if (ArticleController::isAuthorized() && ArticleController::canAccess($article)) {
            return view('articles.edit', compact('article'));
        }
        else {
            return view('unauthorized');
        }
    }
    
    public function convert(Article $article) {
        if (ArticleController::isAuthorized() && ArticleController::canAccess($article)) {
            if ($article->status == 'Basic') {
                return redirect('/article/' . $article->id . '/convert/basic');
            }
            elseif ($article->status == 'Advanced') {
                return redirect('/article/' . $article->id . '/convert/advanced');
            }
            elseif ($article->status == 'Manual') {
                return redirect('/article/' . $article->id . '/convert/manual');
            }
            elseif ($article->status == 'Published') {
                return redirect('/article/' . $article->id . '/convert/manual');
            }
            else {
                return view('articles.convert', compact('article'));
            }
        }
        else {
            return view('unauthorized');
        }
        
    }

    
    public function basic(Article $article) {
        if (ArticleController::isAuthorized() && ArticleController::canAccess($article)) {
            if (request()->isMethod('post')) {
                // validation
                $this->validate(request(), [
                        'body' => 'required',
                ]);
                $article->body = request('body');
                $article->save();
                $conversion = new ConversionController();
                $article->translated_body = $conversion->_convertKanjiHybrid($article->body, true);
            }
            list($transformed, $found, $words) = $this->_transformBodyForAdvancedConvert($article->translated_body, true);
            $article->processed_body =  $transformed;
            
            return view('articles.convert', compact('article'));
        }
        else {
            return view('unauthorized');
        }
    }
    
    public function manual(Article $article) {
        if (ArticleController::isAuthorized() && ArticleController::canAccess($article)) {
            if (request()->isMethod('patch')) {
                $article->translated_body = request('translated_body');
                $article->save();
            }
            else {
                if ($article->translated_body == '') {
                    $conversion = new ConversionController();
                    $article->translated_body = $conversion->_convertKanjiHybrid($article->body, true);
                }
            }    
            return view('articles.manual', compact('article'));
        }
        else {
            return view('unauthorized');
        }
    }
    
    
    public function advanced(Article $article) {
        if (ArticleController::isAuthorized() && ArticleController::canAccess($article)) {
            $conversion = new ConversionController();
            if (request()->isMethod('post')) {
                // validation
                $this->validate(request(), [
                        'body' => 'required',
                ]);
                $article->body = request('body');
                $article->save();
                $article->translated_body = $conversion->_convertKanjiHybrid($article->body, true);
            }
            if ($article->translated_body == '') {
                $article->translated_body = $conversion->_convertKanjiHybrid($article->body, true);
            }
            $article->translated_body = $conversion->_convertKanjiHybrid($article->translated_body, false);
            list($transformed, $found, $words) = $this->_transformBodyForAdvancedConvert($article->translated_body, true);
        
            $article->processed_body =  $transformed;
            $wordsOptions = $this->_getWordsOptions($words);
            return view('articles.advanced', compact('article', 'wordsOptions'));
        }
        else {
            return view('unauthorized');
        }
    }
    
    
    public function store() {
        // validation
        $this->validate(request(), [
                'title' => 'required|unique:articles',
                'body' => 'required',
                'image' => 'required',
                'url' => 'required|unique:articles'
        ]);
        
        $imagePath = request()->file('image')->store('images', 'public');
        
        $article = new Article;
        $article->title = request('title');
        $article->body = request('body');
        $article->url = request('url');
        $article->image = $imagePath;
        $article->caption = request('caption');
        $article->author = request('author');
        $article->created_by = auth()->user()->id;
        $article->updated_by = auth()->user()->id;
        $article->category = '';
        $article->translated_title = '';
        $article->translated_body = '';
        $article->status = 'Draft';
        $article->save();
        
        request()->session()->flash('success', 'Article Saved! Start converting below...');
        return redirect('/article/'. $article->id . '/convert');
    }
    
    public function update(Article $article) {
        if (ArticleController::isAuthorized() && ArticleController::canAccess($article)) {
            // validation
            $article->updated_by = auth()->user()->id;
            $article->category = '';
            if (request('image')) {
                $imagePath = request()->file('image')->store('images', 'public');
                $article->image = $imagePath;
            }
            if (request('title')) {
                $article->title = request('title');
            }
            if (request('body')) {
                $article->body = request('body');
            }
            if (request('url')){
                $article->url = request('url');
            }
            if (request('caption')) {
                $article->caption = request('caption');
            }
            if (request('author')) {
                $article->author = request('author');
            }
            if (request('translated_title')) {
                $article->translated_title = request('translated_title');
            }
            if (request('translated_body')) {
                $conversion = new ConversionController();
                $article->translated_body = $conversion->_capitalizeSentences(request('translated_body'));
                if ($article->status != 'Published') {
                    $article->status = request('status');
                }
            }
            $article->save();
            
            request()->session()->flash('success', 'Update successful!');
            request()->session()->flash('from_update', true);
            return redirect()->back();
        }
        else {
            return view('unauthorized');
        }
    }
    
    public function destroy($id) {
        $article = Article::findOrFail($id);
        if (ArticleController::isAuthorized() && ArticleController::canAccess($article)) {
    	
    	$article->delete();
    	
    	\Session::flash('success','Article successfully deleted.');
    	
    	return redirect()->back();
        }
        else {
            return view('unauthorized');
        }
    }
    
    public function publish(Article $article) {
        Log::debug('publish: ' . $article->id );
        if (ArticleController::isAuthorized() && ArticleController::canAccess($article)) {
            if (!isset(auth()->user()->wp_token)) {
                request()->session()->flash('m_errors', ['You cannot publish without linking a wordpress account. <a href="/user/me/edit">Edit Profile</a>']);
                return redirect()->back();
            }
            
            try {
                $client = new Client([
                        'verify' => false
                ]);
                
                // get a token
                $params = [
                        'username' => auth()->user()->wp_username,
                        'password' => Crypt::decryptString(auth()->user()->wp_password)
                ];
                $res = $client->request('POST', 'https://www.kanjihybrid.com/wp-json/jwt-auth/v1/token', ['http_errors' => false, 'json' => $params]);
                Log::debug($res->getBody());
                $res = json_decode($res->getBody());
                if (isset($res->token)) {
                    $wp_token = $res->token;
                }
                // upload photo
                $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($article->image);
                $handle = fopen($path, 'r');
                
                $res = $client->request(
                        'POST',
                        'https://www.kanjihybrid.com/wp-json/wp/v2/media', [
                                'multipart' => [
                                        [
                                                'name' => 'file',
                                                'contents' => $handle,
                                                'filename' => basename($path)
                                        ]
                                ],
                                'headers' => [
                                        'Authorization' => 'Bearer ' . $wp_token
                                ]
                        ]
                        );
                Log::debug($res->getBody());
                
                // edit all details
                $media_json = json_decode($res->getBody());
                $media_id = $media_json->id;
                $res = $client->request(
                        'POST',
                        'https://www.kanjihybrid.com/wp-json/wp/v2/media/' . $media_id, [
                                'json' => [
                                        'title' => str_slug($article->title),
                                        'caption' => $article->caption
                                ],
                                'headers' => [
                                        'Authorization' => 'Bearer ' . $wp_token
                                ]
                        ]
                        );
                Log::debug($res->getBody());
                
                // upload the actual content
                $res = $client->request(
                        'POST',
                        'https://www.kanjihybrid.com/wp-json/wp/v2/posts' , [
                                'json' => [
                                        'title' => $article->title,
                                        'content' => $this->_createWPPost($article, $media_json),
                                        'featured_media' => $media_id
                                ],
                                'headers' => [
                                        'Authorization' => 'Bearer ' . $wp_token
                                ]
                        ]
                        );
                Log::debug($res->getBody());
                
                $post_json = json_decode($res->getBody());
                $post_id = $post_json->id;
                $post_url = 'https://www.kanjihybrid.com/wp-admin/post.php?post=' . $post_id . '&action=edit';
                // change status
                $article->status = 'Published';
                $article->wp_postid = $post_id;
                $article->wp_url = $post_url;
                
                $article->save();
                request()->session()->flash('success', 'Article successfully published. <a href="' . $post_url .'" target="_blank">View in wordpress</a>');
                request()->session()->flash('from_publish', true);
                return redirect()->back();
            }
            catch(\Exception $e) {
                request()->session()->flash('m_errors', [$e->getMessage()]);
                return redirect()->back();
            }
        }
        else {
            return view('unauthorized');
        }
        
    }
    
    private function _transformBodyForPreview($text) {
        $result = [];
        $words = [];
        $found = 0;
        $tracking_phrase = false;
        $tracking_word = false;
        $tracking_untranslated = false;
        $body = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        $click_cmd = '';
        foreach ($body as $char) {
        
            if ($char == '{' || $char == '[') {
                array_push($result, '<span data-toggle="popover" data-placement="top" ' . $click_cmd . ' id="kh_'. $found. '" class="white-highlight">', $char);
                $word = [];
                $tracking_phrase = true;
                $found++;
            }
            elseif ($char == '}' || $char == ']') {
                array_push($result, $char , '</span>');
                array_push($word, $char);
                array_push($words, implode('', $word));
                $tracking_phrase = false;
            }
            elseif (!$tracking_phrase && !$tracking_word && !$tracking_untranslated && $this->_isUnicode($char)) {
                array_push($result, '<span data-toggle="popover" data-placement="top" ' . $click_cmd . ' id="kh_'. $found. '" class="white-highlight">', $char);
                $word = [];
                $tracking_word = true;
                $found++;
            }
            elseif ($tracking_word && !ctype_alpha($char)) {
                array_push($result, '</span>', $char);
                //array_push($word, $char);
                array_push($words, implode('', $word));
                $tracking_word = false;
            }
            elseif ($char == '|' && $tracking_untranslated != true) {
                array_push($result, '<span data-toggle="popover" data-placement="top" ' . $click_cmd . ' id="kh_'. $found. '" class="white-highlight">');
                $word = [];
                $tracking_untranslated = true;
                $found++;
            }
            elseif ($char == '|') {
                array_push($result , '</span>');
                array_push($words, '|' . implode('', $word));
                $tracking_untranslated = false;
            }
            else {
                array_push($result, $char);
            }
            // form word
            if (($tracking_word || $tracking_phrase || $tracking_untranslated) && $char!='|') {
                array_push($word, $char);
            }
        }
        return implode('', $result);
    }
    
    private function _transformBodyForAdvancedConvert($text, $clickable=true) {
        $result = [];
        $words = [];
        $found = 0;
        $tracking_phrase = false;
        $tracking_word = false;
        $tracking_untranslated = false;
        $body = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        foreach ($body as $char) {
            if ($clickable) {
                $click_cmd = 'onclick="showOptions('. $found. ')"';
            }
            else {
                $click_cmd = '';
            }
            
            if ($char == '{' || $char == '[') {
                array_push($result, '<span data-toggle="popover" data-placement="top" ' . $click_cmd . ' id="kh_'. $found. '" class="yellow-highlight">', $char);
                $word = [];
                $tracking_phrase = true;
                $found++;
            }
            elseif ($char == '}' || $char == ']') {
                array_push($result, $char , '</span>');
                array_push($word, $char);
                array_push($words, implode('', $word));
                $tracking_phrase = false;
            }
            elseif (!$tracking_phrase && !$tracking_word && !$tracking_untranslated && $this->_isUnicode($char)) {
                array_push($result, '<span data-toggle="popover" data-placement="top" ' . $click_cmd . ' id="kh_'. $found. '" class="pink-highlight">', $char);
                $word = [];
                $tracking_word = true;
                $found++;
            }
            elseif ($tracking_word && !ctype_alpha($char)) {
                array_push($result, '</span>', $char);
                //array_push($word, $char);
                array_push($words, implode('', $word));
                $tracking_word = false;
            }
            elseif ($char == '|' && $tracking_untranslated != true) {
                array_push($result, '<span data-toggle="popover" data-placement="top" ' . $click_cmd . ' id="kh_'. $found. '" class="blue-highlight">');
                $word = [];
                $tracking_untranslated = true;
                $found++;
            }
            elseif ($char == '|') {
                array_push($result , '</span>');
                array_push($words, '|' . implode('', $word));
                $tracking_untranslated = false;
            }
            else {
                array_push($result, $char);
            }
            // form word
            if (($tracking_word || $tracking_phrase || $tracking_untranslated) && $char!='|') {
                array_push($word, $char);
            }
        }
        return array(implode('', $result), $found, $words);
    }
    
    private function _getWordsOptions($words) {
        // returns: array of {word: "", options: []}
        $result = [];
        foreach ($words as $word) {
            $word = strtolower($word);
            if (starts_with($word, '{') || starts_with($word, '[')) {
                $row = KanjiHybridPhrase::where('hybrid', $word)->first();
                if ($row) {
                    $english = $row->english;
                    //$options = KanjiHybridPhrase::where('english', $row->english)->get();
                    $option = new class{};
                    $option->english = $word;
                    $option->hybrid = $word;
                    $option->grammar = 'Phrase';
                    $option->core_meanings = $english;
                    $options = [$option];
                    }
                else {
                    $options = [];
                }
            }
            elseif (starts_with($word, '|')) {
                $word = ltrim($word, '|');
                $row = KanjiHybrid::where('english', $word)->first();
                if ($row) {
                    $english = $row->english;
                    $options = KanjiHybrid::where('english', $row->english)->get();
                }
                else {
                    $options = [];
                }
                
            }
            else {
                $row = KanjiHybrid::where('hybrid', $word)->first();
                if ($row) {
                    $english = $row->english;
                    $options = KanjiHybrid::where('english', $row->english)->get();
                }
                else {
                    $options = [];
                }
            }
            array_push($result, array("word" => $word, "english" => $english, "options" => $options));
        }
        //print_r($result);
        return $result;
    }
    
    private function _isUnicode($string) {
        if ($string == '“') return false;
        elseif ($string == '”') return false;
        elseif ($string == '’') return false;
        elseif ($string == '—') return false;
        
        return (strlen($string) != strlen(utf8_decode($string)));
    }
    
    private function _createWPPost($article, $media) {
        $pre = '[caption id="attachment_' . $media->id . '" align="alignleft" width="300"]<img class="size-medium wp-image-' . $media->id. '" src="' . $media->guid->rendered. '" alt="" width="300" /> '. $article->caption . '[/caption]';
        return $pre . '\n' . $article->translated_body;
    }
}

