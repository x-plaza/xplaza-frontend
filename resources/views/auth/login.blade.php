<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Xplaza</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_src/plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_src/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_src/dist/css/adminlte.min.css')}}">

</head>
<body class="hold-transition login-page">
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="" class="h1"><b>Ecom</b>xplaza</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

            <form action="{{ url('apiBasedLogin') }}" method="post">
                {{csrf_field()}}
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">

                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
{{--                        <button type="submit" class="login100-form-btn">--}}
{{--                            {{ __('Login') }}--}}
{{--                        </button>--}}
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

{{--            <div class="social-auth-links text-center mt-2 mb-3">--}}
{{--                <a href="#" class="btn btn-block btn-primary">--}}
{{--                    <i class="fab fa-facebook mr-2"></i> Sign in using Facebook--}}
{{--                </a>--}}
{{--                <a href="#" class="btn btn-block btn-danger">--}}
{{--                    <i class="fab fa-google-plus mr-2"></i> Sign in using Google+--}}
{{--                </a>--}}
{{--            </div>--}}
            <!-- /.social-auth-links -->

            <p class="mb-1">
                <a href="#" data-toggle="modal" data-target="#add-modal-md"> Forgot Password ?</a>
{{--                <button type="button" class="btn btn-info"  data-toggle="modal" data-target="#add-modal-md">--}}
{{--                    Forgot Password--}}
{{--                </button>--}}
            </p>


            <div class="modal fade" id="add-modal-md">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Change Password</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="add_response_msg_area"></div>
                            <div class="form-group">
                                <label for="email">Email <span class="otp_message" style="font-weight: bold;color: darkgreen;"></span></label>
                                <div class="input-group ">
                                    <input type="text" class="user_name form-control" placeholder="Enter valid email" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-info get_otp_btn"> <span class="spinner_area"></span> Get OTP </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Enter OTP</label>
                                <input name="otp" type="text" class="form-control otp_code" placeholder="Enter otp (please check email)">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Enter New Password</label>
                                <input name="new_password" type="text" class="form-control new_password" placeholder="Enter new password">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Enter Confirm Password</label>
                                <input name="confirm_password" type="text" class="form-control confirm_password" placeholder="Enter confirm password">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary set_new_password"> <span class="spinner-icon"></span> Update </button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>


        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('admin_src/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('admin_src/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('admin_src/dist/js/adminlte.min.js')}}"></script>

<script language="javascript">

    function validateEmail(email) {var re = /\S+@\S+\.\S+/;return re.test(email);}

    $(document).ready(function() {
        $(document).on('click', '.get_otp_btn', function () {
            $('.add_response_msg_area').empty();
            $('.otp_message').empty();
            var user_name = $('.user_name').val();

            if (user_name == '' || !validateEmail(user_name)) {
                alert("please insert valid email");
                return false;
            }

            var btn = $(this);
            btn.prop('disabled', true);
            $('.spinner_area').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

            $.ajax({
                url: '{{ url('/forgot-password/get-otp') }}',
                type: "POST",
                //dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    user_name: user_name
                },
                success: function (response) {
                    btn.prop('disabled', false);
                    $('.spinner_area').empty();

                    if (response.responseCode == 1) {
                        $('.add_response_msg_area').html('<div class="alert alert-success">\n' +
                            '                                <strong>Success!</strong> ' + response.message + '\n' +
                            '                            </div>');
                        $('.otp_message').html(' ( '+response.message+' ) ');

                    } else {
                        $('.add_response_msg_area').html('<div class="alert alert-danger">\n' +
                            '                                <strong>Error!</strong> ' + response.message + '\n' +
                            '                            </div>');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        });

        $(document).on('click', '.set_new_password', function () {
            $('.add_response_msg_area').empty();
            var user_name = $('.user_name').val();
            var otp_code = $('.otp_code').val();
            var new_password = $('.new_password').val();
            var confirm_password = $('.confirm_password').val();

            if (user_name == '' || !validateEmail(user_name)) {
                alert("please insert valid email");
                return false;
            }
            if (otp_code == '' ) {
                alert("please insert otp");
                return false;
            }
            if (new_password == '' ) {
                alert("please insert new password");
                return false;
            }
            if (confirm_password == '' ) {
                alert("please insert confirm password");
                return false;
            }

            var btn = $(this);
            btn.prop('disabled', true);
            $('.spinner-icon').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

            $.ajax({
                url: '{{ url('/forgot-password/set-new-password') }}',
                type: "POST",
                //dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    user_name: user_name,
                    otp_code: otp_code,
                    new_password: new_password,
                    confirm_password: confirm_password
                },
                success: function (response) {
                    btn.prop('disabled', false);
                    $('.spinner-icon').empty();

                    if (response.responseCode == 1) {
                        $('.add_response_msg_area').html('<div class="alert alert-success">\n' +
                            '                                <strong>Success!</strong> ' + response.message + '\n' +
                            '                            </div>');

                        $('.user_name').val('');
                        $('.otp_code').val('');
                        $('.new_password').val('');
                        $('.confirm_password').val('');

                        $('.alert-success').fadeOut(2000);

                        setTimeout(function () {
                            $('#add-modal-md').modal('hide');
                        }, 2100);

                    } else {
                        $('.add_response_msg_area').html('<div class="alert alert-danger">\n' +
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

</body>
</html>
