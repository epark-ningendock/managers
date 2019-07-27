<div class="modal fade ajax-data-popup" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: none;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="std-tab-wrapper nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#basic-information" data-toggle="tab">{{ trans('messages.basic_information') }}</a></li>
                        <li><a href="#accepted-guidance-history" data-toggle="tab">{{ trans('messages.accepted_guidance_history') }}</a></li>
                        <li><a href="##name-identification" data-toggle="tab">{{ trans('messages.name_identification') }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="modal-body-wrapper tab-content">

            </div>

        </div>
    </div>
</div>
@includeIf('customer.partials.detail.detail-popup-listing-paginator-script')