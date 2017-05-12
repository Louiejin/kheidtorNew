@extends('layouts.master')



@section('content')
	 @include('layouts.errors')
  	 @include('layouts.success')

<div class="col-md-6">
	<div class="boxed active">
	
		<h1>
			<span class="title_capitalized">{{ $article->title }}</span>
		</h1>
		
		<span> KH Editor: <a id="author" name="author">{{ $article->author }}</a>
		</span>
		<hr>
		<span>
			<figure>
				<div class="wp-caption alignleft">
					<img class=size-medium src="{{ Storage::disk('public')->url($article->image) }}"
						sizes:"(max-width:300px) 100vq, 300px"></a>
					<figcaption style="font-size: 0.8em">
						{{ $article->caption }}
					</figcaption>
				</div>
			</figure>
            <br />
			<p>{!! nl2br($article->translated_body) !!}</p>
		</span>
	</div>
	<!-- <a href="{{ $article->url }}">Continue here…</a>  -->
</div>
@endsection

