

<div class="modal fade ajax-data-popup" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="std-tab-wrapper">
                    <a class="btn btn-default" href="#basic-information" data-toggle="tab">{{ trans('messages.basic_information') }}</a>
                    <a class="btn btn-default" href="#accepted-guidance-history" data-toggle="tab">{{ trans('messages.accepted_guidance_history') }}</a>
                    <a class="btn btn-default" href="#name-identification" data-toggle="tab">{{ trans('messages.name_identification') }}</a>
                </div>
            </div>

            <div class="modal-body-wrapper tab-content">

            </div>

        </div>
    </div>
</div>
@includeIf('customer.partials.detail.detail-popup-listing-paginator-script')