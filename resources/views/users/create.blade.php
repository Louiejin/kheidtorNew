@extends('layouts.master')

@section('title')
  <h3>Create New User</h3>
@endsection

@section('content')
    @include('layouts.errors')
    
    <form method="POST" action="/user">
    {{ csrf_field() }}
    @include('forms.user')
    <div class="pull-right">
      <div class="form-group">
        <a class="btn btn-warning" href="/users">
        <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
        &nbsp;Cancel</a>
        <button type="submit" class="btn btn-success">
        <span class="glyphicon glyphicon-save" aria-hidden="true"></span>
            &nbsp;Save</button>
        
      </div> 
    </div>
    </form>
    
@endsection