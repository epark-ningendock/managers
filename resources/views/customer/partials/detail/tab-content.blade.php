@if ( isset($customer_detail) )
    <input type="hidden" id="customer-id" value="{{ $customer_detail->id }}">
    @includeIf('customer.partials.detail.basic-information')
    @includeIf('customer.partials.detail.accepted-guidance-history')
    @if(isset($name_identifications) )
      @includeIf('customer.partials.detail.name-identification')
     @endif
@endif