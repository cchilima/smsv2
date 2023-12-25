<script>
    {{--Notifications--}}

    @if (session('pop_error'))
    pop({msg: '{{ session('pop_error') }}', type: 'error'});
    @endif

    @if (session('pop_warning'))
    pop({msg: '{{ session('pop_warning') }}', type: 'warning'});
    @endif

    @if (session('pop_success'))
    pop({msg: '{{ session('pop_success') }}', type: 'success', title: 'GREAT!!'});
    @endif

    @if (session('flash_info'))
    flash({msg: '{{ session('flash_info') }}', type: 'info'});
    @endif

    @if (session('flash_success'))
    flash({msg: '{{ session('flash_success') }}', type: 'success'});
    @endif

    @if (session('flash_warning'))
    flash({msg: '{{ session('flash_warning') }}', type: 'warning'});
    @endif

    @if (session('flash_error') || session('flash_danger'))
    flash({msg: '{{ session('flash_error') ?: session('flash_danger') }}', type: 'danger'});
    @endif

    {{--End Notifications--}}

    function pop(data) {
        swal({
            title: data.title ? data.title : 'Oops...',
            text: data.msg,
            icon: data.type
        });
    }

    function flash(data) {
        new PNotify({
            text: data.msg,
            type: data.type,
            hide: true
            //hide: data.type !== "danger"
        });
    }

    function confirmDelete(id) {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this item!",
            icon: "warning",
            buttons: true,
            dangerMode: true
        }).then(function (willDelete) {
            if (willDelete) {
                $('form#item-delete-' + id).submit();
            }
        });
    }

    function confirmReset(id) {
        swal({
            title: "Are you sure?",
            text: "This will reset this item to default state",
            icon: "warning",
            buttons: true,
            dangerMode: true
        }).then(function (willDelete) {
            if (willDelete) {
                $('form#item-reset-' + id).submit();
            }
        });
    }

    $('form#ajax-reg').on('submit', function (ev) {
        ev.preventDefault();
        submitForm($(this), 'store');
        $('#ajax-reg-t-0').get(0).click();
    });

    $('form.ajax-pay').on('submit', function (ev) {
        ev.preventDefault();
        submitForm($(this), 'store');

//        Retrieve IDS
        var form_id = $(this).attr('id');
        var td_amt = $('td#amt-' + form_id);
        var td_amt_paid = $('td#amt_paid-' + form_id);
        var td_bal = $('td#bal-' + form_id);
        var input = $('#val-' + form_id);

        // Get Values
        var amt = parseInt(td_amt.data('amount'));
        var amt_paid = parseInt(td_amt_paid.data('amount'));
        var amt_input = parseInt(input.val());

//        Update Values
        amt_paid = amt_paid + amt_input;
        var bal = amt - amt_paid;

        td_bal.text('' + bal);
        td_amt_paid.text('' + amt_paid).data('amount', '' + amt_paid);
        input.attr('max', bal);
        bal < 1 ? $('#' + form_id).fadeOut('slow').remove() : '';
    });
    //start
    $('form.ajax-store').on('submit', function (ev) {
        ev.preventDefault();
        submitForm($(this), 'store');
        var div = $(this).data('reload');
        div ? reloadDiv(div) : '';
    });

    $('form.ajax-update').on('submit', function (ev) {
        ev.preventDefault();
        submitForm($(this));
        var div = $(this).data('reload');
        div ? reloadDiv(div) : '';
    });

    $('.download-receipt').on('click', function (ev) {
        ev.preventDefault();
        $.get($(this).attr('href'));
        flash({msg: '{{ 'Download in Progress' }}', type: 'info'});
    });

    function reloadDiv(div) {
        var url = window.location.href;
        url = url + ' ' + div;
        $(div).load(url);
    }

    function submitForm(form, formType) {
        var btn = form.find('button[type=submit]');
        disableBtn(btn);
        var ajaxOptions = {
            url: form.attr('action'),
            type: 'POST',
            cache: false,
            processData: false,
            dataType: 'json',
            contentType: false,
            data: new FormData(form[0])
        };
        var req = $.ajax(ajaxOptions);
        req.done(function (resp) {
            resp.ok && resp.msg
                ? flash({msg: resp.msg, type: 'success'})
                : flash({msg: resp.msg, type: 'danger'});
            hideAjaxAlert();
            enableBtn(btn);
            formType == 'store' ? clearForm(form) : '';
            scrollTo('body');
            return resp;
        });
        req.fail(function (e) {
            if (e.status == 422) {
                var errors = e.responseJSON.errors;
                displayAjaxErr(errors);
            }
            if (e.status == 500) {
                displayAjaxErr([e.status + ' ' + e.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])
            }
            if (e.status == 404) {
                displayAjaxErr([e.status + ' ' + e.statusText + ' - Requested Resource or Record Not Found'])
            }
            enableBtn(btn);
            return e.status;
        });
    }

    function disableBtn(btn) {
        var btnText = btn.data('text') ? btn.data('text') : 'Submitting';
        btn.prop('disabled', true).html('<i class="icon-spinner mr-2 spinner"></i>' + btnText);
    }

    function enableBtn(btn) {
        var btnText = btn.data('text') ? btn.data('text') : 'Submit Form';
        btn.prop('disabled', false).html(btnText + '<i class="icon-paperplane ml-2"></i>');
    }

    function displayAjaxErr(errors) {
        $('#ajax-alert').show().html(' <div class="alert alert-danger border-0 alert-dismissible" id="ajax-msg"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>');
        $.each(errors, function (k, v) {
            $('#ajax-msg').append('<span><i class="icon-arrow-right5"></i> ' + v + '</span><br/>');
        });
        scrollTo('body');
    }

    function scrollTo(el) {
        $('html, body').animate({
            scrollTop: $(el).offset().top
        }, 2000);
    }

    function hideAjaxAlert() {
        $('#ajax-alert').hide();
    }

    function clearForm(form) {
        form.find('.select, .select-search').val([]).select2({placeholder: 'Select...'});
        form[0].reset();
    }

    function prepareUserinfor(userid) {
        $('.personal-infor span').hide();
        //console.log(userid);
       // $('.personal-infor span').hide();
        var firsname = $('#fname' + userid), midname = $('#mname' + userid), lastname = $('#lname' + userid),
            gender = $('#gender' + userid), email = $('#email' + userid), nrc = $('#nrc' + userid),
            passport = $('#passport' + userid),
            dob = $('#dob' + userid), marital_status = $('#marital_status' + userid), mobile = $('#mobile' + userid),
            street = $('#street' + userid),
            town = $('#town' + userid), province_id = $('#province_id' + userid),
            country_id = $('#country_id' + userid);
        firsname.removeClass('d-none');
        firsname.show();
        midname.removeClass('d-none');
        lastname.removeClass('d-none');
        gender.removeClass('d-none');
        email.removeClass('d-none');
        nrc.removeClass('d-none');
        passport.removeClass('d-none');
        dob.removeClass('d-none');
        marital_status.removeClass('d-none');
        mobile.removeClass('d-none');
        street.removeClass('d-none');
        town.removeClass('d-none');
        province_id.removeClass('d-none');
        country_id.removeClass('d-none');


        // var showSelect = true; // Change this condition as needed
        //
        // // Check the condition and toggle visibility accordingly
        // if (showSelect) {
        //     $('.personal-infor span').hide();
        // } else {
        //     $('.personal-infor span').show();
        // }
    }

    function UpdateGeneralinformation(data, countries, programs, towns, provinces, course_levels, intake, inforType, username) {


    }
    function UpdateNkininformation(id){
        var kin_country_id = $('#kin_country_id'+id),kin_province_id = $('#kin_province_id'+id),kin_town_id = $('#kin_town_id'+id),
            kin_relationship_id = $('#kin_relationship_id'+id),telephone = $('#telephone'+id),mobile = $('#mobile'+id),name = $('#name'+id);
        $('.next-of-kin-infor span').hide();

        kin_country_id.removeClass('d-none');
        kin_province_id.removeClass('d-none');
        kin_town_id.removeClass('d-none');
        kin_relationship_id.removeClass('d-none');
        telephone.removeClass('d-none');
        mobile.removeClass('d-none');
        name.removeClass('d-none');
    }

    function manageAcademicInfor(key) {
        $('.academic-infor span').hide();
        var study_mode_id = $('#study_mode_id-' + key), course_level_id = $('#course_level_id-' + key),
            program_id = $('#program_id-' + key),
            academic_period_intake_id = $('#academic_period_intake_id-' + key),
            period_type_id = $('#period_type_id-' + key);

        study_mode_id.addClass('select-search');
        course_level_id.addClass('select-search');
        program_id.addClass('select-search');
        academic_period_intake_id.addClass('select-search');
        period_type_id.addClass('select-search');


        period_type_id.show();
        course_level_id.show();
        program_id.show();
        academic_period_intake_id.show();
        study_mode_id.show();
        //let actual = $('#class' + classID).val();

        // var displaymode = $('#display-mode' + classID);
        // var textContent = displaymode.text();
        // var input = $('#class' + classID);
        //
        // var totalElement = $(".assess-total");

        // Get the text content and extract the total value
        // var totalText = totalElement.text();
        // var totalValue = totalText.match(/\d+/);
        //
        // var newValues = ((actual / 100) * totalValue)
        //
        // let apid = $('#apid' + classID).val(),
        //     student_id = classID,
        //     programID = $('#program' + classID).val(),
        //     code = $('#course' + classID).val(),
        //     title = $('#title' + classID).val(),
        //     type = $('#assessid' + classID).val(),
        //     id = $('#idc' + classID).val(),
        //     userID = $('#userid'+ classID).val();


        //url = url.replace(':id', classID);
        // Perform an AJAX request to update the database with the new value
        // You can use Laravel's route and controller for this
        //console.log(newValues);
        // $.ajax({
        //     url: url, // Replace with the actual route
        //     method: 'POST',
        //     dataType: 'json',
        //     data: {
        //         academicPeriodID: apid,
        //         programID: programID,
        //         studentID: student_id,
        //         code: code,
        //         title: title,
        //         total: newValues,
        //         id: id,
        //         type: type,
        //         userID: userID
        //     },
        //     success: function (resp) {
        //         // Update the display mode with the new value
        //         console.log(resp)
        //         if (resp.ok === true) {
        //             displaymode.text(newValues);
        //             displaymode.show();
        //             input.hide();
        //         } else {
        //             displaymode.text(textContent);
        //             displaymode.show();
        //             input.hide();
        //         }
        //         resp.ok && resp.msg ? flash({msg: resp.msg, type: 'success'}) : flash({msg: resp.msg, type: 'danger'});
        //     }, error: function (xhr, status, error) {
        //         flash({msg: error, type: 'danger'})
        //     }
        // });
    }
    //academic classes for assessements
    function getAcClassesPD(ac_id) {
        var url = '<?php echo e(route('class-names', [':id'])); ?>';
        url = url.replace(':id', ac_id);
        var classId = $('#classID');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                classId.empty();
                classId.append($('<option>', {
                    value: '',
                    text: 'Choose ...'
                }));
                $.each(resp, function (i, data) {
                    classId.append($('<option>', {
                        value: data.id,
                        text: data.course.code+' - '+data.course.name
                    }));
                });
            }
        })
    }
    $('.edit-total-link').on('click', function () {

        var row = $(this).closest('tr');
        row.find('.display-mode').hide();
        row.find('.edit-mode').show();
    });
    //});
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function updateExamResults(classID) {
        //date
        let newValuesendDate = $('#enddate' + classID).val();
        var displaymodedate = $('#display-mode-enddate' + classID);
        var inputdate = $('#enddate' + classID);

        let newValues = $('#class' + classID).val();
        var displaymode = $('#display-mode' + classID);
        var input = $('#class' + classID);

        let url = '{{ route('assessmentUpdate', [':id']) }}';
        url = url.replace(':id', classID);
        // Perform an AJAX request to update the database with the new value
        // You can use Laravel's route and controller for this
        console.log(newValues);
        $.ajax({
            url: url, // Replace with the actual route
            method: 'POST',
            dataType: 'json',
            data: {
                total: newValues,
                end_date: newValuesendDate,
            },
            success: function (resp) {
                // Update the display mode with the new value
                displaymode.text(newValues);
                displaymode.show();
                input.hide();

                displaymodedate.text(newValuesendDate);
                displaymodedate.show();
                inputdate.hide();

                resp.ok && resp.msg ? flash({msg: resp.msg, type: 'success'}) : flash({msg: resp.msg, type: 'danger'});
            }, error: function (xhr, status, error) {
                flash({msg: error, type: 'danger'})
            }
        });
    }

    function CloseModal() {
        $('#staticBackdrop').modal('hide');
    }
</script>


<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content row col card card-body">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <div class="list-icons">
                    <a type="submit" class="btn btn-outline-light" onclick="CloseModal()"><i
                            class="icon-close2 ml-2"></i></a>
                </div>
            </div>
            <div class="modal-body p-3">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeModalButton"
                        onclick="CloseModal()"
                        id="closeModalButton" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" id="submitButton" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
