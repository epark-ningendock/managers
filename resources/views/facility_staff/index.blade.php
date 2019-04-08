@extends('adminlte::page')

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
    <h1>医療機関スタッフ管理</h1>
@stop

<!-- ページの内容を入力 -->
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <a class="btn btn-success" href="{{ url('/facility-staff/create') }}">新規作成</a>
                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        {{ session('status') }}
                    </div>
                    <br/>
                @endif


                <div class="box-body">
                    <table id="example2" class="table table-bordered table-hover mb-5 mt-5">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>スタッフ施設名</th>
                            <th>ログインID</th>
                            <th>編集</th>
                            <th>削除</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($facility_staffs as $facility_staff)
                            <tr>
                                <td>{{ $facility_staff->id }}</td>
                                <td>{{ $facility_staff->name }}</td>
                                <td>{{ $facility_staff->login_id }}</td>
                                <td><a href="{{ route('facility-staff.edit', $facility_staff->id) }}"
                                       class="btn btn-primary">編集</a></td>
                                <td>
                                    <a href="#" class="btn btn-danger delete-btn" data-id="{{ $facility_staff->id }}">削除</a></td>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                    </table>

                    {{ $facility_staffs->links() }}


                </div>
            </div>
        </div>
    </div>

    <form class="hide" method="POST"
          action="{{ route('facility-staff.destroy', ':id') }}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
    </form>

@stop


<!-- 読み込ませるJSを入力 -->
@section('js')
    <script>
        $(document).ready(function() {
            $('.delete-btn').click(function() {
                const id = $(this).data('id');
                Modal.showConfirm('Do you want to delete this data?', function() {
                    submitDeleteForm(id);
                });
            });
        });

        function submitDeleteForm(id) {
            const action = $('#delete-form').attr('action').replace(':id', id);
            $('#delete-form').attr('action', action);
            $('#delete-form').submit();
        }
    </script>
@stop