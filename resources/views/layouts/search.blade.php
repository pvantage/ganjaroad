<div class="row">
	<div class="col-sm-12">
		<div class="block-search">
			{!! Form::open(array('url' => URL::to('search'), 'method' => 'get', 'class' => '', 'files'=> false)) !!}
				<label class="sr-only" for="search">@lang('front/general.search')</label>
				<input class="form-control" name="query" type="text" @if(isset($search_query)) value="{{ $search_query }}" @endif placeholder="@lang('front/general.search_placeholder')">
				<button class="btn btn-success-outline" type="submit"><img src="{{ asset('assets/images/ico-search.png') }}" alt=""><span class="sr-only">@lang('front/general.search')</span></button>
			{!! Form::close() !!}
		</div>
	</div>
</div>