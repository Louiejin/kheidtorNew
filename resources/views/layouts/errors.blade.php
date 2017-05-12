@if (count($errors))
<div class="alert alert-danger">
  @foreach ($errors->all() as $error) 
    <li>{{ $error }} </li>
  @endforeach
</div>
@elseif (request()->session()->get('m_errors'))
<div class="alert alert-danger">
  <ul>
  @foreach (request()->session()->get('m_errors') as $error)
    <li>{!! $error !!} </li>
  @endforeach
  </ul>
</div>
@endif