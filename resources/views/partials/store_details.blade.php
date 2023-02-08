<div class="modal" id="store_details">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header head-model">
                <h5 class="modal-title pb-0" id="exampleModalLabel">Update Store Details</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            @if(!Auth::user() || Auth::user()->is_approved == 0)
            <div class="modal-body detailsUpdate">
                <form method="patch" action="{{ route('frontend.update.store.info') }}" id="updateStore">
					  @csrf
                    <div id="message-danger-store" style="display: none;"></div>
                    <div id="message-success-store" style="display: none;"></div>
					<div class="form-group required">
                        <label for="store_name">Store name</label>
                        {{ Form::text('store_name', old('store_name'), ['class' => 'form-control', 'placeholder' => 'Store Name']) }}
                    </div>
					<div class="form-group required">
                        <label for="title">Description</label>
                        {{ Form::textarea('description', old('description'), ['class' => 'form-control textarea-resize','placeholder' => 'Description', 'rows' => 4]) }}
                    </div>
					
					<div class="col-sm-12 text-center mt-4">
                        <button type="submit" class="greebtn align-items-center text-uppercase font-weight-bold ">Update Detail</button>
                    </div>
                    <div class="overlay-block loader hide">
                        <div class="overlay-block-inr">
                            <div class="loader-block text-center dis-block clearfix">
                                <div class="ldr-img"><img src="{{ asset('img/loader.gif') }}">Loading...</div>
                                <!-- <p>Please allow a few moments for data to load! </p> -->
                            </div>
                        </div>
                    </div>
				</form>
            </div>
            @else
            <div class="modal-body detailsUpdate">
            	<div id="message-success-store">
            		<div class="alert alert-success">
            			Store detail is updated Wait for approval from admin.
            		</div>
            	</div>
            </div>
            @endif
        </div>
    </div>
</div>
@push('scripts')

<script>
$(document).ready(function () {
    // jQuery.validator.addMethod("alphanumeric", function(value, element) {
    //     return this.optional(element) || /^\w+$/i.test(value);
    // }, "Special characters not allowed");
    jQuery.validator.addMethod("alphanumeric", function( value, element ) {
        var regex = new RegExp("^[a-zA-Z 0-9]+$");
        var key = value;
        if (!regex.test(key)) {
           return false;
        }
        return true;
    }, "please use only alphanumeric or alphabetic characters");
    $.validator.addMethod("alpha", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
    }, "Please enter valid title.");
	$('#updateStore').validate({
            rules: {
                store_name: {
                    required: true,
                    alpha: true,
                    minlength: 5
                },
                description : {
                	required: true,
                    alpha: true,
                	minlength: 10
                },
            },
            messages: {
                store_name: {
                    required: "The store name field is required.",
                },
                description : {
                    required: "The store description field is required.",
                }
            },
            errorElement: 'div',
            errorClass: 'help-block',
            highlight: function (element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('text-danger');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".text-danger").removeClass('text-danger');
            },
            submitHandler: function(form) {
                $('#message-danger-store').hide();
                $('#message-success-store').hide();
			    $.ajax({
			        url: "{{ route('frontend.update.store.info') }}",
			        type: "patch",
			        data: $(form).serialize(),
			        headers: {
				        'X-XSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
                    beforeSend: function ( xhr ) {
                       $('.loader').removeClass('hide');
                    },
			        success: function(response) {
			        	console.log(response);
			        	$('#message-danger-store').html('');
                        $('#message-success-store').html('');
                        $('#message-danger-store').show();
                        $('#message-success-store').show();
			        	if(response.status == 'success'){
                            $('#message-success-store').html('<div class="alert alert-success">'+response.message+'</div>');
                            setTimeout(function(){ 
                                window.location.href = response.target_url;
                             }, 3000);
			        	}else{
                            $('#message-danger-store').html('<div class="alert alert-danger">'+response.message+'</div>');
			        	}
			        },
                    error: function(error){
                        $('#message-danger-store').html('<div class="alert alert-danger">'+error+'</div>');
                    },
                    complete: function (data) {
                        // $('#message-danger-store').hide();
                        // $('#message-success-store').hide();
                        $('.loader').addClass('hide');
                    }
			    });
			}
        })
});
</script>
@endpush