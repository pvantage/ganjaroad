<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="user_delete_confirm_title">@lang($model.'/modal.title')</h4>
</div>
<div class="modal-body">
	@lang($model.'/modal.body')
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">@lang('general.cancel')</button>
    <a href="javascript:void(0)" onclick="deleteItem()" type="button" class="btn btn-danger">@lang('general.confirm')</a>
</div>
