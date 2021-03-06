@extends('common.master')
@push('styles')
<link rel="stylesheet" href="{{ URL::asset('css/datatables.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/custom-datatables.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/form-utilities.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/flag-icon.min.css') }}">

<style>
    .content-header {
        color: purple;
    }
</style>

@endpush
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>{{ ( ($show == '0') ? 'Edit' : 'Viewing' ) }} '{{ $resource->name }}'</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Administration</a></li>
                <li class="active">Edit {{$route}} Panel</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="box">


                <!-- /.box-header -->
                <div class="box-body">
                    {!! Form::model($resource, ['class' => 'form-horizontal edit-role','id'=>'edit_role_form']) !!}

                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'Name: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        </div>
                        {!! $errors->first('name', '<div class="col-sm-6 col-sm-offset-3 text-danger">:message</div>') !!}
                    </div>

                    <div class="form-group {{ $errors->has('slug') ? 'has-error' : ''}}">
                        {!! Form::label('slug', 'Slug: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::text('slug', null, ['class' => 'form-control']) !!}
                        </div>
                        {!! $errors->first('slug', '<div class="col-sm-6 col-sm-offset-3 text-danger">:message</div>') !!}
                    </div>

                    <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                        {!! Form::label('description', 'Description: ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                        </div>
                        {!! $errors->first('description', '<div class="col-sm-6 col-sm-offset-3 text-danger">:message</div>') !!}
                    </div>

                    @if ($route == "role")
                        <div class="form-group">
                            {!! Form::label('special', 'Special Access: ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class="col-sm-6">
                                {!! Form::select('special', array('all-access' => 'All Access', 'no-access' => 'No Access'), null, ['placeholder' => 'No special access.', 'class' => 'form-control'] ) !!}
                            </div>
                            {!! $errors->first('special', '<div class="col-sm-6 col-sm-offset-3 text-danger">:message</div>') !!}
                        </div>
                    @endif

                    @if ($show == '0')
                        @if ( Shinobi::can( config('admin.acl.'.$route.'.edit', false) ) )
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-3">
                                    {!! Form::submit('Update '.$route, ['class' => 'btn btn-primary form-control updateRole']) !!}
                                </div>
                            </div>

                        @else

                            <div class="col-sm-6 col-sm-offset-3 alert alert-danger lead">
                                <i class="fa fa-exclamation-triangle fa-1x"></i> You are not permitted to {{ ( ($show == '0') ? 'edit' : 'view' ) }} {{$route}}s.
                            </div>

                        @endif
                    @else
                        <div class="form-group">
                            <div class="col-sm-6 col-sm-offset-3 text-center">
                                <div class="pull-right">
                                    <i class="fa fa-pencil"></i>
                                    <a href="{{ route($route.'.edit', $resource->id) }}" title="Edit this {{ $route }}">Edit</a>
                                </div>

                                @if ($route == "role")
                                    <div class="pull-left">
                                        <i class="fa fa-user"></i>
                                        <a href="{{ route($route.'.user.edit', $resource->id) }}" class="text-center" title="Users with this {{ $route }}">Users</a>
                                    </div>

                                    <i class="fa fa-key"></i>
                                    <a href="{{ route($route.'.permission.edit', $resource->id) }}" title="Edit Permissions for this {{ $route }}">Permissions</a>
                                @else
                                    <div class="pull-left">
                                        <i class="fa fa-key"></i>
                                        <a href="{{ route($route.'.role.edit', $resource->id) }}" title="Roles for this permission">Roles</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    {!! Form::close() !!}
                </div>
            </div>
            <!-- /.box-body -->
            <!-- /.box-footer-->
            <!-- /.box -->
        </section>
        <!-- /.content -->
    </div>

@endsection
@push('scripts')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{URL::asset('js/loadingoverlay.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('js/loadingoverlay_progress.min.js')}}"></script>
<script>
  $(document).ready(function () {
    $('.updateRole').on('click', function (event) {
      $('.edit-role').validate({
        rules: {
          name: {
            required: true,
            minlength: 2
          }
        },
        messages: {
          name: {
            required: 'Please enter name',
            minlength: 'Your name must consist of at least 2 characters'
          }
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
          var serializedData = $('#edit_role_form').serialize()

          // Disabled form elements will not be serialized.
          $inputs.prop('disabled', true)

          var url = '/{{$route}}/{{$resource->id}}';

          // Fire off the request to /form.php
          request = $.ajax({
            url: url,
            type: 'PATCH',
            data: serializedData,
          })

          // Callback handler that will be called on success
          request.done(function (response, textStatus, jqXHR) {
            console.log(response)
            if (response === null || response === undefined || response.length <= 0 || response['status_code']===500) {
              $.LoadingOverlay('hide')
              swal(
                'Sorry...',
                'Something went wrong, Try Again!',
                'error'
              ).catch(swal.noop)
            } else if (response['status_code'] === 200) {
              $.LoadingOverlay('hide')
              var message = response['success'];
              swal({
                title: 'Successfully Submitted!',
                text: message,
                type: 'success'
              }).then(function () {
                // Refresh table
                $('#edit_role_form').trigger('reset')
                window.location = '/{{$route}}';
              }).catch(swal.noop)
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
          });

        }

      });
    });
  });
</script>
@endpush
