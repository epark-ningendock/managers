@if($paginator->isNotEmpty())
  <p class="mt-2">
    {{ ( ($paginator->currentPage() - 1) * $paginator->perPage() ) + 1 }} ~ {{ $paginator->currentPage() == $paginator->lastPage() ? $paginator->total() : ($paginator->currentPage() * $paginator->perPage()) }}件 / {{ $paginator->total() }} 件
  </p>
@endif