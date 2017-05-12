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
        <div class="col-md-6">
      <p>
      <a class="btn btn-success" href="#" onclick="onclickSubmitAction('save_advanced', '/article/{{ $article->id }}/convert/basic' )">
      <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
      &nbsp;Basic</a>
      <a class="btn btn-primary" href="#" onclick="onclickSubmitAction('save_advanced', '/article/{{ $article->id }}/convert/advanced' )">
      <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
      &nbsp;Advanced</a>
        <span class= "blue-highlight">MULTIPLE</span>
        <span class= "pink-highlight" >UNIQUE</span>
        <span class= "yellow-highlight" >COMPOUND</span>
      
      </p>
      <form method="POST" action="#" id="save_advanced">
        {{ csrf_field() }}
        <textarea id="fulltextarea" class="form-control" rows="15" id="body" name="body">{{ $article->body }}</textarea>
      </form>
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
        <a class="btn btn-success" href="#" onclick="onclickSubmit('publish_form')" @if(!request()->session()->get('from_update')) disabled @endif >
        <span class="glyphicon glyphicon-open-file" aria-hidden="true"></span>
        &nbsp;Publish</a>
        @endif
      </p>
      <p id="advanced_conversion_text">{!! nl2br($article->processed_body) !!}</p>
    </div>
    
  </div>
  <div class="row" style="display: none">
    <form method="POST" action="/article/{{ $article->id}}" enctype="multipart/form-data" id="save_translation">
        <input type="hidden" name="_method" value="PATCH">
        <input type="hidden" name="status" value="Advanced">
        {{ csrf_field() }}
        <div class="col-md-6">
          <textarea class="form-control" rows="15" id="translated_body" name="translated_body">{{ $article->translated_body }}</textarea>
        </div>
    </form>
      
  </div>
  <div class="row" style="display: none">
    <script>
      var init_advanced = [];
    </script>
    @foreach ($wordsOptions as $wordIndex=>$word)
      <script>
        init_advanced.push(function() {
            $('#kh_' + {{ $wordIndex }}).popover({
                html: true,
                content: function() {
                    return $('#kh_' + {{ $wordIndex }} + "_options").html();
                },
                title: "Please select.",
                placement: "bottom",
                trigger: "click"
            });
        });
      </script>
      <div id="kh_{{ $wordIndex }}_options">
        
        <div class="advanced_popover">
            <div class="advanced_popover_item">
              <div class="adv_pop_button">
                <button class="btn btn-sm btn-warning" onclick="replaceConversion({{ $wordIndex }}, '{{ $word['english'] }}', true)">Pick</button>
              </div>
              <div class="adv_pop_text">
                Do not convert: <b>{{ $word['english'] }}</b>
              </div>
            </div>
            
            @foreach ($word['options'] as $optionIndex=>$option)
            <div id="cleared"></div>
            <div class="advanced_popover_item">
              <div class="adv_pop_button">
                <button class="btn btn-sm btn-success" onclick="replaceConversion({{ $wordIndex }}, '{{ $option->hybrid }}', true)">Pick</button>
              </div>
              <div class="adv_pop_text">
                {{ $optionIndex }}) {{ $option->english }} ({{ $option->grammar }}) = 
                {{ $option->core_meanings }}
              </div>
            </div>
            @endforeach
            <div id="cleared"></div>
        </div>
      </div>
    @endforeach
  </div>

    <div style="display: none">
      <form method="POST" action="/article/{{ $article->id}}/publish" id="publish_form">
        {{ csrf_field() }}
      </form>
    </div>

@endsection