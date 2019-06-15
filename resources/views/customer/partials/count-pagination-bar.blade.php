@if ( isset($customers) && count($customers) > 0 )

<div class="count-paginate-bar">

    <div class="row">

        <div class="col-sm-6">

            <div class="display-total text-left mr-5 ">
                {{ $customers->total() }} の行
                {{ ( $customers->currentPage() * $customers->perPage() ) - $customers->perPage() }}
                - {{ $customers->currentPage() * $customers->perPage() }} 結果
            </div>

        </div>

        <div class="col-sm-6">

            <div class="top-nav-wrapper text-right">
                {{ $customers->appends(request()->input())->links() }}
            </div>

        </div>


    </div>

</div>
@endif