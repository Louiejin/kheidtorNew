@extends('layouts.master')


@section('title')
  <h3>KHEngine Database</h3>
@endsection


@section('content')
  <ul class="nav nav-tabs">
    <li><a href="/databases">Manage</a></li>
    <li class="active"><a href="/databases/history">History</a></li>
  </ul>
  
    <div class="spacer"></div>
    <h4>Search Previous Versions</h4>
    
    <div class="spacer"></div>
    <form method="get" class="form-inline" action="/databases/history">
    <input type="text" id="date" data-format="YYYY-MM-DD" data-template="D MMM YYYY" name="q" value="{{ request('q')? request('q'): Carbon\Carbon::parse(date('m/d/Y h:i:s a', time()))->format('Y-m-d') }}">
    <button class="form-group btn btn-success" href="#" onclick="onclickSubmit('search_articles_form')">
    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
            &nbsp;Search</button>
    </form>
    <script>
    $(function(){
        $('#date').combodate({
            customClass: "form-control"
            });
    });
    </script>
    <br />
    
    <table class="table">
      <tr>
        <td>
        @if ($cleaning)
        <a class="btn btn-primary btn-sm" href="/database/{{ $cleaning->id }}">
        <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span>
            &nbsp;Download</a>
        @endif
        </td>
        <td><label for="cleaning">1. Cleaning: </label>

        @if ($cleaning)
        <br /> {{ $cleaning->name }} </td>
        <td>{{ $cleaning->created_date }}</td>
        <td>{{ $cleaning->creator->fullname }}</td>
        @else
        <br /> Not Found! </td>
        <td></td>
        <td></td>
        @endif
        
      </tr>
      <tr>
        <td>
        @if ($kanji_hybrid_phrase)
        <a class="btn btn-primary btn-sm" href="/database/{{ $kanji_hybrid_phrase->id }}">
        <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span>
            &nbsp;Download</a>
        @endif
        </td>
        <td><label for="cleaning">2. Phrases: </label>
        
        @if ($kanji_hybrid_phrase)
        <br /> {{ $kanji_hybrid_phrase->name }} </td>
        <td>{{ $kanji_hybrid_phrase->created_date }}</td>
        <td>{{ $kanji_hybrid_phrase->creator->fullname }}</td>
        @else
        <br /> Not Found! </td>
        <td></td>
        <td></td>
        @endif

      </tr>
      <tr>
        <td>
        @if ($kanji_hybrid)
        <a class="btn btn-primary btn-sm" href="/database/{{ $kanji_hybrid->id }}">
        <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span>
            &nbsp;Download</a>
        @endif
        </td>
        <td><label for="cleaning">3. KanjiHybrid: </label>
        
        @if ($kanji_hybrid)
        <br /> {{ $kanji_hybrid->name }} </td>
        <td>{{ $kanji_hybrid->created_date }}</td>
        <td>{{ $kanji_hybrid->creator->fullname }}</td>
        @else
        <br /> Not Found! </td>
        <td></td>
        <td></td>
        @endif
      </tr>
    </table>
        

    

@endsection