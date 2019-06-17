<div class="action-btn-bar text-right mb-5">

    <a href="{{ route('customer.create') }}" class="btn btn-primary">{{ trans('messages.create_new') }}</a>
    {{-- <span class="text-bold">{{ trans('messages.bulk_registration') }}</span>
    <form method="post" action="{{ route('customer.import.data') }}" class="top-action-bar-file-with-file" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="input-file">
            <input type="file" name="file_selection" id="file_selection">
            <label for="file_selection">{{ trans('messages.file_selection') }}</label>
        </div>
        <button type="submit" class="btn btn-default btn-sm" style="margin-top: -16px;">{{ trans('messages.upload') }}</button>
    </form> --}}
</div>