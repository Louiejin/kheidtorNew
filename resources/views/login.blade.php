<!DOCTYPE html>
<html lang="en">
  @include('layouts.head')
  <body>
  <img style="margin:0px auto;display:block" src="/images/kh.png" alt="KH logo icon" width="80" height="80">
<h2><center>Welcome to KanjiHybrid</center></h2>    
<div class="container">
      <div class="row">
      <div class="col-md-offset-4 col-md-4">
      <h3>Login</h3>
        @include('layouts.errors')
        <form method="POST" action="/login">
          
          {{ csrf_field() }}
          
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Login</button>
          </div>          
        </form>
      </div>
      </div>
    </div> <!-- /container -->
  </body>
</html>

