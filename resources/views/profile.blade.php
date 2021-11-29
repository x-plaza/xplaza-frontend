@extends('layouts.admin_layout')

@section('styles')
    @include('layouts.admin_common_css')


@endsection

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Profile</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <!-- Left col -->
                    <section class="col-lg-6 connectedSortable">
                        <!-- Custom tabs (Charts with tabs)-->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    My profile
                                </h3>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-12" >
                                        <a href="javascript:void(0)" class="list-group-item AddToInvoice">Name <b
                                                    style="float: right;color: black;">{{Session::get('authData')->user_name}}</b></a>
                                        <a href="javascript:void(0)" class="list-group-item AddToInvoice">Role <b
                                                    style="float: right;color: black;">{{Session::get('authData')->role_name}}</b></a>
                                    </div>
                                </div>

                                <b>Shop:</b>

                                <table class="table table-bordered table-sm">
                                    <thead>
                                    <tr>
                                        <th style="text-align: center;">SL</th>
                                        <th style="text-align: center;">Shop Name</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(Session::get('shopList') as $index =>$shop)
                                    <tr>
                                        <td style="text-align: center;">{{$index+1}}</td>
                                        <td style="text-align: center;">{{$shop->shop_name}}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </section>

                    <section class="col-lg-6 connectedSortable">
                        <!-- Custom tabs (Charts with tabs)-->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Change Password
                                </h3>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="change_pass_resp_message"></div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email</label>
                                    <input name="user_email" value="{{Session::get('authData')->user_name}}" type="text" readonly class="form-control user_email" >
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Old Password</label>
                                    <input name="old_password" type="password" class="form-control old_password">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">New Password</label>
                                    <input name="new_password" type="password" class="form-control new_password">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Confirm Password</label>
                                    <input name="confirm_password" type="password" class="form-control confirm_password">
                                </div>

                                <center>
                                    <button type="button" class="btn btn-primary update_password"> <span class="spinner-icon"></span> Reset Password </button>
                                </center>

                            </div>
                        </div>
                    </section>

                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>


@endsection

@section('scripts')
    @include('layouts.admin_common_js')

    <script language="javascript">

        $(document).ready(function() {

            $(document).on('click', '.update_password', function () {
                $('.change_pass_resp_message').empty();
                var old_password = $('.old_password').val();
                var new_password = $('.new_password').val();
                var confirm_password = $('.confirm_password').val();

                if (old_password == '') {
                    alert("please enter old password");
                    return false;
                }
                if (new_password == '') {
                    alert("please enter new password");
                    return false;
                }
                if (confirm_password == '') {
                    alert("please enter confirm password");
                    return false;
                }

                var btn = $(this);
                btn.prop('disabled', true);
                $('.spinner-icon').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

                $.ajax({
                    url: '{{ url('/user/change-password') }}',
                    type: "POST",
                    //dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        old_password: old_password,
                        confirm_password: confirm_password,
                        new_password: new_password
                    },
                    success: function (response) {
                        btn.prop('disabled', false);
                        $('.spinner-icon').empty();

                        $('.old_password').val('');
                        $('.new_password').val('');
                        if (response.responseCode == 1) {
                            $('.change_pass_resp_message').html('<div class="alert alert-success">\n' +
                                '                                <strong>Success!</strong> ' + response.message + '\n' +
                                '                            </div>');

                            $('.alert-success').fadeOut(2000);

                        } else {
                            $('.change_pass_resp_message').html('<div class="alert alert-danger">\n' +
                                '                                <strong>Error!</strong> ' + response.message + '\n' +
                                '                            </div>');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {

                    }
                });
            });

        });

    </script>
@endsection
