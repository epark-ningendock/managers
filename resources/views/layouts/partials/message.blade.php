@foreach (['error', 'warning', 'success', 'info'] as $key)
  @if(session($key))
    <div class="alert alert-{{ $key == 'error' ? 'danger' : $key }} alert-block alert-dismissible">
      <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
      <strong>{{ session($key) }}</strong>
    </div>
  @endif
@endforeach