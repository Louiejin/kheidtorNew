@extends('layouts.master')

@section('title')
  <h3>{{ $article->title }}</h3>
@endsection

@section('content')
      <ul class="nav nav-tabs">
        <li class="active"><a href="/article/{{ $article->id }}/edit">Edit Properties</a></li>
        <li><a href="/article/{{ $article->id }}/convert">Convert</a></li>
        
        <span class="pull-right" style="margin-right: 15px">
        <!-- 
        <a class="btn btn-danger" href="/articles">
        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
        &nbsp;Cancel</a>
        <a class="btn btn-warning" href="/article/{{ $article->id }}/preview" target="_blank">
        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
        &nbsp;Preview</a>
        <a class="btn btn-primary" href="#" onclick="onclickSubmit('save_form')">
        <span class="glyphicon glyphicon-save" aria-hidden="true"></span>
        &nbsp;Save</a>
        <a class="btn btn-success" href="#" onclick="onclickSubmit('publish_form')">
        <span class="glyphicon glyphicon-open-file" aria-hidden="true"></span>
        &nbsp;Publish</a>
        -->
        </span>
        
      </ul>
  <p><br /></p>
  @include('layouts.errors')
  @include('layouts.success')

    <div class="row">
    <div class="col-md-6">
      <form method="POST" action="/article/{{ $article->id}}" enctype="multipart/form-data" id="save_form">
        <input type="hidden" name="_method" value="PATCH">
        {{ csrf_field() }}
        @include('forms.article')
        <div class="form-group">
          <a class="btn btn-warning" href="/articles">
          <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
          Cancel</a>
          <span class="pull-right">
          <button type="submit" class="btn btn-success">
          <span class="glyphicon glyphicon-save" aria-hidden="true"></span>
          &nbsp;Update Article</button>
          </span>
        </div> 
      
      </form>
      
      
    </div>
    <div class="col-md-6">
      <label>Image</label><br/>
      <img src="{{ Storage::disk('public')->url($article->image) }}"></img>
    </div>
    </div>
    
    <div style="display: none">
      <form method="POST" action="/article/{{ $article->id}}/publish" id="publish_form">
        {{ csrf_field() }}
      </form>
    </div>
@endsection