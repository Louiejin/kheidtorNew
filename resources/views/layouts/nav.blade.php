    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/"><img src="/images/kh.png" class="header_logo"/></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <!-- 
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
            -->
          </ul>
          <ul class="nav navbar-nav navbar-right">
            @if(Auth::check())
            @if (auth()->user()->admin || auth()->user()->publish || auth()->user()->edit)
            <li class="{{ Request::segment(1) === 'home' || Request::segment(1) === 'article' || Request::segment(1) === 'articles' ? 'active' : null }}">
              <a href="/articles">Articles</a>
            </li>
            @endif
            @if (auth()->user()->admin)
            <li class="{{ (Request::segment(1) === 'user' && Request::segment(2) != 'me') || Request::segment(1) === 'users' ? 'active' : null }}">
              <a href="/users">Users</a>
            </li>
            @endif
            @if (auth()->user()->admin || auth()->user()->managedb)
            <li class="{{ Request::segment(1) === 'database' || Request::segment(1) === 'databases' ? 'active' : null }}">
              <a href="/databases">Database</a>
            </li>
            @endif
            <li class="dropdown {{ (Request::segment(1) === 'user' && Request::segment(2) === 'me') ? 'active' : null }}">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Account <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="/user/me/edit">Edit Profile</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="/logout">Logout</a></li>
              </ul>
            </li>
            @else
            <li><a href="/login">Login</a></li>
            @endif
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>