@extends('common.master')
@push('styles')
<link rel="stylesheet" href="{{ URL::asset('css/datatables.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/custom-datatables.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/form-utilities.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/jquery-Ui.min.css') }}">
<style>
    .content-header {
        color: purple;
    }
    .error{
        color:red
    }
</style>

@endpush
@section('content')

    <!-- Page heading ends -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('employee.titles.create')
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Employees</a></li>
                <li class="active">Create</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="box">

                <div class="box-body">
                    <!-- /.box-header -->
                    <div class="pull-right">
                        <a class="btn btn-link" id="new-user" data-toggle="modal" data-target="#createDesignationModal">Create Designation</a>

                    </div>
                    <div class="col-md-offset-1">
                        <a href="/view/employees/list" class="btn btn-default">BACK</a>
                    </div>

                    <div class="col-md-offset-2 col-md-8">
                        <form role="form" method="POST" action="" id="create_employee_form">
                            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" id="status" name="status" value="ACTIVE">
                            <div class="form-group">
                                <label class="">Name <span required_field>*</span></label>
                                <div>
                                    <input type="text" class="form-control input-lg" name="name" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">EMail <span required_field>*</span></label>
                                <div>
                                    <input type="email" class="form-control input-lg" name="email" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Phone Number <span required_field>*</span></label>
                                <div>
                                    <input type="text" id="mobile" class="form-control input-lg" name="mobile">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Address <span required_field>*</span></label>
                                <div>
                                    <input type="text" id="address" class="form-control input-lg" name="address">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">ID Number <span required_field>*</span></label>
                                <div>
                                    <input type="text" class="form-control input-lg" name="id_number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Date of Employment <span required_field>*</span></label>
                                <div>
                                    <input id="date_of_employment" type="text" class="form-control input-lg"
                                           name="date_of_employment">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Salary<span
                                            class="optional_field"> (optional)</span></label>
                                <div>
                                    <input type="text" class="form-control input-lg" name="salary">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label class="control-label">Designation <span class="required_field">*</span></label>
                                <div>
                                    <select id="designationId" class="form-control input-lg"
                                            name="designation_id"></select>
                                </div>
                            </div>


                            <div id="loader"></div>
                            <button class="btn btn-primary btn-lg" id="createEmployee" type="submit" value="Submit">
                                Submit
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
        @include('employees.modals.create-designation-modal')
    </div>

@endsection

@push('scripts')
<script src="{{ asset('js/jquery-Ui.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{URL::asset('js/loadingoverlay.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/loadingoverlay_progress.min.js')}}"></script>

<script type="text/javascript">
  $(document).ready(function () {
    $('#date_of_employment').datepicker({dateFormat: 'yy-mm-dd'})

    $('#createEmployee').on('click', function () {
      $('#create_employee_form').validate({
        rules: {
          name: {
            required: true,
            minlength: 2,
            maxlength: 255,
          },
          mobile: {
            required: true,
            minlength: 10,
            maxlength: 10
          },
          id_number: {
            required: true,
            minlength: 2,
            maxlength: 15
          },
          address: {
            required: true,
            minlength: 2,
            maxlength: 255
          },
          date_of_employment: {
            required: true,
          },
          designation_id: {
            required: true,
          },
          email: {
            required: true,
            email: true
          },
        },
        messages: {
          name: {
            required: 'Please enter name',
            minlength: 'Your name must consist of at least 2 characters'
          },
          mobile: {
            required: 'Please enter your phone number',
            minlength: 'Your phone number must be 10 numbers',
            maxlength: 'Your phone number must be 10 numbers'
          },
          address: {
            required: 'Please enter your Address',
            minlength: 'Your Address must be at least 2 characters long',
            maxlength: 'Your Address must be at most 255 characters long'
          },
          id_number: {
            required: 'Please enter your Id Number',
            minlength: 'Your Id Number must be 8 characters long',
            maxlength: 'Your location must be 8 characters long'
          },
          date_of_payment: {
            required: 'Please enter Date of employment',
          },
          designation_id: {
            required: 'Please select designation',
          },
          email: 'Please enter a valid email address',
        },
        submitHandler: function (form) {
          var request
          $.LoadingOverlay('show')

          if (request) {
            request.abort()
          }

          // setup some local variables
          var $form = $(this)

          // Let's select and cache all the fields
          var $inputs = $form.find('input, select, button, textarea')
          // Serialize the data in the form
          var serializedData = $('#create_employee_form').serialize()

          // Disabled form elements will not be serialized.
          $inputs.prop('disabled', true)

          var url = '/create/employee'

          // Fire off the request to /form.php
          request = $.ajax({
            url: url,
            type: 'post',
            data: serializedData,
          })

          // Callback handler that will be called on success
          request.done(function (response, textStatus, jqXHR) {
            console.log(response)
            if (response === null || response === undefined || response.length <= 0) {
              $.LoadingOverlay('hide')
              swal(
                'Sorry...',
                'Something went wrong, Try Again!',
                'error'
              ).catch(swal.noop)
            } else if (response['status_code'] === 200) {
              $.LoadingOverlay('hide')

              swal({
                title: 'Successfully Submitted!',
                text: 'Thank You!',
                type: 'success'
              }).then(function () {
                // Refresh table
                $('#create_employee_form').trigger('reset')
                window.location='/view/employees/list'
              }).catch(swal.noop)
            } else if (response['status_code'] === 412) {
              swal(
                'Sorry...',
                'The employee with the email entered exists. Try Again!',
                'error'
              )
            }
          })

          // Callback handler that will be called on failure
          request.fail(function (jqXHR, textStatus, errorThrown) {
            $.LoadingOverlay('hide')
            if (jqXHR.responseJSON) {
              var errors = '<h3 class=\'space-down-15\'>Please correct them and try again.</h3>'
                + '<div class=\'list-group shrink-text-dot-8em push-down-10\'>'

              for (var key in jqXHR.responseJSON) {

                if (key == 'errors') {
                  errors += '<div class=\'list-group-item text-left text-danger\'><strong>'
                    + jqXHR.responseJSON['errors'] + '</strong></div>'
                } else {
                  errors += '<div class=\'list-group-item text-left text-danger\'><strong>'
                    + jqXHR.responseJSON[key][0] + '</strong></div>'
                }
              }

              errors += '</div>'

              swal({
                title: 'Sorry. We found some errors',
                html: errors,
                type: 'error',
                showCancelButton: true,
                showConfirmButton: false,
                cancelButtonText: 'Close',
              }).catch(swal.noop)
            } else {
              swal({
                title: 'Sorry...',
                text: 'Something went wrong, Please try again!',
                type: 'error',
                showCancelButton: true,
                showConfirmButton: false,
                cancelButtonText: 'Close',
              }).catch(swal.noop)
              $.LoadingOverlay('hide')
            }
          })

          // Callback handler that will be called regardless
          // if the request failed or succeeded
          request.always(function () {
            //hide loader
            $.LoadingOverlay('hide')

            // Reenable the inputs
            $inputs.prop('disabled', false)
          })

        }
      })
    })
    event.preventDefault()
    $.ajax({
      type: 'GET',
      url: '/get/designations/data',
      success: function (data) {
        if (!(data.length > 0)) {
          $('#designationId').append('<option value="">Nothing To Select</option>')
        } else {
          // Parse the returned json data
          var opts = (data)
          $('#designationId').append('<option value="">Choose Designation</option>')
          // Use jQuery's each to iterate over the opts value
          $.each(opts, function (i, d) {
            // You will need to alter the below to get the right values from your json object.  Guessing that d.id / d.modelName are columns in your carModels data
            $('#designationId').append('<option value="' + d.id + '">' + d.designation_name + '</option>')
          })
        }

      }

    })

    //create designation
    $('#createDesignation').on('click', function () {
      $('#create_designation_form').validate({
        rules: {
          designation_name: {
            required: true,
            minlength: 2,
            maxlength: 255,
          },
        },
        messages: {
          designation_name: {
            required: 'Please enter designation name',
            minlength: 'Your designation name must consist of at least 2 characters'
          },
        },
        submitHandler: function (form) {
          var request
          $.LoadingOverlay('show')

          if (request) {
            request.abort()
          }

          // setup some local variables
          var $form = $(this)

          // Let's select and cache all the fields
          var $inputs = $form.find('input, select, button, textarea')
          // Serialize the data in the form
          var serializedData = $('#create_designation_form').serialize()

          // Disabled form elements will not be serialized.
          $inputs.prop('disabled', true)

          var url = '/create/designation'

          // Fire off the request to /form.php
          request = $.ajax({
            url: url,
            type: 'post',
            data: serializedData,
          })

          // Callback handler that will be called on success
          request.done(function (response, textStatus, jqXHR) {
            if (response === null || response === undefined || response.length <= 0) {
              $.LoadingOverlay('hide')
              swal(
                'Sorry...',
                'Something went wrong, Try Again!',
                'error'
              ).catch(swal.noop)
            } else if (response['status_code'] === 200) {
              $.LoadingOverlay('hide')

              swal({
                title: 'Successfully Submitted!',
                text: 'Thank You!',
                type: 'success'
              }).then(function () {
                // Refresh table
                location.reload();
                $('#create_designation_form').trigger('reset')
                $("#createDesignationModal").modal('hide');

              }).catch(swal.noop)
            } else if (response['status_code'] === 412) {
              swal(
                'Sorry...',
                'The designation with the name entered exists. Try Again!',
                'error'
              )
            }
          })

          // Callback handler that will be called on failure
          request.fail(function (jqXHR, textStatus, errorThrown) {
            $.LoadingOverlay('hide')
            if (jqXHR.responseJSON) {
              var errors = '<h3 class=\'space-down-15\'>Please correct them and try again.</h3>'
                + '<div class=\'list-group shrink-text-dot-8em push-down-10\'>'

              for (var key in jqXHR.responseJSON) {

                if (key == 'errors') {
                  errors += '<div class=\'list-group-item text-left text-danger\'><strong>'
                    + jqXHR.responseJSON['errors'] + '</strong></div>'
                } else {
                  errors += '<div class=\'list-group-item text-left text-danger\'><strong>'
                    + jqXHR.responseJSON[key][0] + '</strong></div>'
                }
              }

              errors += '</div>'

              swal({
                title: 'Sorry. We found some errors',
                html: errors,
                type: 'error',
                showCancelButton: true,
                showConfirmButton: false,
                cancelButtonText: 'Close',
              }).catch(swal.noop)
            } else {
              swal({
                title: 'Sorry...',
                text: 'Something went wrong, Please try again!',
                type: 'error',
                showCancelButton: true,
                showConfirmButton: false,
                cancelButtonText: 'Close',
              }).catch(swal.noop)
              $.LoadingOverlay('hide')
            }
          })

          // Callback handler that will be called regardless
          // if the request failed or succeeded
          request.always(function () {
            //hide loader
            $.LoadingOverlay('hide')

            // Reenable the inputs
            $inputs.prop('disabled', false)
          })

        }
      })
    })
  });


</script>

@endpush
