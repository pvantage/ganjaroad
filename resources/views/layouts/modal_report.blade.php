<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title" id="user_delete_confirm_title">@lang($model.'/modal.report_title')</h4>
</div>

{!! Form::model($classified, array('url' => route('classified/report', ['classified' => $classified->id]), 'method' => 'post', 'id' => 'report_form', 'class' => 'bf', 'files'=> false)) !!}
    <div class="modal-body">
        @if($error)
            <div>{!! $error !!}</div>
        @else
            @lang($model.'/modal.report_body')
            <textarea name="reason" class="form-control" rows="5" required="required"></textarea>
        @endif
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang($model.'/modal.cancel')</button>
      @if(!$error)
        <button type="submit" class="btn btn-danger">@lang($model.'/modal.report')</button>
      @endif
    </div>
{!! Form::close() !!}