@extends('layouts.public')

@section('content')
<div class="home-page">
    @include('partials.publicHeader')

    <header class="auth-header">
        <div class="header-content">
            <div class="header-content-inner">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3 text-left">
                        <div class="panel">
                            <div class="panel-body">
                                <form method="POST" action="/password/email">
                                    {!! csrf_field() !!}

                                    <h2>Forgot Password</h2>

                                    <div class="form-group">
                                        <label>Email</label>
                                        <input class="form-control" type="email" name="email" value="{{ old('email') }}">
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>
@stop
