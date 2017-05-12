
<!DOCTYPE html>
<html lang="en">

  @include('layouts.head')

  <body>
    <div id="loadingDiv"></div>
    @include('layouts.nav')
    
    <div class="container">
    
    @yield('title')
    
    @yield('content')
    </div>
    
    @include('layouts.footer')
  </body>
</html>

