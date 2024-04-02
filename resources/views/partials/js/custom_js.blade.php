<script>
    // Notifications

    @if (session('pop_error'))
        pop({
            msg: '{{ session('pop_error') }}',
            type: 'error'
        });
    @endif

    @if (session('pop_warning'))
        pop({
            msg: '{{ session('pop_warning') }}',
            type: 'warning'
        });
    @endif

    @if (session('pop_success'))
        pop({
            msg: '{{ session('pop_success') }}',
            type: 'success',
            title: 'GREAT!!'
        });
    @endif

    @if (session('flash_info'))
        flash({
            msg: '{{ session('flash_info') }}',
            type: 'info'
        });
    @endif

    @if (session('flash_success'))
        flash({
            msg: '{{ session('flash_success') }}',
            type: 'success'
        });
    @endif

    @if (session('flash_warning'))
        flash({
            msg: '{{ session('flash_warning') }}',
            type: 'warning'
        });
    @endif

    @if (session('flash_error') || session('flash_danger'))
        flash({
            msg: '{{ session('flash_error') ?: session('flash_danger') }}',
            type: 'danger'
        });
    @endif

    {{-- End Notifications --}}

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
        }).then(function(willDelete) {
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
        }).then(function(willDelete) {
            if (willDelete) {
                $('form#item-reset-' + id).submit();
            }
        });
    }

    $('form#ajax-reg').on('submit', function(ev) {
        ev.preventDefault();
        submitForm($(this), 'store');
        $('#ajax-reg-t-0').get(0).click();
    });

    $('form.ajax-pay').on('submit', function(ev) {
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
    $('form.ajax-store').on('submit', function(ev) {
        ev.preventDefault();
        submitForm($(this), 'store');
        var div = $(this).data('reload');
        div ? reloadDiv(div) : '';
    });

    $('form.ajax-update').on('submit', function(ev) {
        ev.preventDefault();
        submitForm($(this));
        var div = $(this).data('reload');
        div ? reloadDiv(div) : '';
    });

    $('.download-receipt').on('click', function(ev) {
        ev.preventDefault();
        $.get($(this).attr('href'));
        flash({
            msg: '{{ 'Download in Progress' }}',
            type: 'info'
        });
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
        req.done(function(resp) {
            resp.ok && resp.msg ?
                flash({
                    msg: resp.msg,
                    type: 'success'
                }) :
                flash({
                    msg: resp.msg,
                    type: 'danger'
                });
            hideAjaxAlert();
            enableBtn(btn);
            formType == 'store' ? clearForm(form) : '';
            scrollTo('body');
            return resp;
        });
        req.fail(function(e) {
            if (e.status == 422) {
                var errors = e.responseJSON.errors;
                displayAjaxErr(errors);
            }
            if (e.status == 500) {
                displayAjaxErr([e.status + ' ' + e.statusText +
                    ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'
                ])
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
        $('#ajax-alert').show().html(
            ' <div class="alert alert-danger border-0 alert-dismissible" id="ajax-msg"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>'
        );
        $.each(errors, function(k, v) {
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
        form.find('.select, .select-search').val([]).select2({
            placeholder: 'Select...'
        });
        form[0].reset();
    }

    function prepareUserinfor(userid) {
        $('.personal-infor span').hide();
        //console.log(userid);
        // $('.personal-infor span').hide();
        var firsname = $('#fname' + userid),
            midname = $('#mname' + userid),
            lastname = $('#lname' + userid),
            gender = $('#gender' + userid),
            email = $('#email' + userid),
            nrc = $('#nrc' + userid),
            passport = $('#passport' + userid),
            dob = $('#dob' + userid),
            marital_status = $('#marital_status' + userid),
            mobile = $('#mobile' + userid),
            street = $('#street' + userid),
            town = $('#town' + userid),
            province_id = $('#province_id' + userid),
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

    function UpdateGeneralinformation(data, countries, programs, towns, provinces, course_levels, intake, inforType,
        username) {


    }

    function UpdateNkininformation(id) {
        var kin_country_id = $('#kin_country_id' + id),
            kin_province_id = $('#kin_province_id' + id),
            kin_town_id = $('#kin_town_id' + id),
            kin_relationship_id = $('#kin_relationship_id' + id),
            telephone = $('#telephone' + id),
            mobile = $('#mobile' + id),
            name = $('#name' + id);
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
        var study_mode_id = $('#study_mode_id-' + key),
            course_level_id = $('#course_level_id-' + key),
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

    }

    //academic classes for assessements
    function getAcClassesPD(ac_id) {
        var url = '<?php echo e(route('class-names', [':id'])); ?>';
        url = url.replace(':id', ac_id);
        var classId = $('#classID');
        var classIds = $('.classID');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function(resp) {
                classId.empty();
                classId.append($('<option>', {
                    value: '',
                    text: 'Choose ...'
                }));
                classIds.empty();
                classIds.append($('<option>', {
                    value: '',
                    text: 'Choose ...'
                }));
                $.each(resp, function(i, data) {
                    classId.append($('<option>', {
                        value: data.id,
                        text: data.course.code + ' - ' + data.course.name
                    }));
                    classIds.append($('<option>', {
                        value: data.id,
                        text: data.course.code + ' - ' + data.course.name
                    }));
                });
            }
        })
    }

    // Bind the function to the change event of the #ac_id input
    $('#ac_id').on('change', function() {
        getAcClassesPDER();
    });

    function getAcClassesPDER() {
        var ac_ids = $('#ac_id').val(); // Assuming ac_id is a multiple select input
        var classId = $('#class_id');
        classId.empty();
        classId.append($('<option>', {
            value: '',
            text: 'Choose ...'
        }));

        // Iterate over each academic period ID
        $.each(ac_ids, function(index, ac_id) {
            var url = '<?php echo e(route('class-names', [':id'])); ?>';
            url = url.replace(':id', ac_id);

            $.ajax({
                dataType: 'json',
                url: url,
                async: false, // Ensures synchronous processing
                success: function(resp) {
                    $.each(resp, function(i, data) {
                        classId.append($('<option>', {
                            value: data.id,
                            text: data.course.code + ' - ' + data.course.name
                        }));
                    });
                }
            });
        });
    }


    function getAcClassesPDERs() {
        var ac_id = $('#ac_id').val();
        var url = '<?php echo e(route('class-names', [':id'])); ?>';
        url = url.replace(':id', ac_id);
        var classId = $('#class_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function(resp) {
                classId.empty();
                classId.append($('<option>', {
                    value: '',
                    text: 'Choose ...'
                }));
                $.each(resp, function(i, data) {
                    classId.append($('<option>', {
                        value: data.id,
                        text: data.course.code + ' - ' + data.course.name
                    }));
                });
            }
        })
    }

    $('.edit-total-link').on('click', function() {

        var row = $(this).closest('tr');
        row.find('.display-mode').hide();
        row.find('.edit-mode').show();
    });
    //});
    function EnterResults(classID, total) {
        let actual = $('#class' + classID).val();

        var displaymode = $('#display-mode' + classID);
        var textContent = displaymode.text();
        var input = $('#class' + classID);

        var totalElement = $(".assess-total");

        // Get the text content and extract the total value
        var totalText = totalElement.text();
        var totalValue = totalText.match(/\d+/);

        var newValues = ((actual / 100) * total).toFixed(2);

        let id = $('#gradeid' + classID).val();

        let url = '{{ route('postedResults.process') }}';
        $.ajax({
            url: url, // Replace with the actual route
            method: 'POST',
            dataType: 'json',
            data: {
                total: newValues,
                id: id,
            },
            success: function(resp) {
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
                resp.ok && resp.msg ? flash({
                    msg: resp.msg,
                    type: 'success'
                }) : flash({
                    msg: resp.msg,
                    type: 'danger'
                });
            },
            error: function(xhr, status, error) {
                flash({
                    msg: error,
                    type: 'danger'
                })
            }
        });
    }

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
            success: function(resp) {
                // Update the display mode with the new value
                displaymode.text(newValues);
                displaymode.show();
                input.hide();

                displaymodedate.text(newValuesendDate);
                displaymodedate.show();
                inputdate.hide();

                resp.ok && resp.msg ? flash({
                    msg: resp.msg,
                    type: 'success'
                }) : flash({
                    msg: resp.msg,
                    type: 'danger'
                });
            },
            error: function(xhr, status, error) {
                flash({
                    msg: error,
                    type: 'danger'
                })
            }
        });
    }

    function downloadCSVtemplate() {
        let url = '{{ route('template.download') }}';
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
            success: function(resp) {
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
                    flash({
                        msg: resp.msg,
                        type: 'success'
                    });
                } else {
                    flash({
                        msg: resp.msg,
                        type: 'danger'
                    });
                }

            },
            error: function(xhr, status, error) {
                flash({
                    msg: error,
                    type: 'danger'
                })
            }
        });
    }

    function modifyMarks(id, student, code, name, grades) {
        console.log(student);

        const user = JSON.parse(student);
        //console.log(user);
        //console.log(typeof user );
        //$('#staticBackdrop').modal('show');
        var modalBody = $('#staticBackdrop .modal-body');
        modalBody.empty();
        const data = JSON.parse(grades);
        //console.log(data);


        // Check if the response is valid and contains data
        if (data && data.length > 0) {
            // Assuming that you want to update the modal body with data from the response

            var title = $('#staticBackdropLabel');
            title.text(user.first_name + ' ' + user.last_name);
            // Clear the existing content in the modal body
            modalBody.empty();
            var assessmentHtml = '<div>';
            assessmentHtml += '<p>Student Number : ' + id + '</p>';
            assessmentHtml += '<p class="title">Code: ' + code + '</p>';
            assessmentHtml += '<p class="header">Title: ' + name + '</p>';
            assessmentHtml += '</div>';

            modalBody.append(assessmentHtml);
            data.forEach(function(assessment) {
                if (parseInt(assessment.student_id) === parseInt(id)) {
                    //var assessmentHtml = '<div>';
                    var assessmentHtml = '<div class="assessment" data-outof="' + assessment.assessment_type
                        .class_assessment.total + '" data-id="' +
                        assessment.id + '" data-code="' + code + '">';
                    assessmentHtml += '<p>Assessment: ' + assessment.assessment_type.name + '</p>';
                    assessmentHtml += '<p>' + assessment.total + ' Out of: ' + assessment.assessment_type
                        .class_assessment.total + '</p>';
                    // assessmentHtml += '<label for="total">Total:</label>';
                    //assessmentHtml += '<input class="form-control total-input" type="number" name="total" value="' + assessment.marks + '">';
                    assessmentHtml +=
                        '<input class="form-control total-input" type="number" name="total" value="0">';
                    assessmentHtml += '<hr>';
                    // Add more fields as needed

                    assessmentHtml += '</div>';

                    modalBody.append(assessmentHtml);
                }
            });
            var assessmentHtml = '<br/>';
            assessmentHtml += ' <div class="form-check">';
            assessmentHtml +=
                '    <input class="form-check-input" value="0" type="radio" name="operation" id="operation1" >';
            assessmentHtml += '        <label class="form-check-label" for="flexRadioDefault1">Subtract</label>';
            assessmentHtml += '</div>';
            assessmentHtml += '<div class="form-check">';
            assessmentHtml +=
                '    <input class="form-check-input" value="1" type="radio" name="operation" id="operation2" >';
            assessmentHtml += '        <label class="form-check-label" for="flexRadioDefault2">Add</label>';
            assessmentHtml += '</div>';
            modalBody.append(assessmentHtml);

            // Show the modal
            $('#staticBackdrop').modal('show');
        } else {
            // Handle the case where there is no data or an error occurred
            flash({
                msg: 'No Assessments found for the course',
                type: 'danger'
            })
        }
    }

    function modifyMarksCAsL(id, student, code, name, grades) {
        console.log(grades);

        function modifyMarksCAsL(id, student, code, name, grades) {
            console.log(student);

            // const user = JSON.parse(student);
            //console.log(user);
            //console.log(typeof user );
            //$('#staticBackdrop').modal('show');
            var modalBody = $('#staticBackdrop .modal-body');
            modalBody.empty();
            //  const data = JSON.parse(grades);


            // const user = JSON.parse(decodeURIComponent(student));
            const data = JSON.parse(decodeURIComponent(grades));
            console.log(data);


            // Check if the response is valid and contains data
            if (data && data.length > 0) {
                // Assuming that you want to update the modal body with data from the response

                var title = $('#staticBackdropLabel');
                title.text(student);
                // Clear the existing content in the modal body
                modalBody.empty();
                var assessmentHtml = '<div>';
                assessmentHtml += '<p>Student Number : ' + id + '</p>';
                assessmentHtml += '<p class="title">Code: ' + code + '</p>';
                assessmentHtml += '<p class="header">Title: ' + name + '</p>';
                assessmentHtml += '</div>';

                modalBody.append(assessmentHtml);
                data.forEach(function(assessment) {
                    //   if (parseInt(assessment.student_id) === parseInt(id)) {
                    //var assessmentHtml = '<div>';
                    var assessmentHtml = '<div class="assessment" data-outof="' + assessment.outof +
                        '" data-id="' +
                        assessment.id + '" data-code="' + code + '">';
                    assessmentHtml += '<p>Assessment: ' + assessment.type + '</p>';
                    assessmentHtml += '<p>' + assessment.total + ' Out of: ' + assessment.outof + '</p>';
                    // assessmentHtml += '<label for="total">Total:</label>';
                    //assessmentHtml += '<input class="form-control total-input" type="number" name="total" value="' + assessment.marks + '">';
                    assessmentHtml +=
                        '<input class="form-control total-input" type="number" name="total" value="0">';
                    assessmentHtml += '<hr>';
                    // Add more fields as needed

                    assessmentHtml += '</div>';

                    modalBody.append(assessmentHtml);
                    //  }
                });
                var assessmentHtml = '<br/>';
                assessmentHtml += ' <div class="form-check">';
                assessmentHtml +=
                    '    <input class="form-check-input" value="0" type="radio" name="operation" id="operation1" >';
                assessmentHtml += '        <label class="form-check-label" for="flexRadioDefault1">Subtract</label>';
                assessmentHtml += '</div>';
                assessmentHtml += '<div class="form-check">';
                assessmentHtml +=
                    '    <input class="form-check-input" value="1" type="radio" name="operation" id="operation2" >';
                assessmentHtml += '        <label class="form-check-label" for="flexRadioDefault2">Add</label>';
                assessmentHtml += '</div>';
                modalBody.append(assessmentHtml);

                // Show the modal
                $('#staticBackdrop').modal('show');
            } else {
                // Handle the case where there is no data or an error occurred
                flash({
                    msg: 'No Assessments found for the course',
                    type: 'danger'
                })
            }
        }
    }

    function modifyMarksExam(id, student, code, name, grades) {
        ///const user = JSON.parse(student);
        //console.log(user);
        //$('#staticBackdrop').modal('show');
        var modalBody = $('#staticBackdrop .modal-body');
        modalBody.empty();
        const data = JSON.parse(grades);
        console.log(data);

        // Check if the response is valid and contains data
        if (data && data.length > 0) {
            // Assuming that you want to update the modal body with data from the response

            var title = $('#staticBackdropLabel');
            title.text(student);
            // Clear the existing content in the modal body
            modalBody.empty();
            var assessmentHtml = '<div>';
            assessmentHtml += '<p>Student Number : ' + id + '</p>';
            assessmentHtml += '<p class="title">Code: ' + code + '</p>';
            assessmentHtml += '<p class="header">Title: ' + name + '</p>';
            assessmentHtml += '</div>';

            modalBody.append(assessmentHtml);
            data.forEach(function(assessment) {
                if (assessment.student_id === parseInt(id)) {
                    //var assessmentHtml = '<div>';
                    var assessmentHtml = '<div class="assessment" data-outof="' + assessment.outof +
                        '" data-id="' +
                        assessment.id + '" data-code="' + code + '">';
                    assessmentHtml += '<p>Assessment: Exam</p>';
                    assessmentHtml += '<p>' + assessment.exam + ' Out of: ' + assessment.outof + '</p>';
                    // assessmentHtml += '<label for="total">Total:</label>';
                    //assessmentHtml += '<input class="form-control total-input" type="number" name="total" value="' + assessment.marks + '">';
                    assessmentHtml +=
                        '<input class="form-control total-input" type="number" name="total" value="0">';
                    assessmentHtml += '<hr>';
                    // Add more fields as needed

                    assessmentHtml += '</div>';

                    modalBody.append(assessmentHtml);
                }
            });
            var assessmentHtml = '<br/>';
            assessmentHtml += ' <div class="form-check">';
            assessmentHtml +=
                '    <input class="form-check-input" value="0" type="radio" name="operation" id="operation1" >';
            assessmentHtml += '        <label class="form-check-label" for="flexRadioDefault1">Subtract</label>';
            assessmentHtml += '</div>';
            assessmentHtml += '<div class="form-check">';
            assessmentHtml +=
                '    <input class="form-check-input" value="1" type="radio" name="operation" id="operation2" >';
            assessmentHtml += '        <label class="form-check-label" for="flexRadioDefault2">Add</label>';
            assessmentHtml += '</div>';
            modalBody.append(assessmentHtml);

            // Show the modal
            $('#staticBackdrop').modal('show');
        } else {
            // Handle the case where there is no data or an error occurred
            flash({
                msg: 'No Assessments found for the course',
                type: 'danger'
            })
        }
    }

    function modifyMarksExamAjax(id, student, code, name, grades) {

        var modalBody = $('#staticBackdrop .modal-body');
        modalBody.empty();

        const user = JSON.parse(decodeURIComponent(student));
        const data = JSON.parse(decodeURIComponent(grades));
        console.log(data);

        // Check if the response is valid and contains data
        if (data && data.length > 0) {
            // Assuming that you want to update the modal body with data from the response

            var title = $('#staticBackdropLabel');
            title.text(user.first_name + ' ' + user.last_name);
            // Clear the existing content in the modal body
            modalBody.empty();
            var assessmentHtml = '<div>';
            assessmentHtml += '<p>Student Number : ' + id + '</p>';
            assessmentHtml += '<p class="title">Code: ' + code + '</p>';
            assessmentHtml += '<p class="header">Title: ' + name + '</p>';
            assessmentHtml += '</div>';

            modalBody.append(assessmentHtml);
            //data.each(function (index, assessment) {
            data.forEach(function(assessment) {
                if (assessment.student_id === parseInt(id)) {
                    //var assessmentHtml = '<div>';
                    var assessmentHtml = '<div class="assessment" data-outof="' + assessment.outof +
                        '" data-id="' +
                        assessment.id + '" data-code="' + code + '">';
                    assessmentHtml += '<p>Assessment: Exam</p>';
                    assessmentHtml += '<p>' + assessment.exam + ' Out of: ' + assessment.outof + '</p>';
                    // assessmentHtml += '<label for="total">Total:</label>';
                    //assessmentHtml += '<input class="form-control total-input" type="number" name="total" value="' + assessment.marks + '">';
                    assessmentHtml +=
                        '<input class="form-control total-input" type="number" name="total" value="0">';
                    assessmentHtml += '<hr>';
                    // Add more fields as needed

                    assessmentHtml += '</div>';

                    modalBody.append(assessmentHtml);
                }
            });
            var assessmentHtml = '<br/>';
            assessmentHtml += ' <div class="form-check">';
            assessmentHtml +=
                '    <input class="form-check-input" value="0" type="radio" name="operation" id="operation1" >';
            assessmentHtml += '        <label class="form-check-label" for="flexRadioDefault1">Subtract</label>';
            assessmentHtml += '</div>';
            assessmentHtml += '<div class="form-check">';
            assessmentHtml +=
                '    <input class="form-check-input" value="1" type="radio" name="operation" id="operation2" >';
            assessmentHtml += '        <label class="form-check-label" for="flexRadioDefault2">Add</label>';
            assessmentHtml += '</div>';
            modalBody.append(assessmentHtml);

            // Show the modal
            $('#staticBackdrop').modal('show');
        } else {
            // Handle the case where there is no data or an error occurred
            flash({
                msg: 'No Assessments found for the course',
                type: 'danger'
            })
        }
    }

    function StrMod4All(classID, exam) {
        var modalBody = $('#staticBackdrop .modal-body');
        modalBody.empty();
        var url = '<?php echo e(route('update.assessments')); ?>';
        $('#assesmentID').val('');

        $.ajax({
            url: url, // Replace with the actual route
            method: 'POST',
            dataType: 'json',
            data: {
                classID: classID,
                exam: exam
            },
            success: function(resp) {
                // Update the display mode with the new value
                console.log(resp)
                // Check if the response is valid and contains data
                if (resp && 'id' in resp) {
                    // Assuming that you want to update the modal body with data from the response

                    var title = $('#staticBackdropLabel');
                    title.text(resp.course.code + ' - ' + resp.course.name);
                    // Clear the existing content in the modal body
                    modalBody.empty();
                    var assessmentHtml = '<div>';
                    assessmentHtml += '<p class="title">Code: ' + resp.course.code + '</p>';
                    assessmentHtml += '<p class="header">Title: ' + resp.course.name + '</p>';
                    assessmentHtml += '</div>';

                    modalBody.append(assessmentHtml);
                    resp.class_assessments.forEach(function(assessment) {
                        //var assessmentHtml = '<div>';
                        var assessmentHtml = '<div class="assessment" data-apid="' + resp
                            .academic_period_id + '" data-code="' +
                            resp.course.code + '" data-id="' + assessment.assessment_type.id +
                            '"data-outof="' + assessment.total + '">';
                        assessmentHtml += '<p>Assessment: ' + assessment.assessment_type.name +
                            '</p>';
                        assessmentHtml += '<p>Out of: ' + assessment.total + '</p>';
                        // assessmentHtml += '<label for="total">Total:</label>';
                        assessmentHtml +=
                            '<input class="form-control total-input" type="number" name="total" value="0">';
                        assessmentHtml += '<hr>';
                        // Add more fields as needed

                        assessmentHtml += '</div>';

                        modalBody.append(assessmentHtml);
                    });
                    var assessmentHtml = '<br/>';
                    assessmentHtml += ' <div class="form-check">';
                    assessmentHtml +=
                        '    <input class="form-check-input" value="0" type="radio" name="operation" id="operation1" >';
                    assessmentHtml +=
                        '        <label class="form-check-label" for="flexRadioDefault1">Subtract</label>';
                    assessmentHtml += '</div>';
                    assessmentHtml += '<div class="form-check">';
                    assessmentHtml +=
                        '    <input class="form-check-input" value="1" type="radio" name="operation" id="operation2" >';
                    assessmentHtml +=
                        '        <label class="form-check-label" for="flexRadioDefault2">Add</label>';
                    assessmentHtml += '</div>';
                    modalBody.append(assessmentHtml);

                    // Show the modal
                    $('#staticBackdrop').modal('show');
                    $('#assesmentID').val('');
                } else {
                    // Handle the case where there is no data or an error occurred
                    console.log('No data found in the response or an error occurred.');
                    flash({
                        msg: 'No Assessments found for the course',
                        type: 'danger'
                    })
                    $('#assesmentID').val('');
                }
                //resp.ok && resp.msg ? flash({msg: resp.msg, type: 'success'}) : flash({msg: resp.msg, type: 'danger'});
            },
            error: function(xhr, status, error) {
                flash({
                    msg: error,
                    type: 'danger'
                })
                $('#assesmentID').val('');
            }

        });
        $('#assesmentID').val('');;

    }


    function SubmitData() {
        // Create an array to store the updated "Total" and "key" values
        var updatedAssessments = [];
        var modalBody = $('#staticBackdrop .modal-body');
        var assessmentContainers = modalBody.find('.assessment');
        assessmentContainers.each(function() {
            var assessment = {};
            assessment.total = $(this).find('.total-input').val();
            //assessment.total = $(this).find('.total-input').val();
            assessment.id = $(this).data('id');
            assessment.code = $(this).data('code');
            assessment.apid = $(this).data('apid');
            assessment.outof = $(this).data('outof');
            updatedAssessments.push(assessment);
        });

        let url = '{{ route('BoardofExaminersUpdateResults') }}';
        var radioButtons = $('input[type="radio"]');
        var lastClickedRadioButton = radioButtons.filter(':checked');
        var operation = lastClickedRadioButton.val();
        if (operation === '' || !lastClickedRadioButton.is(':checked')) {
            flash({
                msg: 'Check the operation to undertake',
                type: 'danger'
            });
        } else {
            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'json',
                data: {
                    updatedAssessments: updatedAssessments,
                    operation: operation

                },
                success: function(resp) {
                    console.log(resp)
                    if (resp.ok === true) {
                        $('#staticBackdrop').modal('hide');

                    } else {
                        $('#staticBackdrop').modal('hide');
                    }
                    resp.ok && resp.msg ? flash({
                        msg: resp.msg,
                        type: 'success'
                    }) : flash({
                        msg: resp.msg,
                        type: 'danger'
                    });
                },
                error: function(xhr, status, error) {
                    flash({
                        msg: error,
                        type: 'danger'
                    })
                }
            });
            $('#staticBackdrop').modal('hide');
        }
        // You now have an array containing objects with "Total" and "key" values
        console.log(updatedAssessments);
        // $('#staticBackdrop').modal('hide');

        // You can send this data to the server using an AJAX request or perform any other action as needed.
    }


    $('.user-all').change(function(e) {
        var value = $('.user-all:checked').val();
        if (value == 1) {
            $('input[name="ckeck_user"]').prop('checked', true);
            $('.publish-results-board').removeAttr('disabled');
        } else {
            $('input[name="ckeck_user"]').prop('checked', false);
            $('.publish-results-board').attr('disabled', 'disabled');
        }
    });


    $("input[name='ckeck_user']").change(function() {
        if ($("input[name='ckeck_user']:checked").length > 0) {
            $('.publish-results-board').removeAttr('disabled');
        } else {
            $('.publish-results-board').attr('disabled', 'disabled');
        }
    });

    $('.publish-results-board').click(function(e) {
        e.preventDefault();
        var ids = [];

        $.each($('input[name="ckeck_user"]:checked'), function() {
            ids.push($(this).data('id'));
        });
        var academic = $('input[name="academic"]').val();
        var type = $('input[name="type"]').val();
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
                    academicPeriodID: academic,
                    type: type
                },
                success: function(resp) {
                    console.log(resp)
                    $('.success-mail').css('display', 'block');
                    $('.publish-results-board').attr("disabled", false);
                    $('.publish-results-board').html(
                        '<i class="fa fa-share"></i> Publish Results');

                    resp.ok && resp.msg ? flash({
                        msg: resp.msg,
                        type: 'success'
                    }) : flash({
                        msg: resp.msg,
                        type: 'danger'
                    });

                },
                error: function(xhr, status, error) {
                    flash({
                        msg: error,
                        type: 'danger'
                    })
                    $('.success-mail').css('display', 'block');
                    $('.publish-results-board').attr("disabled", false);
                    $('.publish-results-board').html(
                        '<i class="fa fa-share"></i> Publish Results');
                }
            });
        }
    });

    function LoadMoreResultsCas(current_page, last_page, per_page, program, academic, level) {

        // $('.load-more-results').attr("disabled", true);
        // $('.load-more-results').html('<i class="fa fa-spinner fa-spin"></i> Load More');


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
                level: level
            },
            success: function(response) {
                console.log(response)
                if (response && Object.keys(response.students).length > 0) {
                    $.each(response.students, function(studentId, student) {
                        var coursesHtml = '';
                        $.each(student.courses, function(courseId, course) {
                            coursesHtml += `
                <tr>
                    <td>0</td>
                    <td>${course.course_details.course_code}</td>
                    <td>${course.course_details.course_title}</td>
                    <td>
                        <table class="table table-bordered table-hover table-striped">
                            <tbody>
                                <tr>
                                    <td>Assessment Type</td>
                                    <td>Total</td>
                                    <td>Out of</td>
                                    <td>Grade</td>
                                </tr>`;
                            $.each(course.course_details.student_grades, function(index,
                                grades) {
                                coursesHtml += `
                                <tr>
                                    <td>${grades.type}</td>
                                    <td>${grades.total}</td>
                                    <td>${grades.outof}</td>
                                    <td>${grades.grade}</td>
                                </tr>`;
                            });
                            coursesHtml += `   </tbody>
                        </table>
                    </td>
                <td><a onclick="modifyMarksCAsL('${studentId}','${student.name}','${course.course_details.course_code}','${course.course_details.course_title}','${encodeURIComponent(JSON.stringify(course.course_details.student_grades))}')"
                                                               class="nav-link"><i class="icon-pencil"></i></a></td>
                </tr>`;
                        });

                        var studentHtml = `
            <table class="table table-hover table-striped-columns mb-3">
                <div class="justify-content-between">
                    <h5>
                        <strong>${student.name}</strong>
                        <p>${studentId}</p>
                    </h5>
                </div>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Assessments</th>
                         <th>Modify</th>
                    </tr>
                </thead>
                <tbody>${coursesHtml}</tbody>
            </table>

            <p class="bg-success p-3 align-bottom">Comment: ${student.calculated_grade.comment}
            <input onchange="checkdata()" type="checkbox" name="ckeck_user" value="${1}" class="ckeck_user float-right p-5" data-id="${studentId}">
                <label for="publish" class="mr-3 float-right">Publish</label>
            </p>
            <hr>
        `;
                        $('.loading-more-results').append(studentHtml);
                        $('#pagenumbers').text('Page ' + response.current_page + ' of ' +
                            response.last_page)
                    });

                    if (response.last_page === response.current_page) {
                        $('.load-more-results').hide();
                    }

                    flash({
                        msg: 'success',
                        type: 'success'
                    });
                } else {
                    $('.load-more-results').hide();
                    flash({
                        msg: 'No data to display',
                        type: 'warning'
                    });
                }
                // $('.load-more-results-first').hide();
            },
            error: function(xhr, status, error) {
                flash({
                    msg: error,
                    type: 'danger'
                })

                // $('.loading-more-results').attr("disabled", false);
                // $('.loading-more-results').html('<i class="fa fa-share"></i> Load More');
            }
        });
    }

    function LoadMoreResults(current_page, last_page, per_page, program, academic, level) {

        // $('.load-more-results').attr("disabled", true);
        // $('.load-more-results').html('<i class="fa fa-spinner fa-spin"></i> Load More');
        //console.log(current_page, last_page, per_page, program, academic, level);


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
                level: level
            },
            success: function(response) {
                console.log(response.data)

                if (response && Object.keys(response.students).length > 0) {
                    $.each(response.students, function(studentId, student) {
                        var coursesHtml = '';
                        $.each(student.courses, function(courseId, course) {
                            coursesHtml += `
                <tr>
                    <td>0</td>
                    <td>${course.course_details.course_code}</td>
                    <td>${course.course_details.course_title}</td>
                    <td>
                        <table class="table table-bordered table-hover table-striped">
                            <tbody>
                                <tr>
                                    <td>CA</td>
                                    <td>Exam</td>
                                    <td>Total</td>
                                    <td>Grade</td>
                                </tr>`;
                            if (course.course_details.student_grades[0].exam !== "NE") {
                                coursesHtml += `
                                <tr>
                                    <td>${course.course_details.student_grades[0].ca}</td>
                                    <td>${course.course_details.student_grades[0].exam + ' out of ' + course.course_details.student_grades[0].outof}</td>
                                    <td>${course.course_details.student_grades[0].total_sum}</td>
                                    <td>${course.course_details.student_grades[0].grade}</td>
                                </tr>`;
                            }
                            coursesHtml += `   </tbody>
                        </table>
                    </td>
                </tr>`;
                        });

                        var studentHtml = `
            <table class="table table-hover table-striped-columns mb-3">
                <div class="justify-content-between">
                    <h5>
                        <strong>${student.name}</strong>
                        <p>${studentId}</p>
                    </h5>
                </div>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Assessments</th>
                        <th>Modify</th>
                    </tr>
                </thead>
                <tbody>${coursesHtml}</tbody>
            </table>

            <p class="bg-success p-3 align-bottom">Comment: ${student.calculated_grade.comment}
            <input onchange="checkdata()" type="checkbox" name="ckeck_user" value="${1}" class="ckeck_user float-right p-5" data-id="${studentId}">
                <label for="publish" class="mr-3 float-right">Publish</label>
            </p>
            <hr>
        `;
                        $('.loading-more-results').append(studentHtml);
                        $('#pagenumbers').text('Page ' + response.current_page + ' of ' +
                            response.last_page)
                    });

                    if (response.last_page === response.current_page) {
                        $('.load-more-results').hide();
                    }

                    flash({
                        msg: 'success',
                        type: 'success'
                    });
                } else {
                    $('.load-more-results').hide();
                    flash({
                        msg: 'No data to display',
                        type: 'warning'
                    });
                }

            },
            error: function(xhr, status, error) {
                flash({
                    msg: error,
                    type: 'danger'
                })
            }
        });
    }

    function updateLoadMoreButton(academicData, program, academic, level) {
        // Dynamically set the onclick function with the new academicData
        var button = $('.load-more-results-first');
        button.attr('onclick',
            `LoadMoreResults('${academicData.current_page}', '${academicData.last_page}', '${academicData.per_page}', '${program}', '${academic}', '${level}')`
        );
    }

    function updateLoadMoreButtonCAs(academicData, program, academic, level) {
        // Dynamically set the onclick function with the new academicData
        var button = $('.load-more-results-first-cas');
        button.attr('onclick',
            `LoadMoreResultsCas('${academicData.current_page}', '${academicData.last_page}', '${academicData.per_page}', '${program}', '${academic}', '${level}')`
        );
    }

    function checkdata() {
        if ($('.ckeck_user').is(':checked')) {
            $('.publish-results-board').removeAttr('disabled');
        } else {
            $('.publish-results-board').attr('disabled', 'disabled');
        }
    }

    function CloseModal() {
        $('#staticBackdrop').modal('hide');
    }

    // Handle select input on enrolments report pages
    const getProgramsByAcademicPeriod = (academicPeriodIds, programsSelector) => {
        $.ajax({
            url: `/academic-periods/${academicPeriodIds}/programs`,
            type: "GET",
            dataType: "json",
            success: (data) => {
                $(programsSelector).empty();
                $(programsSelector).append(`<option disabled>Choose programs</option>`)

                $.each(data, (_, value) => {
                    $(programsSelector).append(
                        `<option value="${value.id}">${value.name}</option>`);
                });
            }
        });
    }

    // Mask NRC input
    const maskNRC = () => {
        $('#nrc').on('input', function() {
            var nrc = $(this).val().replace(/\D/g, '');

            if (nrc.length > 6) {
                nrc = nrc.substring(0, 6) + '/' + nrc.substring(6);
            }

            if (nrc.length > 9) {
                nrc = nrc.substring(0, 9) + '/' + nrc.substring(9);
            }

            $(this).val(nrc);
        });
    }

    $(document).ready(() => {
        $('#academic-period-ids').change(function() {
            const academicPeriodIds = $(this).val();

            if (academicPeriodIds.length > 0) {
                getProgramsByAcademicPeriod(academicPeriodIds, '#program-ids');
            } else {
                $('#program-ids').empty();
            }
        });

        maskNRC();
    });

    // Inline Editale Table Rows
    $.fn.editable.defaults.mode = 'inline';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    })

    $(document).ready(() => {
        // Editable marks (student.show)
        $('.editable').editable();
    })
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
                <button type="button" class="btn btn-secondary closeModalButton" onclick="CloseModal()"
                    id="closeModalButton" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" onclick="SubmitData()" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
