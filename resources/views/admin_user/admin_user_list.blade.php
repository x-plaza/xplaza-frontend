@extends('layouts.admin_layout')

@section('styles')
    @include('layouts.admin_common_css')

    <link rel="stylesheet" type="text/css" href="{{asset('admin_src/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin_src/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
{{--    <link rel="stylesheet" type="text/css" href="{{asset('admin_src/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">--}}

    <link rel="stylesheet" type="text/css" href="{{ asset("admin_src/datatable/dataTables.bootstrap.min.css") }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset("admin_src/datatable/responsive.bootstrap.min.css") }}" />

    <link rel="stylesheet" type="text/css" href="{{ asset("admin_src/plugins/select2/css/select2.min.css") }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset("admin_src/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css") }}" />

    <style>

        .select2-selection__choice{
            background-color: #0f6674 !important;
        }
        .paginate_button.previous{
            background-color: #17a2b8;
            color: white;
            padding: 2px;
            border-radius: 2px;
            cursor: pointer;
        }
        .paginate_button.next{
            background-color: #17a2b8;
            color: white;
            padding: 2px;
            border-radius: 2px;
            cursor: pointer;
        }

        .paginate_button.current{
            background-color: #1a525a;
            color: white;
            padding: 2px;
            border-radius: 2px;
            cursor: no-drop;
        }
        .paginate_button{
            background-color: #17a2b8;
            color: white;
            padding: 2px;
            border-radius: 2px;
            cursor: pointer;
            margin-right: 10px;
        }
    </style>
@endsection

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Admin User List</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Admins</li>
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
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-2 ">
                                        @if(App\Libraries\AclHandler::hasAccess('User Creation','add') == true)
                                        <button type="button" class="btn btn-info"  data-toggle="modal" data-target="#add-modal-lg">
                                            Add New Admin
                                        </button>
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <div class="card-body">

                                <div class="delete_response_msg_area"></div>
                                <div class="table-responsive">
                                    <table id="admin_list"
                                           class="table table-striped table-bordered dt-responsive " cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <!-- /.Add Shop Modal -->
    <div class="modal fade" id="add-modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Admin</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="add_response_msg_area"></div>
                    <div class="form-group">
                        <label for="">Role</label>
                        <select name="role_id" class="form-control role_id">
                            <option value="">Please select role</option>
                            @foreach($roles as $role)
                            <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Shop</label>
                        <select class="select2 shop_id" name="shop_id" multiple="multiple" data-placeholder="Select shop" style="width: 100%;">
                            @foreach($shops as $shop)
                               <option value="{{$shop->id}}">{{$shop->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Full Name</label>
                        <input name="name" type="text" class="form-control name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email <span class="otp_message" style="font-weight: bold;color: darkgreen;"></span></label>
                        <div class="input-group ">
                            <input type="text" class="user_name form-control" placeholder="Enter valid email" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                            <div class="input-group-prepend">
                                <button class="btn btn-info get_otp_btn"> <span class="spinner_area"></span> Get OTP </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Enter OTP</label>
                        <input name="text" type="text" class="form-control confirmation_code" placeholder="Enter otp (please check email)">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Password</label>
                        <input name="password" type="text" class="form-control password" placeholder="Enter password">
                    </div>
{{--                    <div class="form-group">--}}
{{--                        <label for="exampleInputEmail1">Salt</label>--}}
{{--                        <input name="salt" type="text" class="form-control salt" placeholder="Enter salt">--}}
{{--                    </div>--}}
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary store_new_admin"> <span class="spinner-icon"></span> Save </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- /.Edit Shop Modal -->
    <div class="modal fade" id="edit-modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Shop</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="edit_response_msg_area"></div>
                    <div class="edit_data_content"></div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_admin"> <span class="spinner-icon"></span> Update </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('scripts')
    @include('layouts.admin_common_js')

    <script src="{{ asset("admin_src/datatable/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset("admin_src/datatable/dataTables.responsive.min.js") }}"></script>
    <script src="{{ asset("admin_src/datatable/responsive.bootstrap.min.js") }}"></script>
    <script src="{{ asset("admin_src/plugins/select2/js/select2.full.min.js") }}"></script>

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    <script language="javascript">

        $(document).ready(function() {

            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            function getAdminList() {
                $('#admin_list').DataTable({
                    iDisplayLength: 25,
                    processing: true,
                    serverSide: true,
                    lengthChange: false,
                    searching: true,
                    ajax: {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: '{{url("admin-users/get-list")}}',
                        method: 'post'
                    },
                    columns: [
                        {data: 'full_name', name: 'name', searchable: true,orderable: false},
                        {data: 'user_name', name: 'name', searchable: true,orderable: false},
                        {data: 'role_name', name: 'role_name', searchable: true,orderable: false},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    "aaSorting": []
                });
            }

            getAdminList();

            function validateEmail(email) {var re = /\S+@\S+\.\S+/;return re.test(email);}

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
                    url: '{{ url('/admin-users/get-otp') }}',
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


            $(document).on('click', '.store_new_admin', function () {
                $('.add_response_msg_area').empty();
                var role_id = $('.role_id').val();
                var shop_id = $('.shop_id').select2("val");
                var name = $('.name').val();
                var user_name = $('.user_name').val();
                var password = $('.password').val();
                var confirmation_code = $('.confirmation_code').val();

                if (role_id == '') {
                    alert("please select role");
                    return false;
                }
                if (confirmation_code == '') {
                    alert("please enter OTP");
                    return false;
                }
                if (name == '') {
                    alert("please insert name");
                    return false;
                }
                if (password == '') {
                    alert("please insert password");
                    return false;
                }
                if (user_name == '') {
                    alert("please insert email");
                    return false;
                }
                if (shop_id == '') {
                    alert("please insert shop");
                    return false;
                }
                // if (salt == '') {
                //     alert("please insert salt");
                //     return false;
                // }

                var btn = $(this);
                btn.prop('disabled', true);
                $('.spinner-icon').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

                $.ajax({
                    url: '{{ url('/admin-users/add-new-user') }}',
                    type: "POST",
                    //dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        role_id: role_id,
                        shop_id: shop_id,
                        name: name,
                        user_name: user_name,
                        confirmation_code: confirmation_code,
                        password: password
                       // salt: salt
                    },
                    success: function (response) {
                        btn.prop('disabled', false);
                        $('.spinner-icon').empty();

                        if (response.responseCode == 1) {
                            $('.add_response_msg_area').html('<div class="alert alert-success">\n' +
                                '                                <strong>Success!</strong> ' + response.message + '\n' +
                                '                            </div>');


                            $('.name').val('');
                            $('.password').val('');
                          //  $('.salt').val('');

                            $('.alert-success').fadeOut(2000);

                            setTimeout(function () {
                                $('#add-modal-lg').modal('hide');
                            }, 2100);

                            var dataTable = $('#admin_list').dataTable();
                            dataTable.fnDestroy();
                            getAdminList();

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


            $(document).on('click', '.open_admin_modal', function () {
                $('.edit_response_msg_area').empty();
                var admin_id = jQuery(this).data('admin_id');

                var btn = $(this);
                btn.prop('disabled', true);

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ url('/admin-users/get-user-info') }}",
                    data: {
                        _token: $('input[name="_token"]').val(),
                        admin_id: admin_id
                    },
                    success: function (response) {

                        btn.prop('disabled', false);
                        if(response.responseCode == 1){
                            $('.edit_data_content').html(response.html);
                            $('.select2').select2()
                            $('.select2bs4').select2({
                                theme: 'bootstrap4'
                            })
                            $('#edit-modal-lg').modal();
                        }else{

                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Wrong');
                    }
                });

            });


            $(document).on('click', '.update_admin', function () {
                $('.edit_response_msg_area').empty();
                var edit_admin_id = $('.edit_admin_id').val();
                var edit_role_id = $('.edit_role_id').val();
                var edit_shop_id = $('.edit_shop_id').select2("val");
                var edit_name = $('.edit_name').val();
                var edit_user_name = $('.edit_user_name').val();
                var edit_password = $('.edit_password').val();
             //   var edit_salt = $('.edit_salt').val();

                if (edit_role_id == '') {
                    alert("please select role");
                    return false;
                }
                if (edit_name == '') {
                    alert("please insert name");
                    return false;
                }
                if (edit_password == '') {
                    alert("please insert password");
                    return false;
                }
                if (edit_user_name == '') {
                    alert("please insert email");
                    return false;
                }
                if (edit_shop_id == '') {
                    alert("please insert shop");
                    return false;
                }
                // if (edit_salt == '') {
                //     alert("please insert salt");
                //     return false;
                // }

                var btn = $(this);
                btn.prop('disabled', true);
                $('.spinner-icon').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

                $.ajax({
                    url: '{{ url('/admin-users/update-user') }}',
                    type: "POST",
                    //dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        edit_admin_id: edit_admin_id,
                        edit_role_id: edit_role_id,
                        edit_shop_id: edit_shop_id,
                        edit_name: edit_name,
                        edit_user_name: edit_user_name,
                        edit_password: edit_password
                     //   edit_salt: edit_salt
                    },
                    success: function (response) {
                        btn.prop('disabled', false);
                        $('.spinner-icon').empty();

                        if (response.responseCode == 1) {
                            $('.edit_response_msg_area').html('<div class="alert alert-success">\n' +
                                '                                <strong>Success!</strong> ' + response.message + '\n' +
                                '                            </div>');

                            $('.alert-success').fadeOut(2000);

                            setTimeout(function () {
                                $('#edit-modal-lg').modal('hide');
                            }, 2100);

                            var dataTable = $('#admin_list').dataTable();
                            dataTable.fnDestroy();
                            getAdminList();

                        } else {
                            $('.edit_response_msg_area').html('<div class="alert alert-danger">\n' +
                                '                                <strong>Error!</strong> ' + response.message + '\n' +
                                '                            </div>');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Wrong');
                    }
                });
            });


            $(document).on('click', '.deleteAdmin', function () {

                var result = confirm("Want to delete?");
                if (!result) {
                    return false;
                }

                var admin_id = jQuery(this).data('admin_id');

                var btn = $(this);
                btn.prop('disabled', true);

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ url('/admin-users/delete-user') }}",
                    data: {
                        _token: $('input[name="_token"]').val(),
                        admin_id: admin_id
                    },
                    success: function (response) {
                        btn.prop('disabled', false);
                        if(response.responseCode == 1){
                            $('.delete_response_msg_area').html('<div class="alert alert-success">\n' +
                                '                                <strong>Success!</strong> ' + response.message + '\n' +
                                '                            </div>');

                            $('.alert-success').fadeOut(2000);

                            setTimeout(function () {
                                $('#add-modal-lg').modal('hide');
                            }, 2100);

                            var dataTable = $('#admin_list').dataTable();
                            dataTable.fnDestroy();
                            getAdminList();
                        }else{

                        }
                    }
                });

            });

        });

    </script>
@endsection
