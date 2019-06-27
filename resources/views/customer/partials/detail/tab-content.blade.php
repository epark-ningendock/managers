@if ( isset($customer_detail) )
    @includeIf('customer.partials.detail.basic-information')
    @includeIf('customer.partials.detail.accepted-guidance-history')
    @includeIf('customer.partials.detail.name-identification')
@endif