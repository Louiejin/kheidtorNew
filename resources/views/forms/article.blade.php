
      
        <div class="form-group">
          <label for="fullname">Title</label>
          <input type="text" class="form-control" id="title" name="title" placeholder="Title" required 
          @if (empty($article))
            value="{{ request()->old('title') }}"
          @else
            value="{{ $article->title }}"
          @endif
          >
        </div>
        
        <div class="form-group">
          <label for="fullname">URL</label>
          <input type="text" class="form-control" id="url" name="url" placeholder="Source URL" required 
          @if (empty($article))
            value="{{ request()->old('url') }}"
          @else
            value="{{ $article->url }}"
          @endif
          >
        </div>

        <div class="form-group">
          <label for="fullname">Original Author</label>
          <input type="text" class="form-control" id="authoer" name="author" placeholder="Original Author" 
          @if (empty($article))
            value="{{ request()->old('author') }}"
          @else
            value="{{ $article->author }}"
          @endif
          >
        </div>

        <div class="form-group">
          <label for="comment">Body:</label>
          <textarea class="form-control" rows="15" id="body" name="body">@if (empty($article)){{ request()->old('body') }}@else{{ $article->body }}@endif</textarea>
        </div>
    
        <div class="form-group">
          <label for="image">Upload Image</label>
          <input type="file" id="image" name="image">
        </div>

        <div class="form-group">
          <label for="comment">Image Caption:</label>
          <textarea class="form-control" rows="3" id="caption" name="caption">@if (empty($article)){{ request()->old('caption') }}@else{{ $article->caption }}@endif</textarea>
        </div>

