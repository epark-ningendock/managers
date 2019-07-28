@if($paginator->isNotEmpty())
  <p class="mt-2">
    全{{ $paginator->total() }} 件中 {{ ( ($paginator->currentPage() - 1) * $paginator->perPage() ) + 1 }}件 ~ {{ $paginator->currentPage() == $paginator->lastPage() ? $paginator->total() : ($paginator->currentPage() * $paginator->perPage()) }} 件を表示
  </p>
@endif