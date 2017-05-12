
@if (request()->session()->get('success'))
<div class="alert alert-success">
  {!! request()->session()->get('success') !!}
</div>
@endif