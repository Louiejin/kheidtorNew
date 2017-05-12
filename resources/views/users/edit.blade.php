@extends('layouts.master')

@section('title')
  <h3>Edit User: {{ $user->username }}</h3>
@endsection

@section('content')
    @include('layouts.errors')
    @include('layouts.success')
    
    <form method="POST" action="/user/{{ $user->id }}">
    <input type="hidden" name="_method" value="PATCH">
    {{ csrf_field() }}
      
    @include('forms.user')
      
    <div class="pull-right">
      <div class="form-group">
        <a class="btn btn-warning" href="/users">
         <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
            &nbsp;Back</a>
        <button type="submit" class="btn btn-success">
        <span class="glyphicon glyphicon-save" aria-hidden="true"></span>
            &nbsp;Save</button>
      </div> 
    </div>
    </form>
    

    <div class="container"></div>

    <hr />

    <form method="POST" action="/user/{{ $user->id }}/updatepassword">
    <input type="hidden" name="_method" value="PATCH">
    {{ csrf_field() }}
      
    @include('forms.password')
      
    <div class="pull-right">
      <div class="form-group">
        <button type="submit" class="btn btn-success">
        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            &nbsp;Update Password</button>
      </div> 
    </div>
    </form>

    <div class="container"></div>

    <hr />

    <form method="POST" action="/user/{{ $user->id }}/updatewordpress">
    {{ csrf_field() }}
      
    @include('forms.wordpress_credentials')
      
    <div class="pull-right">
      <div class="form-group">
        @if(isset($user->wp_token))
        <a class="btn btn-danger" href="#" onclick="onclickSubmit('unlink')">
        <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
            &nbsp;Unlink</a>
        @endif
        <button type="submit" class="btn btn-success">
        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            &nbsp;Update</button>
      </div> 
    </div>
    </form>
    
    <form method="POST" action="/user/{{ $user->id }}/unlinkwordpress" id="unlink">
    {{ csrf_field() }}
    </form>

@endsection