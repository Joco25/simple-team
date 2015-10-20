<!DOCTYPE html>
<html lang="en" ng-app="simple.team" ng-cloak ng-controller="AppCtrl as appCtrl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>simple.team - Simple and effective project management for teams</title>

    <!-- Bootstrap Core CSS -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
	<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>

    <!-- Angular CSS -->
    <link href='//cdnjs.cloudflare.com/ajax/libs/angular-loading-bar/0.7.1/loading-bar.min.css' rel='stylesheet' type='text/css'>
    <link href="//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/css/selectize.default.min.css" rel="stylesheet" type='text/css'>

    <link rel="stylesheet" type="text/css" href="http://rawgit.com/angular-ui-tree/angular-ui-tree/master/dist/angular-ui-tree.min.css">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/angular.gantt/1.2.7/angular-gantt.min.css">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/angular.gantt/1.2.7/angular-gantt-plugins.min.css">


    <!-- Custom CSS -->
	<link href="/css/app.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="simple-page">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <a href="../" class="navbar-brand">simple.team</a>
            <ul class="nav navbar-nav navbar-right">
                <li ng-class="{ active: appCtrl.state.current.name.indexOf('projects') > -1 }">
                    <a ui-sref="projects">Projects</a>
                </li>
                <li ng-class="{ active: appCtrl.state.current.name.indexOf('tasklist') > -1 }">
                    <a ui-sref="tasklist">Tasklist</a>
                </li>
                <!-- <li ng-class="{ active: appCtrl.state.current.name.indexOf('timeline') > -1 }">
                    <a ui-sref="timeline">Timeline</a>
                </li>
                <li ng-class="{ active: appCtrl.state.current.name.indexOf('dailySummary') > -1 }">
                    <a ui-sref="dailySummary">Daily Summary</a>
                </li> -->
                <!-- <li><a ui-sref="chat">Chat</a></li>
                <li><a ui-sref="notes">Notes</a></li>
                <li><a ui-sref="one-use-notes">View Once Notes</a></li>
                <li><a ui-sref="designer">Designer</a></li> -->

                <li class="dropdown">
                    <a class="pointer dropdown-toggle" data-toggle="dropdown">{{ appCtrl.authUser.team.name || 'Select a team...' }} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li ng-repeat="team in appCtrl.teams" ng-click="appCtrl.setCurrentTeam(team)">
                            <a href="#">{{ team.name }}</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="fa fa-bars"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a ui-sref="settings.account">Account</a></li>
                        <li><a ui-sref="settings.teams">Teams</a></li>
                        <li class="divider"></li>
                        <li><a href="/auth/logout">Sign Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div ui-view></div>

    <script src="//cdnjs.cloudflare.com/ajax/libs/showdown/1.2.3/showdown.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/3.10.1/lodash.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.5/angular.js"></script>




    <script src="http://cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script>
    <script src="http://cdn.jsdelivr.net/angular.moment/latest/angular-moment.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/moment-range/2.0.3/moment-range.min.js"></script>
    <script src="http://rawgit.com/JimLiu/angular-ui-tree/master/dist/angular-ui-tree.js"></script>
    <script src="http://rawgit.com/ganarajpr/angular-dragdrop/master/draganddrop.js"></script>
    <script src="http://rawgit.com/marcj/css-element-queries/master/src/ElementQueries.js"></script>
    <script src="http://rawgit.com/marcj/css-element-queries/master/src/ResizeSensor.js"></script>
    <script src="//cdn.jsdelivr.net/angular.gantt/1.2.7/angular-gantt.min.js"></script>
    <script src="//cdn.jsdelivr.net/angular.gantt/1.2.7/angular-gantt-plugins.min.js"></script>




    <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.4/ui-bootstrap-tpls.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/danialfarid-angular-file-upload/9.0.7/ng-file-upload-all.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular-loading-bar/0.8.0/loading-bar.min.js"></script>

    <script>
        var ENV = {
            authUser: {
                id: <?php echo Auth::user()->id ?>,
                team: <?php echo json_encode(Auth::user()->team) ?>,
                name: <?php echo json_encode(Auth::user()->name) ?>,
                email: <?php echo json_encode(Auth::user()->email) ?>
            },
            s3BucketAttachmentsUrl: <?php echo json_encode(env('S3_BUCKET_ATTACHMENTS_URL')) ?>,
            teams: <?php echo json_encode(Auth::user()->teams) ?>
        }
    </script>
    <script src="/js/app.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/standalone/selectize.min.js"></script>
    <?php if ($app->environment('production')): ?>
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-53112500-6', 'auto');
          ga('send', 'pageview');
        </script>
    <?php endif; ?>
</body>
</html>
