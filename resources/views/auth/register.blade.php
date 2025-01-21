<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- link style -->
     <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-login">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card login_card_bg">
                <div class="card_logo">
                    <img src="image/header-logo-1.png" alt="">
                </div>
                <div class="car-header text-center user_image">
                    <h2>{{ __('Register') }}</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3 login_input">
                            <label for="text" class="form-label">{{ __('Name') }}</label>
                            <input type="text" id="email" class="form-control" name="email" value="{{ old('name') }}" placeholder="Name" required autofocus autocomplete="username">
                            @if ($errors->has('name'))
                                <div class="text-danger mt-2">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>
                        <div class="mb-3 login_input">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input type="email" id="email" class="form-control" name="email" placeholder="Email" required autofocus autocomplete="username">
                            @if ($errors->has('email'))
                                <div class="text-danger mt-2">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>

                        <div class="mb-3 login_input">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input type="password" id="password" class="form-control" name="password" required autocomplete="current-password">
                            @if ($errors->has('password'))
                            <div class="text-danger mt-2">
                                {{ $errors->first('password') }}
                            </div>
                            @endif
                        </div>

                        <div class="mb-3 login_input">
                            <label for="password" class="form-label">{{ __('Confirm Password') }}</label>
                            <input type="password" id="password" class="form-control" name="password" required autocomplete="current-password">
                           
                            @if ($errors->has('password_confirmation'))
                            <div class="text-danger mt-2">
                                {{ $errors->first('password_confirmation') }}
                            </div>
                        @endif
                        </div>


                        <div class="d-flex justify-content-between align-items-center login_input">
                            <a href="{{ route('login') }}" class="text-decoration-none">{{ __('Already registered?') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
