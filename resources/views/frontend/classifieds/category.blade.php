@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@lang('front/general.classifieds')@if(isset($category)) | {{ $category->title }}@endif
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css starts-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/bootstrap-treeview/css/bootstrap-treeview.css') }}">
	
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/frontend/classifieds.css') }}">
    <!--end of page level css-->
@stop

@section('breadcrumbs')
	<ol class="breadcrumb" style="margin-bottom: 5px;">
		<li><a href="{{ URL::to('/') }}">@lang('front/general.home')</a></li>
		@if(isset($category))
			<li><a href="{{ URL::to('classifieds') }}">@lang('front/general.classifieds')</a></li>
			<li class="active">{{ $category->title }}</li>
		@else
			<li class="active">@lang('front/general.classifieds')</li>
		@endif
	</ol>               
@stop

{{-- Page content --}}
@section('content')
    <!-- Container Section Strat -->
	<div class="wrapper">
		<hr>
		<div class="welcome">
			<h1>@if(isset($category)){{ $category->title }}@else @lang('front/general.classifieds') @endif</h1>
		</div>
		<hr>
		<div class="content">
            <?php /*
			@if(!isset($category))
				<div class="change-language">
					{!! Form::open(array('url' => URL::to('classifiedscountry'), 'method' => 'get', 'class' => 'bf', 'files'=> false)) !!}
						<div class="col-md-3">
							<label class="label" for="classifiedscountry">@lang('front/general.classifieds_change_location')</label>
						</div>
						<div class="col-md-5">
							{!! Form::select('country', TemplateHelper::getCountriesList(false), null, array('class' => 'form-control select2', 'id' => 'country', 'required' => 'required', 'placeholder'=>trans('nearme/form.country'))) !!}
						</div>
						<div class="col-md-4 pull-left">
							<button class="btn classifiedscountry-search-btn" type="submit"><img src="{{ asset('assets/images/ico-search.png') }}" alt=""><span class="sr-only">@lang('front/general.search_near_me_placeholder')</span></button>
						</div>
					{!! Form::close() !!}
				</div>
			@endif
			*/ ?>
			<div class="row">
				@foreach($categories as $sub)
					<div class="col-md-12">
						<ul class="tree">
							<li>
								<a href="{!! TemplateHelper::classifiedsCategoryLink($sub->id, $sub->slug) !!}">{{ $sub->title }}</a>
								@if(isset($sub['childrens']))
									<ul>
										{!! TemplateHelper::generateClassifiedsCategories($sub['childrens']) !!}
									</ul>
								@endif
							</li>
						</ul>
					</div>
				@endforeach
			</div>
		</div>
	</div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/bootstrap-treeview/js/bootstrap-treeview.js') }}" type="text/javascript"></script>

<script type="text/javascript">
jQuery( document ).ready(function() {
	jQuery("#country").select2({
		placeholder: "@lang('nearme/form.select_country')",
		theme: 'bootstrap',
		allowClear: false,
		formatResult: format,
		formatSelection: format,
		width: 'resolve',
		templateResult: format,
		escapeMarkup: function (m) {
			return m;
		},
	});
	//Initialization of treeviews
	jQuery('.tree').treed({openedClass:'glyphicon-folder-open', closedClass:'glyphicon-folder-close'});
});

jQuery.fn.extend({
	treed: function (o) {
      
		var openedClass = 'glyphicon-folder-open';
		var closedClass = 'glyphicon-folder-close';
      
		if (typeof o != 'undefined'){
			if (typeof o.openedClass != 'undefined'){
				openedClass = o.openedClass;
			}
			if (typeof o.closedClass != 'undefined'){
				closedClass = o.closedClass;
			}
		};
      
		//initialize each of the top levels
		var tree = jQuery(this);
		tree.addClass("tree");
		tree.find('li').has("ul").each(function () {
			var branch = jQuery(this); //li with children ul
			branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
			branch.addClass('branch');
			branch.on('click', function (e) {
				if (this == e.target) {
					var icon = jQuery(this).children('i:first');
					icon.toggleClass(openedClass + " " + closedClass);
					jQuery(this).children().children().toggle();
				}
			})
			@if(isset($category))

			@else
				branch.children().children().toggle(); //close all
			@endif
        });
        
		//fire event from the dynamically added icon
		tree.find('.branch .indicator').each(function(){
			jQuery(this).on('click', function () {
				jQuery(this).closest('li').click();
			});
		});
        
		//fire event to open branch if the li contains an anchor instead of text
		tree.find('.branch>a').each(function () {
			jQuery(this).on('click', function (e) {
				jQuery(this).closest('li').click();
				e.preventDefault();
			});
		});
        
		//fire event to open branch if the li contains a button instead of text
		tree.find('.branch > button').each(function () {
			jQuery(this).on('click', function (e) {
				jQuery(this).closest('li').click();
				e.preventDefault();
			});
		});
    }
});

function format(state) {
    if (!state.id) return state.text; // optgroup
    return "<img class='flag' src='{{ asset('assets/images/countries_flags') }}/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
}
</script>
@stop
