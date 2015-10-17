@extends('layouts.public')

@section('content')
<div class="home-page">
    @include('partials.publicHeader')

    <section class="section-home">
        <h1>All The Tools You<br>Need To Make Progress.</h1>
        <p>Multiple forms of project management and all of your projects in one place.</p>
        <a href="/auth/register" class="btn btn-success btn-lg">Create My Free Account</a>
        <div class="site-preview"></div>
    </section>

    <section class="section-features">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Projects - Clearly See Your Progress</h3>
                    <p>
                        Show exactly what stage you are at with a project by allowing someone to quickly glance over the placement of tasks. Prioritize what is most important to your project by putting putting the most important on top and adding impact to your tasks.
                    </p>
                </div>
                <div class="col-sm-6 text-center">
                    <div class="image-preview" style="background-image: url(/img/feature-projects.png)"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 text-center">
                    <div class="image-preview" style="background-image: url(/img/feature-tasklist.png)"></div>
                </div>
                <div class="col-sm-6">
                    <h3>Tasklist - Overview of Your Goals</h3>
                    <p>
                        Turns your cards into a list that allows you to sort and filter by impact or stage.
                        You can scan them quickly to see what issues are being issues are being worked on and what might have been missed.
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <h3>Daily Summaries - What Everyone Did Today</h3>
                    <p>
                        Keep team members informed about what you worked on for the day.  Excellent for adding details about things that may not be immediately related to the projects you're actively working on.
                    </p>
                </div>
                <div class="col-sm-6 text-center">
                    <div class="image-preview" style="background-image: url(/img/feature-projects.png)"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@stop
