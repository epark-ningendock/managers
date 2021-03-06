@foreach (['error', 'warning'] as $key)
  @if(session($key))
    <div class="alert alert-{{ $key == 'error' ? 'danger' : $key }} alert-block">
      <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
      <strong class="white-space">{{ session($key) }}</strong>
    </div>
  @endif
@endforeach

<style>
.white-space {
  white-space: pre-line
}
</style>