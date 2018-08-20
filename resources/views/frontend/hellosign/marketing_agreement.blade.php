@if($sign_url)
    <div id="hs-container"></div>
    <script type="text/javascript">
        HelloSign.init("{{ env('HELLOSIGN_CLIENT_KEY') }}");
        HelloSign.open({
            // Set the sign_url passed from the controller.
            url: "{{ $sign_url }}",
            allowCancel: false,
            redirectUrl: '{{ route('getsignagreement') }}',
            //skipDomainVerification: true,
            //height: 800,
            // Set the debug mode based on the test mode toggle.
            debug: {{ (env('HELLOSIGN_TEST_MODE') == 1 ? "true" : "false") }},
            // Point at the div we added in the content section.
            container: document.getElementById("hs-container"),
            // Define a callback for processing events.
            messageListener: function(e) {
                if (e.event == 'signature_request_signed') {
                    //window.location = "{!! route('payment') !!}";
                }
            }
        });
    </script>
@else
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="agree_modal_title">@lang('general.notice')</h4>
    </div>
    <div class="modal-body">
         <p>@lang('front/review.already_signed')</p>
		 @if($notice)
			<p>{{ $notice }}</p>
		 @endif
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('general.close')</button>
    </div>
@endif
