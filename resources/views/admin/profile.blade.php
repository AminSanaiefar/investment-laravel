@extends('admin.master')
@section('title', 'profile')

@section('content')
<header class="page-header">
    <h2>User Profile</h2>

    <div class="right-wrapper text-end">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="bx bx-home-alt"></i>
                </a>
            </li>
            <li><span>Profile</span></li>
        </ol>

        <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
    </div>
</header>
<div class="row">
    <div class="col-lg-4 col-xl-3 mb-4 mb-xl-0">

        <section class="card">
            <div class="card-body">
                <div class="thumb-info mb-3">
                    <img id="showProfilePhoto" src="{{ !empty($user->photo) ? asset($user->photo) : asset('upload/no-profile.svg') }}" class="rounded img-fluid" alt="John Doe" >
                    <div class="thumb-info-title">
                        <span class="thumb-info-inner">{{ $user->name }}</span>
                        <span class="thumb-info-type">{{  $user->email }}</span>
                    </div>
                </div>
                <hr class="dotted short">

                <div class="social-icons-list">
                    <a rel="tooltip" data-bs-placement="bottom" target="_blank" href="http://www.facebook.com" data-original-title="Facebook"><i class="fab fa-facebook-f"></i><span>Facebook</span></a>
                    <a rel="tooltip" data-bs-placement="bottom" href="http://www.twitter.com" data-original-title="Twitter"><i class="fab fa-twitter"></i><span>Twitter</span></a>
                    <a rel="tooltip" data-bs-placement="bottom" href="http://www.linkedin.com" data-original-title="Linkedin"><i class="fab fa-linkedin-in"></i><span>Linkedin</span></a>
                </div>
            </div>
        </section>
    </div>
    <div class="col-lg-8 col-xl-9">
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-3">
            @csrf
            <h4 class="mb-3 font-weight-semibold text-dark">Information</h4>
            <div class="row mb-3">
                <div class="form-group @error('address') has-danger @enderror">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ $user->address }}" placeholder="enter your address">
                    @error('address')
                        <span class="text-danger error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 @error('first_name') has-danger @enderror">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $user->first_name }}" placeholder="enter first name">
                    @error('first_name')
                        <span class="text-danger error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="col-md-6 @error('last_name') has-danger @enderror">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $user->last_name }}" placeholder="last last name">
                    @error('last_name')
                        <span class="text-danger error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email">email</label>
                    <input type="text" class="form-control" id="email" value="{{ $user->email }}" readonly="readonly" placeholder="enter email">
                </div>
                <div class="col-md-6 @error('phone') has-danger @enderror">
                    <label for="phone">phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}" placeholder="enter phone number">
                    @error('phone')
                        <span class="text-danger error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="form-group @error('photo') has-danger @enderror">
                    <label for="photo">Profile Photo</label>
                    <input type="file" class="form-control" id="profilePhotoInput" name="photo" placeholder="enter your address">
                    @error('photo')
                        <span class="text-danger error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12 mt-4">
                <button class="btn btn-primary modal-confirm">Save Personal Info</button>
            </div>
        </form>

        <form action="{{ route('admin.profile.update') }}" method="POST" class="p-3">
            <hr class="dotted tall">

            <h4 class="mb-3 font-weight-semibold text-dark">Change Password</h4>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="inputPassword4">New Password</label>
                    <input type="password" class="form-control" id="inputPassword4" placeholder="Password">
                </div>
                <div class="form-group col-md-6 border-top-0 pt-0">
                    <label for="inputPassword5">Re New Password</label>
                    <input type="password" class="form-control" id="inputPassword5" placeholder="Password">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-3">
                    <button class="btn btn-primary modal-confirm">Change Password</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <scri src="{{ asset('backend/js/jquery-4.0.0.min.js') }}"></scri>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#profilePhotoInput').change(function (e) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#showProfilePhoto').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>
@endpush
