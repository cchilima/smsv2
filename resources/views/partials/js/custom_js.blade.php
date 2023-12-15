<script>
    function getAcClasses(ac_id) {
        var url = '{{ route('class-names', [':id']) }}';
        url = url.replace(':id', ac_id);
        var classId = $('#to-upload-classID');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                classId.empty();
                $.each(resp, function (i, data) {
                    classId.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
            }
        })
    }

    function getLevels(l_id) {
        var url = '{{ route('get_levels', [':id']) }}';
        url = url.replace(':id', l_id);
        var levelId = $('#level_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                levelId.empty();
                $.each(resp, function (i, data) {
                    levelId.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
            }
        })
    }

    function getPrograms(p_id) {
        var url = '{{ route('get_programs', [':id']) }}';
        url = url.replace(':id', p_id);
        var program = $('#program_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                program.empty();
                $.each(resp, function (i, data) {
                    program.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
            }
        })
    }

    function getStates(t_id) {
        var url = '{{ route('get_states', [':id']) }}';
        url = url.replace(':id', t_id);
        var town = $('#state_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                town.empty();
                $.each(resp, function (i, data) {
                    town.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
            }
        })
    }

    function getStatesN(t_id) {
        var url = '{{ route('get_states', [':id']) }}';
        url = url.replace(':id', t_id);
        var town = $('#state_idn');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                town.empty();
                $.each(resp, function (i, data) {
                    town.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
            }
        })
    }

    function getTowns(state_id) {
        var url = '{{ route('get_towns', [':id']) }}';
        url = url.replace(':id', state_id);
        var lga = $('#lga_id');
        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                //console.log(resp);
                lga.empty();
                $.each(resp, function (i, data) {
                    lga.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
            }
        })
    }

    function getTownsN(state_id) {
        var url = '{{ route('get_towns', [':id']) }}';
        url = url.replace(':id', state_id);
        var lga = $('#lga_idn');
        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                //console.log(resp);
                lga.empty();
                $.each(resp, function (i, data) {
                    lga.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
            }
        })
    }

    function get(state_id) {
        var url = '{{ route('get_towns', [':id']) }}';
        url = url.replace(':id', state_id);
        var lga = $('#lga_id');
        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                //console.log(resp);
                lga.empty();
                $.each(resp, function (i, data) {
                    lga.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
            }
        })
    }


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
    $('form.ajax-store-publish').on('submit', function (ev) {
        ev.preventDefault();
        submitForm($(this), 'store');
        var div = $(this).data('reload');
        div ? reloadDiv(div) : '';
        location.reload();
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

    //exemptions
    $('.user-all').change(function (e) {
        var value = $('.user-all:checked').val();
        if (value == 1) {
            $('input[name="ckeck_user"]').prop('checked', true);
            $('.publish-results').removeAttr('disabled');
        } else {
            $('input[name="ckeck_user"]').prop('checked', false);
            $('.se').attr('disabled', 'disabled');
        }
    });

    $("input[name='ckeck_user']").change(function () {
        if ($("input[name='ckeck_user']:checked").length > 0) {
            $('.publish-results').removeAttr('disabled');
        } else {
            $('.publish-results').attr('disabled', 'disabled');
        }
    });

    $('.publish-results').click(function (e) {
        e.preventDefault();
        var ids = [];

        $.each($('input[name="ckeck_user"]:checked'), function () {
            ids.push($(this).data('id'));
        });

        if (ids !== '') {
            $(this).attr("disabled", true);
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Exempt');
            $.ajax({
                url: '',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: ids
                },
                success: function (data) {
                    $('.success-mail').css('display', 'block');
                    $('.publish-results').attr("disabled", false);
                    $('.publish-results').html('<i class="fa fa-share"></i> Exempt');
                }
            });
        }
    });
    //total marcks for exams

    //$(document).ready(function () {
    // Edit link click event
    //$('.datableClassAssessment').on('click', '.edit-total-link', function () {
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

        let url = '{{ route('classExamUpdateTotal', [':id']) }}';
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

    function updateExamResultsToPublish(classID) {
        let newValues = $('#class' + classID).val();
        var displaymode = $('#display-mode' + classID);
        var input = $('#class' + classID);

        let url = '{{ route('resultsPublish', [':id']) }}';
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
            },
            success: function (resp) {
                // Update the display mode with the new value
                displaymode.text(newValues);
                displaymode.show();
                input.hide();
                resp.ok && resp.msg ? flash({msg: resp.msg, type: 'success'}) : flash({msg: resp.msg, type: 'danger'});
            }, error: function (xhr, status, error) {
                flash({msg: error, type: 'danger'})
            }
        });
    }

    //enterResults

    function EnterResults(classID) {
        let actual = $('#class' + classID).val();

        var displaymode = $('#display-mode' + classID);
        var textContent = displaymode.text();
        var input = $('#class' + classID);

        var totalElement = $(".assess-total");

        // Get the text content and extract the total value
        var totalText = totalElement.text();
        var totalValue = totalText.match(/\d+/);

        var newValues = ((actual / 100) * totalValue)

        let apid = $('#apid' + classID).val(),
            student_id = classID,
            programID = $('#program' + classID).val(),
            code = $('#course' + classID).val(),
            title = $('#title' + classID).val(),
            type = $('#assessid' + classID).val(),
            id = $('#idc' + classID).val(),
            userID = $('#userid'+ classID).val();

        let url = '{{ route('postedResults.process')}}';
        //url = url.replace(':id', classID);
        // Perform an AJAX request to update the database with the new value
        // You can use Laravel's route and controller for this
        //console.log(newValues);
        $.ajax({
            url: url, // Replace with the actual route
            method: 'POST',
            dataType: 'json',
            data: {
                academicPeriodID: apid,
                programID: programID,
                studentID: student_id,
                code: code,
                title: title,
                total: newValues,
                id: id,
                type: type,
                userID: userID
            },
            success: function (resp) {
                // Update the display mode with the new value
                console.log(resp)
                if (resp.ok === true) {
                    displaymode.text(newValues);
                    displaymode.show();
                    input.hide();
                } else {
                    displaymode.text(textContent);
                    displaymode.show();
                    input.hide();
                }
                resp.ok && resp.msg ? flash({msg: resp.msg, type: 'success'}) : flash({msg: resp.msg, type: 'danger'});
            }, error: function (xhr, status, error) {
                flash({msg: error, type: 'danger'})
            }
        });
    }

    //upload results get running programs
    function getRunningPrograms(ac_id) {
        var url = '<?php echo e(route('program-names', [':id'])); ?>';
        url = url.replace(':id', ac_id);
        var classId = $('.programID');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                classId.empty();
                classId.append($('<option>', {
                    value: '',
                    text: 'Choose ....'
                }));
                $.each(resp, function (i, data) {
                    classId.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
            }
        })
    }

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
                        text: data.name
                    }));
                });
            }
        })
    }

    function reloadActiveTabContent() {
        var activeTab = $('#myTabs .nav-link.active'); // Get the currently active tab
        var activeTabContentId = activeTab.attr('href'); // Get the href attribute of the active tab
        var activeTabContent = $(activeTabContentId); // Get the corresponding content div

        // Reload the active tab content
        console.log("reloaded");
        activeTabContent.load(window.location.href + ' ' + activeTabContentId);
    }

    function modifyMarks(studentID, program, academic, code,type) {
        console.log(studentID);
        //$('#staticBackdrop').modal('show');
        var modalBody = $('#staticBackdrop .modal-body');
        modalBody.empty();
        var url = '<?php echo e(route('update.assessments')); ?>';

        $.ajax({
            url: url, // Replace with the actual route
            method: 'POST',
            dataType: 'json',
            data: {
                academicPeriodID: academic,
                programID: program,
                studentID: studentID,
                code: code,
                type:type
            },
            success: function (resp) {
                // Update the display mode with the new value
                console.log(resp)
                // Check if the response is valid and contains data
                if (resp && resp.length > 0) {
                    // Assuming that you want to update the modal body with data from the response

                    var title = $('#staticBackdropLabel');
                    title.text(resp[0].first_name + ' ' + resp[0].last_name);
                    // Clear the existing content in the modal body
                    modalBody.empty();
                    var assessmentHtml = '<div>';
                    assessmentHtml += '<p>Student Number : ' + resp[0].student_id + '</p>';
                    assessmentHtml += '<p class="title">Code: ' + resp[0].code + '</p>';
                    assessmentHtml += '<p class="header">Title: ' + resp[0].title + '</p>';
                    assessmentHtml += '</div>';

                    modalBody.append(assessmentHtml);
                    resp.forEach(function (assessment) {
                        //var assessmentHtml = '<div>';
                        var assessmentHtml = '<div class="assessment" data-outof="' + assessment.total + '" data-key="' + assessment.key + '" data-id="' + assessment.id + '">';
                        assessmentHtml += '<p>Assessment: ' + assessment.assessment_name + '</p>';
                        assessmentHtml += '<p>' + assessment.marks + ' Out of: ' + assessment.total + '</p>';
                        // assessmentHtml += '<label for="total">Total:</label>';
                        //assessmentHtml += '<input class="form-control total-input" type="number" name="total" value="' + assessment.marks + '">';
                        assessmentHtml += '<input class="form-control total-input" type="number" name="total" value="0">';
                        assessmentHtml += '<hr>';
                        // Add more fields as needed

                        assessmentHtml += '</div>';

                        modalBody.append(assessmentHtml);
                    });
                    var assessmentHtml = '<br/>';
                    assessmentHtml += ' <div class="form-check">';
                    assessmentHtml += '    <input class="form-check-input" value="0" type="radio" name="operation" id="operation1" >';
                    assessmentHtml += '        <label class="form-check-label" for="flexRadioDefault1">Subtract</label>';
                    assessmentHtml += '</div>';
                    assessmentHtml += '<div class="form-check">';
                    assessmentHtml += '    <input class="form-check-input" value="1" type="radio" name="operation" id="operation2" >';
                    assessmentHtml += '        <label class="form-check-label" for="flexRadioDefault2">Add</label>';
                    assessmentHtml += '</div>';
                    modalBody.append(assessmentHtml);

                    // Show the modal
                    $('#staticBackdrop').modal('show');
                } else {
                    // Handle the case where there is no data or an error occurred
                    console.log('No data found in the response or an error occurred.');
                    flash({msg: 'No Assessments found for the course', type: 'danger'})
                }
                //resp.ok && resp.msg ? flash({msg: resp.msg, type: 'success'}) : flash({msg: resp.msg, type: 'danger'});
            }, error: function (xhr, status, error) {
                flash({msg: error, type: 'danger'})
            }
        });

    }

    function modifyMarksCloseModal() {
        $('#staticBackdrop').modal('hide');
    }

    //modify for all launchmodal

    function StrMod4All(program, academic, code,type) {
        console.log(program);

        var modalBody = $('#staticBackdrop .modal-body');
        modalBody.empty();
        var url = '<?php echo e(route('update.assessments')); ?>';
        $('#assesmentID').val('');

        $.ajax({
            url: url, // Replace with the actual route
            method: 'POST',
            dataType: 'json',
            data: {
                academicPeriodID: academic,
                programID: program,
                code: code,
                type:type
            },
            success: function (resp) {
                // Update the display mode with the new value
                console.log(resp)
                // Check if the response is valid and contains data
                if (resp && resp.length > 0) {
                    // Assuming that you want to update the modal body with data from the response

                    var title = $('#staticBackdropLabel');
                    title.text(resp[0].code + ' - ' + resp[0].name);
                    // Clear the existing content in the modal body
                    modalBody.empty();
                    var assessmentHtml = '<div>';
                    assessmentHtml += '<p class="title">Code: ' + resp[0].code + '</p>';
                    assessmentHtml += '<p class="header">Title: ' + resp[0].name + '</p>';
                    assessmentHtml += '</div>';

                    modalBody.append(assessmentHtml);
                    resp.forEach(function (assessment) {
                        //var assessmentHtml = '<div>';
                        var assessmentHtml = '<div class="assessment" data-apid="' + assessment.academic_period_id + '" data-code="' +
                            assessment.code + '" data-id="' + assessment.assessment_type_id + '"data-outof="' + assessment.total + '">';
                        assessmentHtml += '<p>Assessment: ' + assessment.assessment_type_name + '</p>';
                        assessmentHtml += '<p>Out of: ' + assessment.total + '</p>';
                        // assessmentHtml += '<label for="total">Total:</label>';
                        assessmentHtml += '<input class="form-control total-input" type="number" name="total" value="0">';
                        assessmentHtml += '<hr>';
                        // Add more fields as needed

                        assessmentHtml += '</div>';

                        modalBody.append(assessmentHtml);
                    });
                    var assessmentHtml = '<br/>';
                    assessmentHtml += ' <div class="form-check">';
                    assessmentHtml += '    <input class="form-check-input" value="0" type="radio" name="operation" id="operation1" >';
                    assessmentHtml += '        <label class="form-check-label" for="flexRadioDefault1">Subtract</label>';
                    assessmentHtml += '</div>';
                    assessmentHtml += '<div class="form-check">';
                    assessmentHtml += '    <input class="form-check-input" value="1" type="radio" name="operation" id="operation2" >';
                    assessmentHtml += '        <label class="form-check-label" for="flexRadioDefault2">Add</label>';
                    assessmentHtml += '</div>';
                    modalBody.append(assessmentHtml);

                    // Show the modal
                    $('#staticBackdrop').modal('show');
                    $('#assesmentID').val('');
                } else {
                    // Handle the case where there is no data or an error occurred
                    console.log('No data found in the response or an error occurred.');
                    flash({msg: 'No Assessments found for the course', type: 'danger'})
                    $('#assesmentID').val('');
                }
                //resp.ok && resp.msg ? flash({msg: resp.msg, type: 'success'}) : flash({msg: resp.msg, type: 'danger'});
            }, error: function (xhr, status, error) {
                flash({msg: error, type: 'danger'})
                $('#assesmentID').val('');
            }

        });
        $('#assesmentID').val('');
        ;

    }
    // Attach a click event handler to the Submit button
    $('#submitButton').on('click', function () {
        // Create an array to store the updated "Total" and "key" values
        var updatedAssessments = [];
        var modalBody = $('#staticBackdrop .modal-body');


        // Find all assessment containers inside the modal body
        var assessmentContainers = modalBody.find('.assessment');

        // Loop through the assessment containers and collect "Total" and "key" values
        assessmentContainers.each(function () {
            var assessment = {};
            assessment.total = $(this).find('.total-input').val();
            //assessment.total = $(this).find('.total-input').val();
            assessment.id = $(this).data('id');
            assessment.key = $(this).data('key');
            assessment.code = $(this).data('code');
            assessment.apid = $(this).data('apid');
            assessment.outof = $(this).data('outof');
            updatedAssessments.push(assessment);
        });

        let url = '{{ route('BoardofExaminersUpdateResults')}}';
        var program = $('input[name="program"]').val();
        //var operation = $('input[name="operation"]').val();
        //var operation = $('input[name="operation"]:last').val();
        var radioButtons = $('input[type="radio"]');
        var lastClickedRadioButton = radioButtons.filter(':checked');

// Get the value of the last radio button that was clicked.
        var operation = lastClickedRadioButton.val();
        if (operation === '' || !lastClickedRadioButton.is(':checked')) {
            flash({
                msg: 'Check the operation to undertake',
                type: 'danger'
            });
        } else{
            $.ajax({
                url: url, // Replace with the actual route
                method: 'POST',
                dataType: 'json',
                data: {
                    updatedAssessments: updatedAssessments,
                    program: program,
                    operation: operation

                },
                success: function (resp) {
                    // Update the display mode with the new value
                    console.log(resp)
                    if (resp.ok === true) {
                        $('#staticBackdrop').modal('hide');

                    } else {
                        $('#staticBackdrop').modal('hide');
                    }
                    resp.ok && resp.msg ? flash({msg: resp.msg, type: 'success'}) : flash({
                        msg: resp.msg,
                        type: 'danger'
                    });
                }, error: function (xhr, status, error) {
                    flash({msg: error, type: 'danger'})
                }
            });
            $('#staticBackdrop').modal('hide');
    }
        // You now have an array containing objects with "Total" and "key" values
        console.log(updatedAssessments);
       // $('#staticBackdrop').modal('hide');

        // You can send this data to the server using an AJAX request or perform any other action as needed.
    });

    // Example: Reload on button click
    $('.reloadButton').click(function () {
        reloadActiveTabContent();
    });
    //check out  using publish all

    $('.user-all').change(function (e) {
        var value = $('.user-all:checked').val();
        if (value == 1) {
            $('input[name="ckeck_user"]').prop('checked', true);
            $('.publish-results-board').removeAttr('disabled');
        } else {
            $('input[name="ckeck_user"]').prop('checked', false);
            $('.publish-results-board').attr('disabled', 'disabled');
        }
    });


    $("input[name='ckeck_user']").change(function () {
        if ($("input[name='ckeck_user']:checked").length > 0) {
            $('.publish-results-board').removeAttr('disabled');
        } else {
            $('.publish-results-board').attr('disabled', 'disabled');
        }
    });



    $('.publish-results-board').click(function (e) {
        e.preventDefault();
        var ids = [];

        $.each($('input[name="ckeck_user"]:checked'), function () {
            ids.push($(this).data('id'));
        });
        var academic = $('input[name="academic"]').val();
        var program = $('input[name="program"]').val();
        var type = $('input[name="typepublish"]').val();
        console.log(academic);
        if (ids != '') {
            $(this).attr("disabled", true);
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Publish Results');
            $.ajax({
                url: '{{ route('publishProgramResults') }}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: ids,
                    programID: program,
                    academicPeriodID: academic,
                    type:type
                },
                success: function (resp) {
                    console.log(resp)
                    $('.success-mail').css('display', 'block');
                    $('.publish-results-board').attr("disabled", false);
                    $('.publish-results-board').html('<i class="fa fa-share"></i> Publish Results');

                    resp.ok && resp.msg ? flash({msg: resp.msg, type: 'success'}) : flash({
                        msg: resp.msg,
                        type: 'danger'
                    });

                }, error: function (xhr, status, error) {
                    flash({msg: error, type: 'danger'})
                    $('.success-mail').css('display', 'block');
                    $('.publish-results-board').attr("disabled", false);
                    $('.publish-results-board').html('<i class="fa fa-share"></i> Publish Results');
                }
            });
        }
    });


    //off script load more results
    function LoadMoreResultsCas(current_page, last_page, per_page, program, academic) {

        // $('.load-more-results').attr("disabled", true);
        // $('.load-more-results').html('<i class="fa fa-spinner fa-spin"></i> Load More');

        var level_name = $('input[name="level_name"]').val();

        $.ajax({
            url: '{{ route('load.more.results.board.Cas') }}',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                current_page: current_page,
                last_page: last_page,
                per_page: per_page,
                program: program,
                academic: academic,
                level_name: level_name
            },
            success: function (data) {
                console.log(data)
                if (data && Object.keys(data).length > 0) {
                    $.each(data, function (academicId, academicData) {
                        // Loop through the students in each academic data
                        $.each(academicData.students, function (studentId, student) {
                            // Create a table row for each course
                            var coursesHtml = '';
                            var i = 1;
                            $.each(student.courses, function (courseCode, course) {
                                coursesHtml += `
                                <tr>
                                    <td>${i++}</td>
                                    <td>${courseCode}</td>
                                    <td>${course.title}</td>
                                    <td>${course.CA}</td>
                                    <td>`;

                                if (true) {
                                    coursesHtml += `<td>
                                  <a onclick="modifyMarks('${student.student_id}','${academicData.program}','${academicData.academic}','${courseCode}')" class="nav-link"><i class="icon-pencil"></i></a>
                                </td>`;
                                }
                                coursesHtml += `</td>
                                </tr>`;
                            });

                            // Append the student data to your table
                            var studentHtml = `
                            <table class="table table-hover table-striped-columns mb-3">
                                <div class="justify-content-between">
                                                    <h5><strong>${student.name}</strong></h5>
                                                    <h5><strong>${student.student_id}</strong></h5>

                                                </div>
                                <thead>
                                    <tr>
                                        <th>S/N</th>
<th>Course Code</th>
                                        <th>Course Name</th>
                                        <th>Total</th>
                                        <th>Modify</th>
                                    </tr>
                                </thead>
                                <tbody>${coursesHtml}</tbody>
                            </table>
                            <p class="bg-success p-3 align-bottom">Comment: ${student.commentData}

                            <input onchange="checkdata()" type="checkbox" name="ckeck_user" value="${1}" class="ckeck_user float-right p-5" data-id="${student.student_id}">
                                <label for="publish" class="mr-3 float-right">Publish</label>
                                </p>
                            <hr>
                        `;
                            updateLoadMoreButton(academicData);
                            if (academicData.last_page === academicData.current_page) {
                                $('.load-more-results').hide();
                            }

                            // Append the studentHtml to a container div
                            $('.loading-more-results').append(studentHtml);
                        });
                        $('#pagenumbers').text('Page '+academicData.current_page+' of '+academicData.last_page)
                    });

                    // Append studentHtml to the resultsContainer
                    flash({msg: 'success', type: 'success'});
                } else {
                    $('.load-more-results').hide();
                    flash({msg: 'No data to display', type: 'warning'});
                }
                // $('.load-more-results-first').hide();
            }, error: function (xhr, status, error) {
                flash({msg: error, type: 'danger'})

                // $('.loading-more-results').attr("disabled", false);
                // $('.loading-more-results').html('<i class="fa fa-share"></i> Load More');
            }
        });
    }

    function LoadMoreResults(current_page, last_page, per_page, program, academic) {

        // $('.load-more-results').attr("disabled", true);
        // $('.load-more-results').html('<i class="fa fa-spinner fa-spin"></i> Load More');

        var level_name = $('input[name="level_name"]').val();

        $.ajax({
            url: '{{ route('load.more.results.board') }}',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                current_page: current_page,
                last_page: last_page,
                per_page: per_page,
                program: program,
                academic: academic,
                level_name: level_name
            },
            success: function (data) {
                console.log(data)
                if (data && Object.keys(data).length > 0) {
                    $.each(data, function (academicId, academicData) {
                        // Loop through the students in each academic data
                        $.each(academicData.students, function (studentId, student) {
                            // Create a table row for each course
                            var coursesHtml = '';
                            $.each(student.courses, function (courseCode, course) {
                                coursesHtml += `
                                <tr>
                                    <td>${courseCode}</td>
                                    <td>${course.title}</td>
                                    <td>${course.CA}</td>
                                    <td>`;
                                // Loop through assessments for the "Exam" value
                                $.each(course.assessments, function (assessmentName, assessment) {
                                    if (assessment.assessment_name === 'Exam') {
                                        coursesHtml += `${assessment.total}`;
                                    }
                                });
                                coursesHtml += `</td>
                                    <td>${course.total}</td>
                                    <td>${course.grade}</td>
                                    <td>`;
                                if (true) {
                                    coursesHtml += `<td>
                                  <a onclick="modifyMarks('${student.student_id}','${academicData.program}','${academicData.academic}','${courseCode}')" class="nav-link"><i class="icon-pencil"></i></a>
                                </td>`;
                                }
                                coursesHtml += `</td>
                                </tr>`;
                            });

                            // Append the student data to your table
                            var studentHtml = `
                            <table class="table table-hover table-striped-columns mb-3">
                                <div class="justify-content-between">
                                                    <h5><strong>${student.name}</strong></h5>
                                                    <h5><strong>${student.student_id}</strong></h5>

                                                </div>
                                <thead>
                                    <tr>
                                        <th>Course Code</th>
                                        <th>Course Name</th>
                                        <th>CA</th>
                                        <th>Exam</th>
                                        <th>Total</th>
                                        <th>Grade</th>
                                        <th>Modify</th>
                                    </tr>
                                </thead>
                                <tbody>${coursesHtml}</tbody>
                            </table>
                            <p class="bg-success p-3 align-bottom">Comment: ${student.commentData}

                            <input onchange="checkdata()" type="checkbox" name="ckeck_user" value="${1}" class="ckeck_user float-right p-5" data-id="${student.student_id}">
                                <label for="publish" class="mr-3 float-right">Publish</label>
                                </p>
                            <hr>
                        `;
                            updateLoadMoreButton(academicData);
                            if (academicData.last_page === academicData.current_page) {
                                $('.load-more-results').hide();
                            }

                            // Append the studentHtml to a container div
                            $('.loading-more-results').append(studentHtml);
                        });
                        $('#pagenumbers').text('Page '+academicData.current_page+' of '+academicData.last_page)
                    });

                    // Append studentHtml to the resultsContainer
                    flash({msg: 'success', type: 'success'});
                } else {
                    $('.load-more-results').hide();
                    flash({msg: 'No data to display', type: 'warning'});
                }
                // $('.load-more-results-first').hide();
            }, error: function (xhr, status, error) {
                flash({msg: error, type: 'danger'})

                // $('.loading-more-results').attr("disabled", false);
                // $('.loading-more-results').html('<i class="fa fa-share"></i> Load More');
            }
        });
    }
function downloadCSVtemplate(){
    let url = '{{ route('template.download')}}';
    var classID = $('input[name="classIDTemplate"]').val();
    var AssessID = $('input[name="AssessIDTemplate"]').val();
        $.ajax({
            url: url, // Replace with the actual route
            method: 'POST',
            dataType: 'json',
            data: {
                classId: classID,
                assessID: AssessID
            },
            success: function (resp) {
                // resp.ok && resp.msg ? flash({msg: resp.msg, type: 'success'}) : flash({
                //     msg: resp.msg,
                //     type: 'danger'
                // });

                if (resp.fileUrl) {
                    // If the response contains a file URL, trigger the download
                    var link = document.createElement('a');
                    link.href = resp.fileUrl;
                    link.download = 'results_upload_template.csv'; // Set the default file name
                    link.click();
                } else if (resp.ok && resp.msg) {
                    flash({ msg: resp.msg, type: 'success' });
                } else {
                    flash({ msg: resp.msg, type: 'danger' });
                }

            }, error: function (xhr, status, error) {
                flash({msg: error, type: 'danger'})
            }
        });
    }
    function updateLoadMoreButton(academicData) {
        // Dynamically set the onclick function with the new academicData
        var button = $('.load-more-results-first');
        button.attr('onclick', `LoadMoreResults('${academicData.current_page}', '${academicData.last_page}', '${academicData.per_page}', '${academicData.program}', '${academicData.academic}', '${academicData.level_name}')`);
    }
    function checkdata(){
        if ($('.ckeck_user').is(':checked')) {
            $('.publish-results-board').removeAttr('disabled');
        }else{
            $('.publish-results-board').attr('disabled', 'disabled');
        }
    }
    $(document).ready(function () {
   /*     var ctxs = document.getElementById("BestBasedOnClass").getContext('2d');
        var data = {!! (isset($grouped) && !empty($grouped) ? $grouped:'00') !!};
        var studentNames = [];
        var totals = [];

        $.each(data, function(key,val){
            studentNames.push(val.first_name);
            totals.push(val.total_score);
        });
            console.log(total)
        var myChart = new Chart(ctxs, {
            type: 'pie',
            data: {
                labels: studentNames,
                datasets: [{
                    label: 'Top performing students',
                    data: totals,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
*/
//second chart

            var ctx = document.getElementById("myChart").getContext('2d');
            var data = {!! (isset($results) && !empty($results)) ? json_encode($results) : '00' !!};
            var studentName = [];
            var total = [];
            var end = 0;
            const sortedStudents = $.map(data.academic.students, function(student, student_id) {
                return { id: student_id, ...student }; // Add student ID to the object
            }).sort((a, b) => b.total - a.total);

            $.each(sortedStudents, function(key,val){
                if (end !== 5){
                    studentName.push(val.name);
                    total.push(val.total);
                    end++;
                }

            });
            console.log(total)
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: studentName,
                    datasets: [{
                        label: 'Top performing students in IT second year',
                        data: total,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

//second chart

            var divID = document.getElementById("analysisCount").getContext('2d');
            var report = {!! (isset($results) && !empty($results)) ? json_encode($results) : '00' !!};
            console.log(report.academic.students);

            var myChart = new Chart(divID, {
                type: 'bar',
                data: {
                    labels: ['Total Students','Clear Pass','Failed one or more courses','MaleClear Pass','female Clear pass','female failed','male failed'],
                    datasets: [{
                        label: 'Exam report BIT Year 1',
                        data: [report.academic.total_students,report.academic.clearPassCount,report.academic.failCount,report.academic.MalesClearPassCount,report.academic.FemaleClearPassCount,
                            report.academic.FailedFemaleCount,report.academic.FailedMaleCount],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

    });
    $(document).on('focus', '.date-pick', function () {
        $(this).datepicker();
    });

    //assessments get levels
    function getLevelAssess(program) {
        var levelId = $('.level_idAss');
        var academic = $('input[name="academic"]').val();
        $.ajax({
            url: '{{ route('get_levelsAsses') }}',
            type: 'POST',
            data: {
                programID: program,
                academicPeriodID: academic
            },
            success: function (resp) {
                console.log(resp)
                levelId.empty();
                levelId.append($('<option>', {
                    value: '',
                    text: ''
                }));
                $.each(resp, function (i, data) {
                    levelId.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });

            }, error: function (xhr, status, error) {
                flash({msg: error, type: 'danger'})
            }
        });
    }

    function getAnalysis(l_id) {

        var levelId = $('.level_id');
        $.ajax({
            url: '{{ route('publishProgramResults') }}',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                leve_id: l_id,
                programID: program,
                academicPeriodID: academic
            },
            success: function (resp) {
                levelId.empty();
                $.each(resp, function (i, data) {
                    levelId.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });

            }, error: function (xhr, status, error) {
                flash({msg: error, type: 'danger'})
            }
        });
    }
</script>
