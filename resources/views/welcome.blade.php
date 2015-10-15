@extends('layouts.public')

@section('content')
<div class="home-page">
    @include('partials.publicHeader')

    <section class="section-home">
        <h1>Your team's communication on one platform</h1>
        <p>Your whole team on one platform, with multiple ways of working with projects.</p>
        <a href="/auth/register" class="btn btn-success btn-lg">Sign Up For Free</a>
        <a href="#section-features" class="btn btn-primary btn-lg">Learn More</a>
        <div class="site-preview"></div>
    </section>

    <section class="section-features">
        <h2 class="section-feature-heading">Tab Section</h2>
        <p class="section-feature-description">This is some text inside of a div block.</p>

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
                    <li>
                        <a href="#feature-daily-summary" data-toggle="tab">Daily Summary</a>
                    </li>
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
                <h3>Pjrojects Heading</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique.<br>
Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat.<br>
Aenean faucibus nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
                <img src="http://placehold.it/940x627">
            </div>
            <div class="tab-pane fade" id="feature-tasklist">
                <h3>Tasklist Heading</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique.<br>
Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat.<br>
Aenean faucibus nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
                <img src="http://placehold.it/940x627">
            </div>
            <div class="tab-pane fade" id="feature-daily-summary">
                <h3>Daiuly Heading</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique.<br>
Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat.<br>
Aenean faucibus nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
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
