@extends('layouts.app')

@section('content')

<div class="container">
    <h2>Register</h2>
    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password-confirm">Confirm Password</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
        </div>

        <div class="form-group mt-3">
    <label>Captcha</label>

    <div class="d-flex align-items-center mb-2">
        <img src="{{ url('/captcha') }}" id="captchaImg" style="border:1px solid #ccc; height:50px;">
        <button type="button" class="btn btn-sm btn-secondary ms-2" id="refresh-btn"
            onclick="document.getElementById('captchaImg').src='{{ url('/captcha') }}?'+Date.now()">
            Refresh
        </button>
    </div>

    <input type="text"
           name="captcha"
           class="form-control @error('captcha') is-invalid @enderror"
           placeholder="Enter Captcha"
           required>

    @error('captcha')
        <span class="invalid-feedback">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>


        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
@endsection

@section('after-scripts')
<script>
     $('#registerForm').on('submit', function (e) {
        e.preventDefault();

        let url = $(this).attr('action');
        let form = document.getElementById('registerForm');
        let formData = new FormData(form);

        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,   // REQUIRED for FormData
            contentType: false,   // REQUIRED for FormData
            success: function (response) {
                window.location.href = "{{ route('home') }}";
            },
            error: function (xhr) {

                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {

                        let input = $('[name="'+key+'"]');
                        input.addClass('is-invalid');

                        input.after(
                            '<span class="invalid-feedback d-block">' +
                            '<strong>' + value[0] + '</strong>' +
                            '</span>'
                        );
                    });
                }

                // Refresh captcha after failure
               $('#refresh-btn').click();
            }
        });
    });
</script>
@endsection
