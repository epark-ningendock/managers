<table class="table">
    <tr>
        <td>
            <h3>顧客一覧</h3>
        </td>
    </tr>
    <tr>
        <td>顧客ID</td>
        <td>名前</td>
        <td>電話番号</td>
        <td>診察券番号</td>
    </tr>
    @foreach($customers as $customer)

        <tr class="customer-row" 
        data-id="{{  $customer->id }}"
        data-family_name="{{  $customer->family_name }}"
        data-first_name="{{  $customer->first_name }}"
        data-family_name_kana="{{  $customer->family_name_kana }}"
        data-first_name_kana="{{  $customer->first_name_kana }}"
        data-tel="{{  $customer->tel }}"
        data-registration_card_number="{{  $customer->registration_card_number }}"
        >
            <td>{{ $customer->id }}</td>
            <td>{{ $customer->family_name . $customer->first_name }}</td>
            <td>{{ $customer->tel }}</td>
            <td>{{ $customer->registration_card_number }}</td>
            <td><button type="button" class="btn btn-primary">選択</button></td>
        </tr>
    @endforeach
    {{--            {{ $customer->links() }}--}}
</table>