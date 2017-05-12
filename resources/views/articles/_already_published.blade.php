@if($article->status == 'Published' && !request()->session()->get('from_publish'))
<div class="col-md-12">
 This article is already published (<a href="{{ $article->wp_url }}" target="_blank">View in wordpress</a>). Publishing will create a new post <br /><br />
</div>
@endif