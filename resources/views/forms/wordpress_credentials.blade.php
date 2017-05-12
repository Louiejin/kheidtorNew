    <div class="row">
      <div class="col-md-6">
        <h4>Update Wordpress Credentials</h4>
      
        @if($user->wp_username)
          <p><i>Your account is linked to <b>{{ $user->wp_username }}</b></i></p>
        @else
          <p>There is no wordpress account linked</p>
        @endif
      
      
        <div class="form-group">
          <label for="wp_username">
          Wordpress Username
          </label>
          <input type="text" class="form-control" id="wp_username" name="wp_username" placeholder="Enter Wordpress Username" autocomplete="off" required
          @if (empty($user))
            value="{{ request()->old('wp_username') }}"
          @else
            value="{{ $user->wp_username }}"
          @endif
          >
        </div>
        <div class="form-group">
          <label for="wp_password">
          Wordpress Password
          </label>
          <input type="password" class="form-control" id="wp_password" name="wp_password" placeholder="Enter Wordpress Password" autocomplete="off" required
          @if (empty($user))
            value="{{ request()->old('wp_password') }}"
          @else
            value="{{ $user->wp_password }}"
          @endif
          >
        </div>
      </div>
      <div class="col-md-6">

      </div>
    </div>