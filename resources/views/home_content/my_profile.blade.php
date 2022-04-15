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
                            <label>My Name</label>
                            <input type="text" name="name" value="{{$session_others_array['user_name']}}" readonly>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="input-item">
                            <label>Email Address</label>
                            <input type="email" name="email" value="{{$session_others_array['user_email']}}" readonly>
                        </div>
                    </div>

{{--                    <div class="col-lg-6">--}}
{{--                        <div class="input-item">--}}
{{--                            <label>Mobile Number</label>--}}
{{--                            <input type="text" name="number">--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="col-lg-6">--}}
{{--                        <div class="input-item">--}}
{{--                            <label>Website</label>--}}
{{--                            <input type="text" name="website">--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>

                <div>
                    <button type="submit" class="submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
