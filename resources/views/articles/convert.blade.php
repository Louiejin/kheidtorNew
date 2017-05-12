@extends('layouts.master')

@section('title')
  <h3>{{ $article->title }}</h3>
@endsection

@section('content')  
  <ul class="nav nav-tabs">
    <li><a href="/article/{{ $article->id }}/edit">Edit Properties</a></li>
    <li class="active"><a href="/article/{{ $article->id }}/convert">Convert</a></li>
        
        <span class="pull-right" style="margin-right: 15px">

        </span>
  </ul>
  <p></p>
  @include('layouts.errors')
  @include('layouts.success')
  
  <div class="row" id="section_convert">
    @include('articles._already_published')
    <form method="POST" action="#" id="save_basic">
        {{ csrf_field() }}
        <div class="col-md-6">
          <p>
          <a class="btn btn-success" href="#" onclick="onclickSubmitAction('save_basic', '/article/{{ $article->id }}/convert/basic' )">
          <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
          &nbsp;Basic</a>
          <a class="btn btn-primary" href="#" onclick="onclickSubmitAction('save_basic', '/article/{{ $article->id }}/convert/advanced' )">
          <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
          &nbsp;Advanced</a>
          </p>
          <textarea id="fulltextarea" class="form-control" rows="15" id="body" name="body">{{ $article->body }}</textarea>
        </div>
        <div class="col-md-6">
          <p>
            <a class="btn btn-danger" href="#" onclick="onclickSubmitAction('save_translation', '/article/{{ $article->id }}/convert/manual' )">
            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
            &nbsp;Manual Edit</a>
            <a class="btn btn-primary" href="#" onclick="onclickSubmit('save_translation')">
            <span class="glyphicon glyphicon-save" aria-hidden="true"></span>
            &nbsp;Save</a>
            <a class="btn btn-warning" href="/article/{{ $article->id }}/preview" target="_blank" @if(!request()->session()->get('from_update')) disabled @endif>
            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
            &nbsp;Preview</a>
            
            @if (auth()->user()->publish || auth()->user()->admin)
            <a class="btn btn-success" href="#" onclick="onclickSubmit('publish_form')" @if(!request()->session()->get('from_update')) disabled @endif>
            <span class="glyphicon glyphicon-open-file" aria-hidden="true"></span>
            &nbsp;Publish</a>
            @endif
          </p>
          @if ($article->translated_body) <p>{!! nl2br($article->processed_body) !!} @else <i>Select Basic or Advanced</i> @endif
        </div>
    </form>
      
  </div>
  
  <div class="row" style="display: none">
    <form method="POST" action="/article/{{ $article->id}}" id="save_translation">
        <input type="hidden" name="_method" value="PATCH">
        <input type="hidden" name="status" value="Basic">
        {{ csrf_field() }}
        <div class="col-md-6">
          <textarea class="form-control" rows="15" id="translated_body" name="translated_body">{{ $article->translated_body }}</textarea>
        </div>
    </form>
      
  </div>
  
    <div style="display: none">
      <form method="POST" action="/article/{{ $article->id}}/publish" id="publish_form">
        {{ csrf_field() }}
      </form>
    </div>
  
@endsection