@extends('frontend.layout._master')
@section('body')

<!-- Style Section -->
@section('style')

@endsection

<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container d-flex align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href=""></a>Reset Password</li>
            </ol>
        </div>
    </nav><!-- End .breadcrumb-nav -->

<div class="login-page pt-4" style="background-image: url('{{ asset('assets') }}/dist/img/photo1.png')">
    <div class="page-content">
        <div class="container">
            <div class="form-box">
                <div class="form-tab">
                    <ul class="nav nav-pills nav-fill" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="signin-tab-2" data-toggle="tab" href="#signin-2" role="tab" aria-controls="signin-2" aria-selected="false">Reset Password</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="" style="display: block;">
                            <form action="{{ route('reset.otp') }}" method="post" style="margin-top: 40px;">
                                @csrf
                                <div class="form-group" hidden>
                                    <label for="singin-email-2">Email address *</label>
                                    <input type="text" class="form-control" id="singin-email-2" name="email" value="{{ $email }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="singin-email-2">Otp *</label>
                                    <input type="text" class="form-control" id="singin-email-2" name="otp" required>
                                </div>
                                <div class="form-footer">
                                    <button type="submit" class="btn btn-outline-primary-2">
                                        <span>Submit</span>
                                        <i class="icon-long-arrow-right"></i>
                                    </button>
                                </div>
                            </form>
                        </div><!-- End .tab-pane -->
                    </div><!-- End .tab-content -->
                </div><!-- End .form-tab -->
            </div><!-- End .form-box -->
        </div>
    </div>
</div>
</main>

<!-- Script Section -->
@section('script')


@endsection
<!-- Body Section -->
@endsection