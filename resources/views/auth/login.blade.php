
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
@php 
$data=App\Models\CompanyInformations::first();
@endphp
<title>{{(isset($data->company_name)? $data->company_name : 'Company Name')}} | Log in</title>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('storage/adminlte/plugins/fontawesome-free/css/all.min.css')}}">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="{{asset('storage/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('storage/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<!-- JQVMap -->
<link rel="stylesheet" href="{{asset('storage/adminlte/plugins/jqvmap/jqvmap.min.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('storage/adminlte/dist/css/adminlte.min.css')}}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
<div class="login-logo">
    <img src="{{asset('storage/logo/'.(isset($data->logo)? $data->logo : 'logo'))}}" alt="Logo">
{{-- <a href="{{URL::to('/')}}">{{(isset($data->company_name)? $data->company_name : 'Company Name')}}</a> --}}
</div>

<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">{{(isset($data->company_slogan)? $data->company_slogan : 'Company Slogan')}}</p>
        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="input-group mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                <div class="input-group-append">
                    <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                    </div>
                </div>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="input-group mb-3">
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                <div class="input-group-append">
                    <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" id="remember">
                    <label for="remember">
                    Remember Me
                    </label>
                </div>
            </div>
            <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>

            </div>
        </form>
        <div class="social-auth-links text-center mb-3">
        {{-- <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
        <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
        </div> --}}

        <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
        </p>
        {{-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
        </p> --}}
</div>

</div>
</div>

<script src="{{asset('storage/adminlte/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('storage/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('storage/adminlte/dist/js/adminlte.js')}}"></script>
</body>
</html>
