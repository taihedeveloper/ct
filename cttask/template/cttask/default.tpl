<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        {%block name="head"%}
        <meta charset="utf-8"/>
        <title>CT任务中心</title>
        <link rel="shortcut icon" href="static/ico/favicon.html"/>
        <script src="/static/js/libs/jquery.min.js"></script>
        <script src="/static/js/libs/bootstrap.min.js"></script>
        <script src="/static/js/libs/jquery.metisMenu.js"></script>
        <script src="/static/js/libs/sb-admin.js"></script>
        <script src="/static/js/libs/jquery-ui-1.9.2.custom.min.js"></script>
        <script src="/static/js/libs/jquery.form.min.js"></script>
        <script src="/static/js/modules/apollo.min.js?v=3"></script>
        <link rel="stylesheet" href="/static/css/libs/bootstrap.min.css" />
        <link rel="stylesheet" href="/static/css/libs/font-awesome.min.css" />
        <link rel="stylesheet" href="/static/css/libs/sb-admin.css" />
        <link rel="stylesheet" href="/static/css/libs/jquery-ui-1.10.0.custom.css?v=2" />
        <link rel="stylesheet" href="/static/css/base.min.css?v=2" />
        {%/block%}
    </head>
    <body>
        <div id="wrapper">
            {%block name="nav"%}
            <nav class="navbar navbar-default navbar-fixed-top" style="margin-bottom: 0" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/cttask/">CT任务中心</a>
                </div>
                <!-- /.navbar-header -->
                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i>
                            {%$smarty.session.USER%}
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li>
                                <a href="#"><i class="fa fa-key fa-fw"></i>修改密码</a>
                            </li>
                            <li>
                                <a href="/cttask/logout"><i class="fa fa-sign-out fa-fw"></i>退出</a>
                            </li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
                <div class="navbar-default navbar-static-side" role="navigation">
                    <div class="sidebar-collapse">
                        <ul id="side-menu" class="nav">
                            <li>
                                <a class="mis-channel-group" href="/cttask/">
                                    任务列表
                                </a>
                            </li>
                            <li>
                                <a class="mis-channel-group" href="/cttask/group/index">
                                    机器组列表
                                </a>
                            </li>
                            <li>
                                <a class="mis-channel-group" href="/cttask/host/index">
                                    机器列表
                                </a>
                            </li>
                            <li>
                                <a class="mis-channel-group" href="/cttask/taskgroup/index">
                                    任务分组
                                </a>
                            </li>
                            <li>
                                <a class="mis-channel-group" href="/cttask/tasklog/index">
                                    日志列表
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>
            {%/block%}
            <div id="page-wrapper">
                {%block name="content"%}
                {%/block%}
            </div>
        </div>
    </body>
</html>