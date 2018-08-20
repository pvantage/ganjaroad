@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
@lang('classifiedschema/title.management')
@parent
@stop
@section('header_styles')
    <link href="{{ asset('assets/css/pages/categorytree.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/jtree/css/style.css') }}" rel="stylesheet" type="text/css" />
@stop

{{-- Montent --}}
@section('content')
<section class="content-header">
    <h1>@lang('classifiedschema/title.management')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li><a href="#"> @lang('classifiedschema/title.classifiedschema')</a></li>
        <li class="active">@lang('classifiedschema/title.classifiedschemalist')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-6 col-md-6">
                                <h4 class="panel-title pull-left"> <i class="livicon" data-name="list" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                                    @lang('classifiedschema/title.classifiedschemalist')
                                </h4>
                                <div class="pull-right">
                                    <input type="text" placeholder="Search" id="tree_search" value="">
                                </div>
                            </div>

                            <div class="col-xs-6 col-md-6"> 
                                <div class="pull-right">
									@if ($user->hasAccess(['classifiedschema.data.quickcreate']))
										<a href="javascript:void(0)" onclick="quick_create();" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-asterisk"></i>@lang('button.create')</a>
									@endif
									@if ($user->hasAccess(['classifiedschema.data.quickrename']))
										<a href="javascript:void(0)" onclick="quick_rename();" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-pencil"></i>@lang('button.rename')</a>
									@endif
									@if ($user->hasAccess(['classifiedschema.data.quickremove']))
										<a href="javascript:void(0)" onclick="show_remove_popup()" data-target="#delete_confirm" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove"></i>@lang('button.delete')</a>
									@endif
									@if ($user->hasAccess(['classifiedschema.data.quickrename']))
										<a href="javascript:void(0)" onclick="edit();" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-pencil"></i>@lang('button.edit')</a>
									@endif
									@if ($user->hasAccess(['classifiedschema.data.quickcreate']))
										<a href="{{ URL::to('admin/classifiedschema/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
									@endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="panel-body">
                    <div class="row">
                        <div id="classifiedschema"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>

@stop
{{-- Body Bottom confirm modal --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/jtree/js/jstree.min.js') }}"></script>
    
    <script type="text/javascript">
        function quick_create() {
            var ref = $('#classifiedschema').jstree(true),
            sel = ref.get_selected();
            if(!sel.length) { return false; }
            sel = sel[0];
            sel = ref.create_node(sel);
            if(sel) {
                ref.edit(sel);
            }
        };

        function quick_rename() {
            var ref = $('#classifiedschema').jstree(true),
            sel = ref.get_selected();

			if(!sel.length) { return false; }
            sel = sel[0];
            ref.edit(sel);
        };
		
		function edit() {
            var ref = $('#classifiedschema').jstree(true),
            sel = ref.get_selected();

			if(!sel.length) { return false; }
            id = sel[0];
			
			if(id) {
				var url = '{{ URL::to('admin/classifiedschema' ) }}';
				url += '/' + id + '/edit';
				window.location.href = url;
			}
        };

        function show_remove_popup() {
            var ref = $('#classifiedschema').jstree(true),
            sel = ref.get_selected();
            
            if(sel[0]) {
                var url = '{{ route('confirm-delete/classifiedschema') }}';
                $('#delete_confirm .modal-content').load(url, function(result){
                    $('#delete_confirm #text_modal_title').text('@lang('general.notice')');
                    $('#delete_confirm #text_modal_content').text('@lang('classifiedschema/modal.select_item')');
                    $('#delete_confirm').modal({show:true});
                });
            } else {
                var url = '{{ route('admin/modal') }}';
                $('#delete_confirm .modal-content').load(url, function(result){
                    $('#delete_confirm #text_modal_title').text('@lang('general.notice')');
                    $('#delete_confirm #text_modal_content').text('@lang('classifiedschema/modal.select_item')');
                    $('#delete_confirm').modal({show:true});
                });
            }
        }
        
        function deleteItem() {
            var ref = $('#classifiedschema').jstree(true),
            sel = ref.get_selected();

            if(!sel.length) { return false; }
            ref.delete_node(sel);
            
            var id = sel[0];
            $.ajax({
                type: 'GET', 
                url: '{!! route('classifiedschema.data.quickremove') !!}', 
                data: {id: id}, 
                dataType: 'json',
                success: function (data) {
                    $('#delete_confirm').modal('hide');
                }
            });   
        };
        
        $(document).ready(function() {
            var to = false;
            $('#tree_search').keyup(function () {
                if(to) { clearTimeout(to); }
                to = setTimeout(function () {
                    var v = $('#tree_search').val();
                    $('#classifiedschema').jstree(true).search(v);
                }, 250);
            });

            $('#classifiedschema').jstree({
                'core' : {
                  'animation' : 0,
                  'check_callback': true,
                  'themes' : { 'stripes' : true },
                  'data' : function (obj, callback) {
                        $.ajax({
                            type: 'GET', 
                            url: '{!! route('classifiedschema.data') !!}', 
                            data: {id: obj.id}, 
                            dataType: 'json',
                            success: function (data) {
                                callback.call(this, data.data);
                            }
                        });
                    }
                },
                "types" : {
                    "#" : {
                        "icon" : "glyphicon glyphicon-file",
                    },
                    "root" : {
                      "icon" : "glyphicon glyphicon-file",
                    },
                    "default" : {
                       "icon" : "glyphicon glyphicon-file",
                    },
                    "notpublished" : {
                      "icon" : "glyphicon glyphicon-file notpublished",
                    }
                },
                'plugins' : [
                  'contextmenu', 'dnd', 'crrm', 'search',
                  'types', 'wholerow', 'state'
                ],
				'contextmenu': {         
					"items": function($node) {
						var tree = $("#classifiedschema").jstree(true);
						return {
							"Open": {
								"separator_before": false,
								"separator_after": false,
								"label": "@lang('button.open')",
								"action": function (obj) { 
									var url = '{{ URL::to('admin/classifiedschema' ) }}';
									url += '/' + $node.id + '/edit';
									window.location.href = url;
								}
							},
							"Create": {
								"separator_before": false,
								"separator_after": false,
								"label": "@lang('button.create')",
								"action": function (obj) { 
									$node = tree.create_node($node);
									tree.edit($node);
								}
							},
							"Rename": {
								"separator_before": false,
								"separator_after": false,
								"label": "@lang('button.rename')",
								"action": function (obj) { 
									tree.edit($node);
								}
							},
						};
					}
				}
            }).on('rename_node.jstree', function (e, data) {
                var ref = $('#classifiedschema').jstree(true);
                var id = data.node.id;
                var parentId = data.node.parent;
                var text = data.text;

                parent = ref.get_node(parentId);
                var position = $.inArray(id, parent.children) + 1;
                
                if(isNaN(data.node.id)) {
                    $.ajax({
                        type: 'GET', 
                        url: '{!! route('classifiedschema.data.quickcreate') !!}', 
                        data: {text: text, parent: parentId, position: position }, 
                        dataType: 'json',
                        success: function (data) {
                            $('#classifiedschema').jstree(true).refresh();
                        }
                    });
                } else {
                    $.ajax({
                        type: 'GET', 
                        url: '{!! route('classifiedschema.data.quickrename') !!}', 
                        data: {text: text, id: id}, 
                        dataType: 'json',
                        success: function (data) {
                        }
                    });
                }
            });
            
            $(document).on('dnd_stop.vakata', function (e, data) {
                var ref = $('#classifiedschema').jstree(true);
                var id = data.data.nodes[0];
                var parentId = ref.get_node(id).parent; // '#' = no parent!
                if(parentId == '#') parentId = 0;
               
                parent = ref.get_node(parentId);
                var position = $.inArray(id, parent.children) + 1;
                
                $.ajax({
                    type: 'GET', 
                    url: '{!! route('classifiedschema.data.move') !!}', 
                    data: {id: id, parent: parentId, position: position }, 
                    dataType: 'json',
                    success: function (data) {
                    }
                });
            });
			$("#classifiedschema").delegate("a","dblclick", function(e) {
				var ref = $('#classifiedschema').jstree(true);
				sel = ref.get_selected();
				var id = sel[0];
				
				var url = '{{ URL::to('admin/classifiedschema' ) }}';
				url += '/' + id + '/edit';
				window.location.href = url;
			});
        });
    </script>
    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="classifiedschema_delete_confirm_title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <script>
        $(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});
	</script>
@stop
