@extends('layout.master')

@section('content')
    <div class="checkout-section mt-150 mb-150">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section-title text-center mb-4">
                        <h2>Account Settings</h2>
                        <p>Update your profile details and password.</p>
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card p-4">
                        <form action="{{ route('user.settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3 text-center">
                                <label class="form-label d-block">Profile Avatar</label>
                                <img src="{{ $user->avatar ? asset($user->avatar) : asset('assets/img/users/default-avatar.png') }}"
                                     alt="Avatar" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Change Avatar</label>
                                <input class="form-control" type="file" name="avatar" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                            </div>

                            <button type="submit" class="boxed-btn">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
