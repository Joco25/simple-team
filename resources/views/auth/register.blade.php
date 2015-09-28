@extends('layouts.public')

@section('content')
<div class="home-page">
    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="/">Simple.Team</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="/auth/login">Login</a>
                    </li>
                    <li>
                        <a href="/auth/register">Register</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <header class="auth-header">
        <div class="header-content">
            <div class="header-content-inner">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3 text-left">
                        <div class="panel">
                            <div class="panel-body">
                                <form method="POST" action="/auth/register">
                                    {!! csrf_field() !!}

                                    <h2>Create Account</h2>

                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control">
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
@stop
