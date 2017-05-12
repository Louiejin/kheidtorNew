@extends('layouts.master')

@section('title')
  <h3>Create New Article</h3>
@endsection

@section('content')
  @include('layouts.errors')
  @include('layouts.success')
  
    
    <div class="row">
    <div class="col-md-6">
      <form method="POST" action="/article" enctype="multipart/form-data">
        {{ csrf_field() }}
        @include('forms.article')
        <div class="form-group">
          <a class="btn btn-warning" href="/articles">
          <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
          Cancel</a>
          <span class="pull-right">
          <button type="submit" class="btn btn-success">
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span>
          &nbsp;Add Article</button>
          </span>
        </div> 
      </form>
    </div>
    </div>

@endsection