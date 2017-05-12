@extends('layouts.master')


@section('title')
  <h3>KHEngine Database</h3>
@endsection


@section('content')
  <ul class="nav nav-tabs">
    <li class="active"><a href="/databases">Manage</a></li>
    <li><a href="/databases/history">History</a></li>
        
  </ul>
  <div class="spacer"></div>
  <div class="row">
    
    <div class="col-md-6">
      <h4>Latest Versions</h4>
      
        <br />
        @if ($cleaning)
        <div class="form-group">
          <label for="cleaning">1. Cleaning: </label> {{ $cleaning->name }} <br/>
          <a class="btn btn-primary btn-sm" href="/database/{{ $cleaning->id }}">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span>
            &nbsp;Download</a>
        </div>
        <br />
        @endif
        
        @if($kanji_hybrid_phrase)
        <div class="form-group">
          <label for="cleaning">2. Phrases: </label> {{ $kanji_hybrid_phrase->name }} <br/>
          <a class="btn btn-primary btn-sm" href="/database/{{ $kanji_hybrid_phrase->id }}">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span>
            &nbsp;Download</a>
        </div>
        <br />
        @endif
        
        @if ($kanji_hybrid)
        <div class="form-group">
          <label for="cleaning">3. KanjiHybrid: </label> {{ $kanji_hybrid->name }}<br/>
          <a class="btn btn-primary btn-sm" href="/database/{{ $kanji_hybrid->id }}">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span>
            &nbsp;Download</a>
        </div>
        <br />
        @endif
      
      
    </div>
    <div class="col-md-6">
      <h4>Upload New Versions</h4>
        @include('layouts.errors')
        @include('layouts.success')
      
      <form method="POST" action="/database" enctype="multipart/form-data" id="save_form">
        {{ csrf_field() }}
        
        <br />
        <div class="form-group">
          <label for="cleaning">1. Cleaning</label>
          <input type="file" name="cleaning">
        </div>
        <br />
        
        <div class="form-group">
          <label for="kanji_hybrid_phrase">2. Phrases</label>
          <input type="file" name="kanji_hybrid_phrase">
        </div>
        <br />
        
        <div class="form-group">
          <label for="kanji_hybrid">3. KanjiHybrid</label>
          <input type="file" name="kanji_hybrid">
        </div>
        <br />
        
        <a class="btn btn-success" onclick="onclickSubmit('save_form')">
        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            &nbsp;Update</a>
        
      </form>
    </div>
      
  </div>
@endsection