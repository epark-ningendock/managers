@if ($errors->any())
  <div class="alert alert-danger">
    <p>以下の項目でエラーが発生しました。</p>
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif