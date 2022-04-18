<div class="col-lg-12">
    <div class="my-account-box">
{{--        <div class="my-account-header">--}}
{{--            <h6>My Account</h6>--}}
{{--        </div>--}}
        <div class="my-account-body">
            <form action="#" class="eflux-login-form">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="input-item">
                            <label>Email</label>
                            <input type="text" name="email" class="email" value="{{$email}}" readonly>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-item">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="first_name" value="{{$profile_data->first_name}}" >
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-item">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="last_name" value="{{$profile_data->last_name}}" >
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="input-item">
                            <label>House No</label>
                            <input type="text" name="house_no" class="house_no" value="{{$profile_data->house_no}}" >
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-item">
                            <label>Street Name</label>
                            <input type="text" name="street_name" class="street_name" value="{{$profile_data->street_name}}" >
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-item">
                            <label>Post code</label>
                            <input type="text" name="postcode" class="postcode" value="{{$profile_data->postcode}}" >
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-item">
                            <label>Area</label>
                            <input type="text" name="area" class="area" value="{{$profile_data->area}}" >
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-item">
                            <label>City</label>
                            <input type="text" name="city" class="city" value="{{$profile_data->city}}" >
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-item">
                            <label>Country</label>
                            <input type="text" name="country" class="country" value="{{$profile_data->country}}" >
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-item">
                            <label>Mobile</label>
                            <input type="text" name="mobile_no" class="mobile_no" value="{{$profile_data->mobile_no}}" >
                        </div>
                    </div>


                </div>

                <div class="profile_message_section"></div>

                <div>
                    <button type="button" class="submit updateProfileBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
