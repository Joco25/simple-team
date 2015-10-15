@extends('layouts.public')

@section('content')
<div class="home-page">
    @include('partials.publicHeader')

    <section class="section-home">
        <h1>Your Communication<br>On One Platform.</h1>
        <p>Your whole team on one platform, with multiple ways of working with projects.</p>
        <a href="/auth/register" class="btn btn-success btn-lg">Create My Free Account</a>
        <div class="site-preview"></div>
    </section>

    <section class="section-features">
        <h2 class="section-feature-heading">Everyone communicates differently</h2>
        <p class="section-feature-description">We offer several styles of organization to fit your personal preference.</p>

        <div class="row">
            <div class="col-sm-12 centered-pills">
                <!-- Nav tabs -->
                <ul class="nav nav-pills">
                    <li class="active">
                        <a href="#feature-projects" data-toggle="tab">Projects</a>
                    </li>
                    <li>
                        <a href="#feature-tasklist" data-toggle="tab">Tasklist</a>
                    </li>
                    {{-- <li>
                        <a href="#feature-daily-summary" data-toggle="tab">Daily Summary</a>
                    </li> --}}
                    {{-- <li>
                        <a href="#feature-timeline" data-toggle="tab">Timeline</a>
                    </li>
                    <li>
                        <a href="#feature-timeline" data-toggle="tab">Designs</a>
                    </li>
                    <li>
                        <a href="#feature-timeline" data-toggle="tab">Notes</a>
                    </li>
                    <li>
                        <a href="#feature-timeline" data-toggle="tab">Chat</a>
                    </li> --}}
                </ul>
            </div>
        </div>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade in active" id="feature-projects">
                <h3>Clear Project Status</h3>
                <p>
                    Good for constant updates and clearly communicating where you are with a project.<br>
                    Allowing a project lead to glance quickly over the size and progress of a project.
                </p>
                <img src="/img/feature-projects.png">
            </div>
            <div class="tab-pane fade" id="feature-tasklist">
                <h3>Overview Of All Tasks</h3>
                <p>
                    Turns your cards into a list of tasks that allows you to sort and filter easily.<br>
                    Lets you scan quickly over the work that your team is going to be working on.
                </p>
                <img src="/img/feature-tasklist.png">
            </div>
            <div class="tab-pane fade" id="feature-daily-summary">
                <h3>Communicate What You Did Today</h3>
                <p>
                    Keep other team members and managers in the loop with the details of what you worked on for the day.
                </p>
                <img src="http://placehold.it/940x627">
            </div>
        </div>
    </section>

    <section classs="section-previews">

    </section>
</div>
@stop

@section('scripts')
    <script>$('#feature-projects').tab('show')</script>
@stop
