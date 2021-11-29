<input type="hidden" class="edit_admin_id" value="{{$admin_data->id}}">

<div class="form-group">
    <label for="">Role</label>
    <select name="edit_role_id" class="form-control edit_role_id">
        <option value="">Please select role</option>
        @foreach($roles as $role)
            <option value="{{$role->id}}" @if($role->id == $admin_data->role_id) selected @endif>{{$role->name}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="">Shop</label>
    <select class="select2 edit_shop_id" name="edit_shop_id" multiple="multiple" data-placeholder="Select shop" style="width: 100%;">
        @foreach($shops as $shop)
            <option value="{{$shop->id}}" @if(in_array($shop->id,$shopArr)) selected @endif>{{$shop->name}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="exampleInputEmail1">Full Name</label>
    <input name="edit_name" type="text" value="{{$admin_data->full_name}}" class="form-control edit_name" placeholder="Enter Name">
</div>
<div class="form-group">
    <label for="exampleInputEmail1">Email</label>
    <input name="fixed_user_name" type="text" value="{{$admin_data->user_name}}" class="form-control fixed_user_name" placeholder="Enter Email" readonly>
    <input name="edit_user_name" type="hidden" value="{{$admin_data->user_name}}" class="form-control edit_user_name">
</div>
{{--<div class="form-group">--}}
{{--    <label for="exampleInputEmail1">Password</label>--}}
    <input name="edit_password" type="hidden" value="{{$admin_data->password}}" class="form-control edit_password" placeholder="Enter password">
{{--</div>--}}
{{--<div class="form-group">--}}
{{--    <label for="exampleInputEmail1">Salt</label>--}}
{{--    <input name="edit_salt" type="text" value="{{$admin_data->salt}}" class="form-control edit_salt" placeholder="Enter salt">--}}
{{--</div>--}}