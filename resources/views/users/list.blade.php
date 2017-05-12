@extends('layouts.master')

@section('title')
  <h3>Users</h3>
@endsection

@section('content')
  
  <div class="row">
    <div class="col-md-12 form-group">
    <p> <a class="form-group btn btn-primary pull-right" href="/user/new">
    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>&nbsp;
     Add New</a></p>
    </div>
  </div>

  @include('layouts.success')
  
  <table class="table table-striped">
    <tbody>
      @foreach ($users as $user)
      <tr>
        <td>
          {{ $user->username }}
        </td>
        <td>
          {{ $user->fullname }}
        </td>
        <td>
          {{ implode(', ', $user->roles_arr()) }}
        </td>
        <td>
          @if ($user->enabled)
          <form method="POST" action="/user/{{ $user->id }}/deactivate">
            <a class="btn btn-primary btn-xs" href="/user/{{ $user->id }}/edit">
            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;
            Edit</a>
            {{ csrf_field() }} 
            @if ($user->id != auth()->user()->id)
            <input class="btn btn-danger btn-xs" type="submit" value="Deactivate">
            @endif
          </form>
          @else
          <form method="POST" action="/user/{{ $user->id }}/activate">
            <a class="btn btn-primary btn-xs" href="/user/{{ $user->id }}/edit"> 
            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;
            Edit</a>
            {{ csrf_field() }} 
            @if ($user->id != auth()->user()->id)
            <input class="btn btn-success btn-xs" type="submit" value="Activate">
            @endif
          </form>
          
          @endif
          
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
    
@endsection