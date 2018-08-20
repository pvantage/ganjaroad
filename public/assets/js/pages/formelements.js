
    $(document).ready(function() {
        $(
            'input#defaultconfig'
        ).maxlength();

        $(
            'input#thresholdconfig'
        ).maxlength({
            threshold: 20

        });
        $(
            'input#moreoptions'
        ).maxlength({
            alwaysShow: true,
            warningClass: "label label-success",
            limitReachedClass: "label label-danger"
        });

        $(
            'input#alloptions'
        ).maxlength({
            alwaysShow: true,
            warningClass: "label label-success",
            limitReachedClass: "label label-danger",
            separator: ' chars out of ',
            preText: 'You typed ',
            postText: ' chars.',
            vali
                : true
        });
        $(
            'textarea#textarea'
        ).maxlength({
            alwaysShow: true
        });

        $(".display-no").hide();

        $('input#placement')
            .maxlength({
                alwaysShow: true,
                placement: 'bottom'
            });
    });
  $('#card').card({
      container: $('.card-wrapper')
  });

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {
        "placeholder": "dd/mm/yyyy"
    });
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {
        "placeholder": "mm/dd/yyyy"
    });
    //Money Euro
    $("[data-mask]").inputmask();  