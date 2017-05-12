@extends('layouts.master')

@section('title')
  <h3>Articles</h3>
@endsection

@section('content')
  
  <div class="row">
    <div class="col-md-12 form-group">
    <form method="GET" action="/articles" id="search_articles_form">
      <div class="row">
        <div class="col-md-6"  style="padding-right: 5px;">
          <input type="text" class="form-control" id="search" placeholder="Search Articles" name="q" value="{{ request('q') }}">
        </div>
        <div  class="col-md-4" style="padding-left: 0px;">
          <button class="form-group btn btn-success" href="#" onclick="onclickSubmit('search_articles_form')">
          <span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp;
          Search</button>
        </div>
        <div class="col-md-2"><a class="form-group btn btn-primary pull-right" href="/article/new">
        <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>&nbsp;
        Add New</a>
        </div>
     
      </div>	
      
    </form>
    
    
    </div>
  </div>

  @include('layouts.success')
  
  <table class="table table-striped">
    <tbody>
      <tr>
        <th>Date</th>
        <th>Title</th>
        <th>Author</th>
        <th>Status</th>
        <th>Editor</th>
        <th>Last Mod By</th>
        <th>Date</th>
        <th></th>
      </tr>
      @if (count($articles))
          @foreach ($articles as $article)
          <tr>
            <td>
              {{ $article->created_date }}
            </td>
            <td>
              <!-- <img class="pull-left" src="{{ Storage::disk('public')->url($article->image) }}"> -->
              <a href="/article/{{ $article->id }}/convert">{{ $article->title }}</a>
            </td>
            <td>
              {{ $article->author }}
            </td>
            <td>
              {{ $article->status }}
            </td>
            <td>
              {{ $article->creator->fullname }}
            </td>
            <td>
              {{ $article->updater->fullname }}
            </td>
            <td>
              {{ $article->updated_date }}
            </td>
             <td>
           		{!! Form::open(['action' => ['ArticleController@destroy', $article->id], 'method' => 'delete']) !!}
    			{!! Form::submit('Delete', ['class'=> 'btn btn-danger btn-xs']) !!}
				{!! Form::close() !!}
            </td>
          </tr>
          @endforeach
      @else
        <tr><td colspan="5">No articles found<td></tr>
      @endif
    </tbody>
  </table>  

  <div id="article-pagination"></div>
  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="/js/bs_pagination/jquery.bs_pagination.js"></script>
    <script type="text/javascript" src="/js/bs_pagination/localization/en.min.js"></script>
  
  <script>
        var custom_init = [];
        custom_init.push(function() {
            console.log("running article pagination");
            $("#article-pagination").bs_pagination({
              currentPage: {{ $pagination['page'] }},
              totalPages: {{ $pagination['total'] }},
              visiblePageLinks: 5,
              showGoToPage: false,
              showRowsPerPage: false,
              showRowsInfo: false,
              containerClass: "",
              onChangePage: function(event, data) {
                  console.log(window.location.href);
                  url = window.location.href;
                  url = url.replace(/\&p=\d+/i, "");
                  url = url.replace(/\?p=\d+/i, "");
                  if (url.indexOf("?") != -1) {
                      url = url + '&p=' + data.currentPage;
                  }
                  else {
                      url = url + '?p=' + data.currentPage;
                  }
                  window.open(url,"_self")
              }
            });
        });
    </script>
  
@endsection


