<div class="modal fade customer-search-box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">受診者検索</h4>
      </div>
      <div class="modal-body">
        <form id="customer-searchform" method="post" action="{{  route('customer.search') }}">

        	{{ csrf_field()  }}

        	<div class="row">
        		
    				<div class="col-sm-9">
    					
    					<div class="form-group">
    						<input type="text" class="form-control" name="search_text" id="registration_card_number_search">
    					</div>

    				</div>

    				<div class="col-sm-3">
    					
    					<button type="submit" class="btn btn-primary">{{  trans('messages.search') }}</button>

    				</div>

        	</div>

        </form>

        <div class="customer-list">
          
        </div>

      </div>

    </div>
  </div>
</div>


@push('js')

<script type="text/javascript">
	(function($){
		/* ---------------------------------------------------
		Show Customer Search Modal Box
		-----------------------------------------------------*/
		$(document).on('click', '#examinee-information', function(){

			$('.customer-search-box').modal('show');

			return false;
		});

    $('.customer-search-box').on('hidden.bs.modal', function (e) {
      $('.customer-list').html('');
      $('#customer-searchform').find('input:text').val('');
    });    


		/* ---------------------------------------------------
		Search Customer Through Ajax
		-----------------------------------------------------*/
    $(document).on('submit', '#customer-searchform', function(event){
    	event.preventDefault();

    	let thisForm = $(this);

        $.ajax({
            url: thisForm.attr('action'),
            method: "POST",
            data: thisForm.serialize(),
            success: function (response) {
                $('.customer-list').html(response.data);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                 alert("Customer Search Ajax Error");
            }                    
        });
        
    });		

    /* ---------------------------------------------------
    Selected Customer Id to hidden input selected_customer fields
    -----------------------------------------------------*/
    $(document).on('click', '.customer-row', function(){
        let $this = $(this);
        $('#customer_id').val($this.attr('data-id'));
        $('#family_name').val($this.attr('data-family_name'));
        $('#first_name').val($this.attr('data-first_name'));
        $('#family_name_kana').val($this.attr('data-family_name_kana'));
        $('#first_name_kana').val($this.attr('data-first_name_kana'));
        $('#tel').val($this.attr('data-tel'));
				$('input[name="sex"]').each(function(){
					$(this).attr('checked', ($(this).val() === $this.attr('data-sex')));
				});
        $('#birthday').val($this.attr('data-birthday'));
        $('#postcode1').val($this.attr('data-postcode1'));
        $('#postcode2').val($this.attr('data-postcode2'));
        $('#prefecture_id').val($this.attr('data-prefecture_id'));
        $('#address1').val($this.attr('data-address1'));
        $('#address2').val($this.attr('data-address2'));
        $('#email').val($this.attr('data-email'));
        $('#memo').val($this.attr('data-memo'));
        $('#registration_card_number').val($this.attr('data-registration_card_number'));

        $('.customer-search-box').modal('hide');
    });
    
		
		
	})(jQuery);
</script>

@endpush