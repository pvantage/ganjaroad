@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    Add User
    @parent
    @stop

    {{-- page level styles --}}
    @section('header_styles')
            <!--page level css -->
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/pages/wizard.css') }}" rel="stylesheet">
	
	<link href="{{ asset('assets/css/groups/style.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/elements/checkbox.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css') }}" rel="stylesheet" type="text/css">
    <!--end of page level css-->
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>@lang('users/form.add_new_user')</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    @lang('users/form.dashboard')
                </a>
            </li>
            <li><a href="#"> @lang('users/form.users')</a></li>
			<li class="active">@lang('users/form.add_new_user')</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            @lang('users/form.add_new_user')
                        </h3>
                                <span class="pull-right clickable">
                                    <i class="glyphicon glyphicon-chevron-up"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <!-- errors -->
                        <div class="has-error">
                            @if($errors->has())
                                @foreach ($errors->all() as $error)
                                     <span class="help-block">{{ $error }}</span>
                               @endforeach
                             @endif
                        </div>
                        <!--main content-->
                        <form id="commentForm" action="{{ route('admin.users.create') }}"
                              method="POST" enctype="multipart/form-data" class="form-horizontal">
                            <!-- CSRF Token -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                            <div id="rootwizard">
                                <ul>
									<li><a href="#tab1" data-toggle="tab">@lang('users/form.user_profile')</a></li>
									<li><a href="#tab2" data-toggle="tab">@lang('users/form.bio')</a></li>
									<li><a href="#tab3" data-toggle="tab">@lang('users/form.address')</a></li>
									<li><a href="#tab4" data-toggle="tab">@lang('users/form.user_permissions')</a></li>
								</ul>
                                <div class="tab-content">
									<div class="tab-pane" id="tab1">
										<h2 class="hidden">&nbsp;</h2>

										<div class="form-group">
											<label for="first_name" class="col-sm-2 control-label">@lang('users/form.user_first_name') *</label>
											<div class="col-sm-10">
												<input id="first_name" name="first_name" type="text"
													placeholder="@lang('users/form.user_first_name')" class="form-control required"
													value="{!! old('first_name') !!}"/>
											</div>
										</div>

										<div class="form-group">
											<label for="last_name" class="col-sm-2 control-label">@lang('users/form.user_last_name') *</label>
											<div class="col-sm-10">
												<input id="last_name" name="last_name" type="text" placeholder="@lang('users/form.user_last_name')"
													class="form-control required"
													value="{!! old('last_name') !!}"/>
											</div>
										</div>

										<div class="form-group">
											<label for="email" class="col-sm-2 control-label">@lang('users/form.user_email') *</label>
											<div class="col-sm-10">
												<input id="email" name="email" placeholder="@lang('users/form.user_email')" type="text"
													class="form-control required email"
													value="{!! old('email') !!}"/>
											</div>
										</div>

										<div class="form-group">
											<label for="password" class="col-sm-2 control-label">@lang('users/form.user_password') *</label>
											<div class="col-sm-10">
												<input id="password" name="password" type="password" placeholder="@lang('users/form.user_password')"
													class="form-control" value="{!! old('password') !!}"/>
											</div>
										</div>

										<div class="form-group">
											<label for="password_confirm" class="col-sm-2 control-label">@lang('users/form.user_confirm_password') *</label>
											<div class="col-sm-10">
												<input id="password_confirm" name="password_confirm" type="password"
													placeholder="@lang('users/form.user_confirm_password') " class="form-control"
													value="{!! old('password_confirm') !!}"/>
											</div>
										</div>
										
										<div class="user-group-wrapper">
											<p class="text-danger"><strong>@lang('users/form.permissions_info')</strong></p>
											<div class="form-group">
												<label for="group" class="col-sm-2 control-label">@lang('users/form.groups') *</label>
												<div class="col-sm-10">
													<select class="multiselect" multiple="multiple" title="@lang('users/form.select_group')" name="groups[]" id="groups" required>
														<option value="">@lang('users/form.select')</option>
														@foreach($groups as $group)
															<option value="{{ $group->id }}" @if($group->id == old('group')) selected="selected" @endif >
																{{ $group->name}}
															</option>
														@endforeach                                                       
													</select>
												</div>
											</div>

											<div class="form-group">
												<label for="activate" class="col-sm-2 control-label"> @lang('users/form.activcate_user')</label>
												<div class="col-sm-10">
													<input id="activate" name="activate" type="checkbox"
														   class="pos-rel p-l-30"
														   value="1" @if(old('activate')) checked="checked" @endif >
													<span>@lang('users/form.activate-checkbox')</span>
												</div>
											</div>
											
											<div class="form-group">
												<label for="published" class="col-sm-2 control-label"> @lang('users/form.published')</label>
												<div class="col-sm-10">
													<input id="published" name="published" type="checkbox"
														   class="pos-rel p-l-30"
														   value="1" @if(old('published')) checked="checked" @endif >
												<span>@lang('users/form.published-checkbox')</span></div>
											</div>
                                            
                                            <div class="form-group">
												<label for="news" class="col-sm-2 control-label"> @lang('users/form.news')</label>
												<div class="col-sm-10">
													<input id="news" name="news" type="checkbox"
														   class="pos-rel p-l-30"
														   value="1" @if(old('news')) checked="checked" @endif >
												<span>@lang('users/form.news-checkbox')</span></div>
											</div>
											
											<div class="form-group">
												<label for="newsletter" class="col-sm-2 control-label"> @lang('users/form.newsletter')</label>
												<div class="col-sm-10">
													<input id="newsletter" name="newsletter" type="checkbox"
														   class="pos-rel p-l-30"
														   value="1" @if(old('newsletter')) checked="checked" @endif >
												<span>@lang('users/form.newsletter_notice')</span></div>
											</div>
										</div>
										
										<div class="form-group">
											<div class="col-sm-offset-2 col-sm-4">
												<a class="btn btn-danger" href="{{ URL::to('admin/users') }}">
													@lang('button.cancel')
												</a>
												<button type="submit" class="btn btn-success">
													@lang('button.save')
												</button>
											</div>
										</div>

									</div>
									<div class="tab-pane" id="tab2" disabled="disabled">
										<h2 class="hidden">&nbsp;</h2>
										<div class="form-group {{ $errors->first('dob', 'has-error') }}">
											{!! Form::label('dob', trans('users/form.date_of_birth'), array('class' => 'col-lg-2 control-label')) !!}
											<div class="col-lg-6">
												{!! Form::DateTimeInput('dob', null, array('class' => 'dob'))!!}
												<span class="help-block">{{ $errors->first('dob', ':message') }}</span>
											</div>
										</div>

										<div class="form-group">
											<label for="pic" class="col-sm-2 control-label">@lang('users/form.profile_picture')</label>
											<div class="col-sm-10">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                        <img src="//placehold.it/200x200" alt="profile pic" />
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                    <div>
														<span class="btn btn-default btn-file">
															<span class="fileinput-new">@lang('users/form.select_image')</span>
															<span class="fileinput-exists">@lang('users/form.change')</span>
															<input id="pic" name="pic_file" type="file" class="form-control"/>
														</span>
                                                        <a href="#" class="btn btn-danger fileinput-exists"
                                                           data-dismiss="fileinput">@lang('users/form.remove')</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="bio" class="col-sm-2 control-label">@lang('users/form.bio') <small>@lang('users/form.bio_notice')</small></label>
											<div class="col-sm-10">
												<textarea name="bio" id="bio" class="form-control"
													rows="4">{!! old('bio') !!}</textarea>
											</div>
										</div>

									</div>
									
									<div class="tab-pane" id="tab3" disabled="disabled">
										<div class="form-group {{ $errors->first('gender', 'has-error') }}">
                                            <label for="email" class="col-sm-2 control-label">@lang('users/form.gender')</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" title="@lang('users/form.select_gender')" name="gender">
                                                    <option value="">@lang('users/form.select')</option>
                                                    <option value="male"
                                                            @if(old('gender') === 'male') selected="selected" @endif >@lang('users/form.male')
                                                    </option>
                                                    <option value="female"
                                                            @if(old('gender') === 'female') selected="selected" @endif >
                                                        @lang('users/form.female')
                                                    </option>
                                                    <option value="other"
                                                            @if(old('gender') === 'other') selected="selected" @endif >@lang('users/form.other')
                                                    </option>

                                                </select>
                                            </div>
                                            <span class="help-block">{{ $errors->first('gender', ':message') }}</span>
                                        </div>

										<div class="form-group required">
											<label for="country" class="col-sm-2 control-label">@lang('users/form.country') </label>
											<div class="col-sm-10">
												{!! Form::SelectCountry('country', '', null, array('class' => 'form-control select2',  'id' => 'countries', 'placeholder'=>trans('user/form.select-country'))) !!}
											</div>
										</div>

										<div class="form-group">
											<label for="state" class="col-sm-2 control-label">@lang('users/form.state') </label>
											<div class="col-sm-10">
												<input id="state" name="state" type="text" class="form-control"
													value="{!! old('state') !!}"/>
											</div>
										</div>

										<div class="form-group">
											<label for="city" class="col-sm-2 control-label">@lang('users/form.city') </label>
											<div class="col-sm-10">
												<input id="city" name="city" type="text" class="form-control"
													value="{!! old('city') !!}"/>
											</div>
										</div>

										<div class="form-group">
											<label for="address" class="col-sm-2 control-label">@lang('users/form.address') </label>
											<div class="col-sm-10">
												<input id="address" name="address" type="text" class="form-control"
													value="{!! old('address') !!}"/>
											</div>
										</div>

										<div class="form-group">
											<label for="postal" class="col-sm-2 control-label">@lang('users/form.zip')</label>
											<div class="col-sm-10">
												<input id="postal" name="postal" type="text" class="form-control"
													value="{!! old('postal') !!}"/>
											</div>
										</div>
									</div>
									
									<div class="tab-pane" id="tab4" disabled="disabled">
										 <p class="text-danger">
											<strong>@lang('users/form.individual_permissions_info')</strong>
											<span class="pull-right">
												<a href="javascript:void(0)" class="tooglepermissins select-all">@lang('users/form.selectall')</a>
												<a href="javascript:void(0)" class="tooglepermissins unselect-all" style="display:none">@lang('users/form.unselectall')</a>
											</span>
										</p>
										<div class="form-group checkbox-group">
											<label for="slug" class="col-sm-2 control-label">@lang('groups/form.permissions')</label>
											<div class="col-sm-5">
												{!! Form::Permissions() !!}
											</div>
										</div>
									</div>
									<ul class="pager wizard">
										<li class="previous"><a href="#">@lang('users/form.previous')</a></li>
										<li class="cancel"><a href="{{ URL::to('admin/users') }}">@lang('users/form.cancel')</a></li>
										<li class="next"><a href="#">@lang('users/form.next')</a></li>
										<li class="next finish" style="display:none;"><a href="javascript:void(0);">@lang('users/form.finish')</a></li>
									</ul>
								</div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--row end-->
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" ></script>
<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/bootstrapwizard/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/adduser.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>

{!! TemplateHelper::includeDateScript() !!}
	
<script type="text/javascript">
$(document).ready(function() {
	$('.multiselect').each(function() {
		$(this).multiselect({
			includeSelectAllOption: true,
            enableFiltering: true,
            filterBehavior: 'both',
			enableCaseInsensitiveFiltering: true,
        });
		$(this).multiselect('rebuild');
	});
});
</script>
@stop