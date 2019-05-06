@push('js')

    <script type="text/javascript">
        (function($){
            $('#contract-form').submit(function(e){
                e.preventDefault();

                var form = $(this);



                form.find('.form-group').removeClass('has-error');
                form.find('.help-block').fadeOut();

                $.ajax({
                    url: $(this).attr('action'),
                    method: "POST",
                    data: $(this).serialize(),
                    cache : false,
                    processData: false,
                    success: function (response) {

                        form[0].reset();
                        $(".modal").animate({scrollTop: form.offset().top});
                        $('.contract-form-alert').fadeIn().delay(1500).fadeOut(function(){
                            $("a[href='#basic-information']").click();
                        });

                    },
                    error: function (data, textStatus, errorThrown) {

                        if ( data.responseJSON.errors ) {

                            $.each(data.responseJSON.errors, function(key, value){
                                var form_group = $('#' + key).closest('.form-group');

                                $('#' + key).closest('.form-group').addClass('has-error');
                                if ( form_group.find('.help-block').length > 0 ){
                                    form_group.find('.help-block').html(value[0]).fadeIn();
                                } else {
                                    $('<span class="help-block">' + value[0] + '</span>').insertAfter(form_group.find('input'));
                                }

                            });

                        }


                    },
                });

            });
        })(jQuery);
    </script>

@endpush

<div class="contract-form-alert">
    @include('commons.alert', $alert = [
        'type' => 'success',
        'message' => trans('messages.created', ['name' => trans('messages.names.contractor')])
    ])
</div>

<form id="contract-form" class="form-horizontal" action="{{ route('contract.store') }}">

    {{ csrf_field() }}

    <h5 class="sm-title">既存の利用規約情報をご利用ください</h5>

    <div class="form-group">
        <label for="medical_institution_name" class="col-md-4">医療機関ID *医療機関名*ポリシー所有者名</label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="medical_institution_name" name="medical_institution_name" />
        </div>
    </div>

    <hr/>

    <h5 class="sm-title">契約情報</h5>

    <div class="form-group">
        <label for="contractor_name_kana" class="col-md-4">契約者名（フリガナ）</label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="contractor_name_kana" name="contractor_name_kana" />
        </div>
    </div>

    <div class="form-group">
        <label for="contractor_name" class="col-md-4">契約者名</label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="contractor_name" name="contractor_name" />
        </div>
    </div>

    <div class="form-group">
        <label for="application_date" class="col-md-4">申込日</label>
        <div class="col-md-8">
            <input type="date" class="form-control" id="application_date" name="application_date" />
        </div>
    </div>

    <div class="form-group">
        <label for="billing_start_date" class="col-md-4">課金開始日</label>
        <div class="col-md-8">
            <input type="date" class="form-control" id="billing_start_date" name="billing_start_date" />
        </div>
    </div>

    <div class="form-group">
        <label for="cancellation_date" class="col-md-4">解約日</label>
        <div class="col-md-8">
            <input type="date" class="form-control" id="cancellation_date" name="cancellation_date"  />
        </div>
    </div>


    <h5 class="sm-title">契約者情報</h5>


    <div class="form-group">
        <label for="representative_name_kana" class="col-md-4">代表者名（フリガナ）</label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="representative_name_kana" name="representative_name_kana" />
        </div>
    </div>


    <div class="form-group">
        <label for="representative_name" class="col-md-4">代表者名</label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="representative_name" name="representative_name" />
        </div>
    </div>


    <div class="form-group">
        <label for="postcode" class="col-md-4">郵便番号</label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="postcode" name="postcode" />
        </div>
    </div>

    <div class="form-group">
        <label for="address" class="col-md-4">住所</label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="address" name="address"/>
        </div>
    </div>

    <div class="form-group">
        <label for="tel" class="col-md-4">電話番号</label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="tel" name="tel" />
        </div>
    </div>


    <div class="form-group">
        <label for="fax" class="col-md-4">FAX番号</label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="fax" name="fax" />
        </div>
    </div>

    <div class="form-group">
        <label for="email" class="col-md-4">メールアドレス</label>
        <div class="col-md-8">
            <input type="email" class="form-control" id="email" name="email" />
        </div>
    </div>

    <h5 class="sm-title">代表アカウント情報</h5>

    <div class="form-group">
        <label for="login" class="col-md-4">ログインID</label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="login" name="login" />
        </div>
    </div>

    <div class="form-group">
        <label for="password" class="col-md-4">パスワード</label>
        <div class="col-md-8">
            <input type="password" class="form-control" id="password" name="password" />
        </div>
    </div>

    <h5 class="sm-title">ドックネットID</h5>
    <div class="form-group">
        <label for="old_karada_dog_id" class="col-md-4">からだドックID</label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="old_karada_dog_id" name="old_karada_dog_id" />
        </div>
    </div>

    <div class="form-group">
        <label for="karada_dog_id" class="col-md-4">からだドック医療機関ID</label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="karada_dog_id" name="karada_dog_id">
        </div>
    </div>

    <div class="text-right">
        <button type="submit" class="btn btn-primary">登録</button>
    </div>

</form>