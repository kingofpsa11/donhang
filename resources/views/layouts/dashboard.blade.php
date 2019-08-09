<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
    {{--<link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">--}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.css"/>
    <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link media="print" href="{{ asset('css/print.css') }}" rel="stylesheet">

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    @if(!auth()->guest())
    <script>
        window.Laravel.userId = <?php echo auth()->user()->id; ?>
    </script>
    @endif
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="#" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>ĐH</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Đơn hàng</b></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->
{{--                    <li class="dropdown messages-menu">--}}
{{--                        <!-- Menu toggle button -->--}}
{{--                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
{{--                            <i class="fa fa-envelope-o"></i>--}}
{{--                            <span class="label label-success">4</span>--}}
{{--                        </a>--}}
{{--                        <ul class="dropdown-menu">--}}
{{--                            <li class="header">You have 4 messages</li>--}}
{{--                            <li>--}}
{{--                                <!-- inner menu: contains the messages -->--}}
{{--                                <ul class="menu">--}}
{{--                                    <li><!-- start message -->--}}
{{--                                        <a href="#">--}}
{{--                                            <div class="pull-left">--}}
{{--                                                <!-- User Image -->--}}
{{--                                            </div>--}}
{{--                                            <!-- Message title and timestamp -->--}}
{{--                                            <h4>--}}
{{--                                                Support Team--}}
{{--                                                <small><i class="fa fa-clock-o"></i> 5 mins</small>--}}
{{--                                            </h4>--}}
{{--                                            <!-- The message -->--}}
{{--                                            <p>Why not buy a new awesome theme?</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li><!-- start message -->--}}
{{--                                        <a href="#">--}}
{{--                                            <div class="pull-left">--}}
{{--                                                <!-- User Image -->--}}
{{--                                            </div>--}}
{{--                                            <!-- Message title and timestamp -->--}}
{{--                                            <h4>--}}
{{--                                                Support Team--}}
{{--                                                <small><i class="fa fa-clock-o"></i> 5 mins</small>--}}
{{--                                            </h4>--}}
{{--                                            <!-- The message -->--}}
{{--                                            <p>Why not buy a new awesome theme?</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <!-- end message -->--}}
{{--                                </ul>--}}
{{--                                <!-- /.menu -->--}}
{{--                            </li>--}}
{{--                            <li class="footer">--}}
{{--                                <a href="javascript:void(0)">Đánh dấu tất cả đã xem</a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
                    <!-- /.messages-menu -->

                    <!-- Notifications Menu -->
                    <li class="dropdown notifications-menu">
                        <!-- Menu toggle button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning hidden">
                            </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header"></li>
                            <li>
                                <!-- Inner Menu: contains the notifications -->
                                <ul class="menu">
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="javascript:void(0)" class="markAllRead">Đánh dấu tất cả đã xem</a>
                                <a href="{{ route('notifications.index')}}" class="viewAll pull-right">Xem tất cả</a>
                            </li>
                        </ul>
                    </li>
                    <!-- Tasks Menu -->
{{--                    <li class="dropdown tasks-menu">--}}
{{--                        <!-- Menu Toggle Button -->--}}
{{--                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
{{--                            <i class="fa fa-flag-o"></i>--}}
{{--                            <span class="label label-danger">9</span>--}}
{{--                        </a>--}}
{{--                        <ul class="dropdown-menu">--}}
{{--                            <li class="header">You have 9 tasks</li>--}}
{{--                            <li>--}}
{{--                                <!-- Inner menu: contains the tasks -->--}}
{{--                                <ul class="menu">--}}
{{--                                    <li><!-- Task item -->--}}
{{--                                        <a href="#">--}}
{{--                                            <!-- Task title and progress text -->--}}
{{--                                            <h3>--}}
{{--                                                Design some buttons--}}
{{--                                                <small class="pull-right">20%</small>--}}
{{--                                            </h3>--}}
{{--                                            <!-- The progress bar -->--}}
{{--                                            <div class="progress xs">--}}
{{--                                                <!-- Change the css width attribute to simulate progress -->--}}
{{--                                                <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"--}}
{{--                                                     aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">--}}
{{--                                                    <span class="sr-only">20% Complete</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <!-- end task item -->--}}
{{--                                </ul>--}}
{{--                            </li>--}}
{{--                            <li class="footer">--}}
{{--                                <a href="#">View all tasks</a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <i class="fa fa-user"></i>
                            <span class="hidden-xs">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">

                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <p>
                                    {{ auth()->user()->name }}
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body">
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Đăng xuất</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- search form (Optional) -->
            <form action="" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
                </div>
            </form>
            <!-- /.search form -->

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">HEADER</li>
                <!-- Optionally, you can add icons to the links -->
                @can('view_users')
                <li><a href="{{ route('users.index') }}"><i class="fa fa-user"></i> <span>Người dùng</span></a></li>
                <li><a href="{{ route('roles.index') }}"><i class="fa fa-link"></i> <span>Phân quyền</span></a></li>
                @endcan

                <li><a href="{{ route('customers.index') }}"><i class="fa fa-user"></i> <span>Khách hàng</span></a></li>
                @role(3)
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Đặt hàng</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('products.create') }}"><i class="fa fa-circle-o"></i>Tạo sản phẩm</a></li>
                        <li><a href="{{ route('prices.create') }}"><i class="fa fa-circle-o"></i>Giá</a></li>
                        <li><a href="{{ route('contracts.create') }}"><i class="fa fa-circle-o"></i>Đơn hàng</a></li>
                        <li><a href="{{ route('output-orders.create') }}"><i class="fa fa-circle-o"></i>Lệnh xuất hàng</a></li>
                        <li><a href=""><i class="fa fa-circle-o"></i> Bảng kê</a></li>
                    </ul>
                </li>
                @endrole
                <li><a href="{{ route('boms.index') }}"><i class="fa fa-link"></i> <span>Định mức</span></a></li>
                <li><a href="{{ route('profit-rate.create') }}"><i class="fa fa-link"></i><span>Tỉ lệ giá</span></a></li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Quản lý kho</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('output-orders.getUndoneOutputOrder') }}"><i class="fa fa-circle-o"></i>Danh sách LXH</a></li>
                        <li><a href="{{ route('good-receive.create') }}"><i class="fa fa-circle-o"></i>Phiếu nhập kho</a></li>
                        <li><a href="{{ route('good-deliveries.create') }}"><i class="fa fa-circle-o"></i>Phiếu xuất kho</a></li>
                        <li><a href="{{ route('inventories.index') }}"><i class="fa fa-circle-o"></i>Tồn kho</a></li>
                        <li><a href="{{ route('inventories.all') }}"><i class="fa fa-circle-o"></i>Tồn xuất nhập</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Quản lý sản xuất</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('manufacturer-order.index') }}"><i class="fa fa-circle-o"></i>Danh sách LSX</a></li>
                        <li><a href="{{ route('manufacturer-notes.index') }}"><i class="fa fa-circle-o"></i>Danh sách Phiếu sản xuất</a></li>
                        <li><a href="{{ route('step-notes.index') }}"><i class="fa fa-circle-o"></i>Phiếu công đoạn</a></li>
                    </ul>
                </li>
            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @yield('title')
                <small>@yield('action')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route(explode('.',Route::currentRouteName())[0] . '.index') }}"><i class="fa fa-dashboard"></i> Danh mục @yield('title')</a></li>
                <li class="active">@yield('action')</li>
            </ol>
        </section>
        <section class="content container-fluid">
            <div id="flash-msg">
                @include('flash::message')
            </div>
            <div class="row hidden">
                <div class="icon col-xs-4">
                    <img src="{{ asset('storage/icon.png') }}" alt="">
                </div>
                <div class="col-xs-8">
                    <p><strong>CÔNG TY TNHH MỘT THÀNH VIÊN CHIẾU SÁNG & THIẾT BỊ ĐÔ THỊ</strong></p>
                    <p>Trụ sở chính: Số 1 - phố Vũ Đức Thận - phường Việt Hưng - quận Long Biên - thành phố Hà Nội</p>
                    <p>Điện thoại: +84-24-38253300;  Fax: +84-24-38262772;  Web: www.hapulico.com; Email: info@hapulico.com</p>
                </div>
            </div>
            @yield('content')
        </section>
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2019 <a href="http://hapulico.com/">Hapulico</a>.</strong> All rights reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane active" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:;">
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:;">
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="pull-right-container">
                    <span class="label label-danger pull-right">70%</span>
                  </span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Some information about this general settings option
                        </p>
                    </div>
                    <!-- /.form-group -->
                </form>
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
    immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- jQuery 3 -->
    {{--<script src="bower_components/jquery/dist/jquery.min.js"></script>--}}
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    <script src="{{ asset('bower_components/select2/dist/js/select2.full.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/af-2.3.3/b-1.5.6/b-colvis-1.5.6/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.5.0/r-2.2.2/rg-1.1.0/rr-1.2.4/sc-2.0.0/sl-1.3.0/datatables.min.js"></script>

    {{--@yield('javascript')--}}

    <script>
        $(function () {
            $('button').on('click', function (e) {
                e.preventDefault();
            });

            // flash auto hide
            $('#flash-msg .alert').not('.alert-danger, .alert-important').delay(3000).slideUp(500);

            $('.markAllRead').on('click', function() {
                $.get('{{ route("users.markAsReadNotifications") }}', function (result) {
                    console.log(result);
                });
            });
        })
    </script>
    @yield('javascript')
</body>
</html>