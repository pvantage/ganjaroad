@if($menu = TemplateHelper::getMenu())
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#ganjaroad_menu" aria-expanded="false">
					<span class="sr-only">@lang('front/general.toggle_navigation')</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>

			<div class="collapse navbar-collapse" id="ganjaroad_menu">
				<ul class="nav navbar-nav">
					{!! TemplateHelper::renderMenu() !!}
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container -->
	</nav>
@endif