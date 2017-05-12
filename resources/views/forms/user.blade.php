    <div class="row">
      <div class="col-md-6">
        <h4>User Information</h4>
      
        <div class="form-group">
          <label for="fullname">Full Name</label>
          <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name" required 
          @if (empty($user))
            value="{{ request()->old('fullname') }}"
          @else
            value="{{ $user->fullname }}"
          @endif
          >
        </div>
    
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" required 
          @if (empty($user))
            value="{{ request()->old('email') }}"
          @else
            value="{{ $user->email }}"
          @endif
          >
        </div>
      
        <!-- <h4>Login Details</h4> -->
        
        <div class="form-group">
          <label for="password">Username</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required 
          @if (empty($user))
            value="{{ request()->old('username') }}"
          @else
            value="{{ $user->username }}"
          @endif
          >
        </div>
        
        @if (empty($user))
        <div class="form-group">
          <label for="password">
          Password
          </label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required
          >
        </div>
        @endif
      </div>
        
      <div class="col-md-6">
        @if (auth()->user()->admin)
        <h4>Authorization</h4>
        <div class="form-group"><br/></div>
        <div class="checkbox">
          <div class="form-group">
            <label><input type="checkbox" name="admin"
            @if (!empty($user) && $user->admin)
            checked
            @endif
            >KHEditor Admin</label>
          </div>
          <div class="form-group">
            <label><input type="checkbox" name="publish"
            @if (!empty($user) && $user->publish)
            checked
            @endif
            >Publish Articles</label>
          </div>
          <div class="form-group">
            <label><input type="checkbox" name="edit"
            @if (!empty($user) && $user->edit)
            checked
            @endif
            >Edit Articles</label>
          </div>
          <div class="form-group">
            <label><input type="checkbox" name="managedb"
            @if (!empty($user) && $user->managedb)
            checked
            @endif
            >Manage Database</label>
          </div>
        </div>
        @endif
        
      </div>
    </div>