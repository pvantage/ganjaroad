<div class="block">
	<h3 class="block-title">@lang('front/general.links')</h3>
</div>
<div class="content">
	<dl>
		@if (Sentinel::getUser()->hasAccess(['create.nearme']))
			<dt><dd><a href="{{ URL::to('mynearme') }}">@lang('front/general.my_nearme')</a></dd></dt>
			<dt><dd><a href="{{ URL::to('newnearme') }}">@lang('front/general.add_nearme')</a></dd></dt>
		@endif

		@if (Sentinel::getUser()->hasAccess(['create.classified']))
			<dt><dd><a href="{{ URL::to('myclassifieds') }}">@lang('front/general.my_classifieds')</a></dd></dt>
			<dt><dd><a href="{{ URL::to('newclassified') }}">@lang('front/general.add_classified')</a></dd></dt>
		@endif

		@if (Sentinel::getUser()->hasAccess(['create.ads']))
			<dt><dd><a href="{{ URL::to('myads') }}">@lang('front/general.myads')</a></dd></dt>
			<dt><dd><a href="{{ URL::to('newad') }}">@lang('front/general.add_new_add')</a></dd></dt>
		@endif

		@if (Sentinel::getUser()->hasAnyAccess(['create.nearme', 'create.classified', 'create.ads']))
			<dt><dd><a href="{{ URL::to('transactions') }}">@lang('front/general.transactions')</a></dd></dt>
		@endif

		<dt><dd><a href="{{ URL::to('update/vault') }}">Update Payment Card</a></dd></dt>
		<dt><dd><a href="{{ URL::to('my-account') }}">@lang('front/general.settings')</a></dd></dt>
		<dt><dd><a href="{{ URL::to('logout') }}">@lang('front/general.logout')</a></dd></dt>
	</dl>
</div>
