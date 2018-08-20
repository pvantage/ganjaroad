// bootstrap wizard//
$("#gender, #gender1").select2({
    theme:"bootstrap",
    placeholder:"",
    width: '100%'
});

$("#commentForm").bootstrapValidator({
    fields: {
        first_name: {
            validators: {
                notEmpty: {
                    message: 'The full name is required'
                }
            },
            required: true,
            minlength: 3
        },
        last_name: {
            validators: {
                notEmpty: {
                    message: 'The full name is required'
                }
            },
            required: true,
            minlength: 3
        },
        password: {
            validators: {
            }
        },
        password_confirm: {
            validators: {
                identical: {
                    field: 'password'
                }
            }
        },
        email: {
            validators: {
                notEmpty: {
                    message: 'The email address is required'
                },
                emailAddress: {
                    message: 'The input is not a valid email address'
                }
            }
        },
        group: {
            validators:{
                notEmpty:{
                    message: 'You must select a group'
                }
            }
        }
    }
});

$('#rootwizard').bootstrapWizard({
    'tabClass': 'nav nav-pills',
    'onNext': function(tab, navigation, index) {
        var $validator = $('#commentForm').data('bootstrapValidator').validate();
        return $validator.isValid();
    },
    onTabClick: function(tab, navigation, index) {
        //return false;
    },
    onTabShow: function(tab, navigation, index) {
        var $total = navigation.find('li').length;
        var $current = index + 1;

        // If it's the last tab then hide the last button and show the finish instead
        if ($current >= $total) {
            $('#rootwizard').find('.pager .next').hide();
            $('#rootwizard').find('.pager .finish').show();
            $('#rootwizard').find('.pager .finish').removeClass('disabled');
        } else {
            $('#rootwizard').find('.pager .next').show();
            $('#rootwizard').find('.pager .finish').hide();
        }
    }
});

$('#rootwizard .finish').click(function () {
    var $validator = $('#commentForm').data('bootstrapValidator').validate();
    if ($validator.isValid()) {
        document.getElementById("commentForm").submit();
    }

});

$('.tooglepermissins').on('click', function(){
	if($(this).hasClass('select-all')) {
		$(this).hide();
		$('.tooglepermissins.unselect-all').show();
		$('#tab4 .permissions-table .btn-group').each(function( i, l ){
			$(this).find('.btn').addClass('active');
			$(this).find('input[type=checkbox]').prop('checked', true);
		});
	} else {
		$(this).hide();
		$('.tooglepermissins.select-all').show();
		$('#tab4 .permissions-table .btn-group').each(function( i, l ){
			$(this).find('.btn').removeClass('active');
			$(this).find('input[type=checkbox]').prop('checked', false);
		});
	}
});