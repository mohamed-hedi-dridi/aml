<!DOCTYPE html>
<html>
    <head>
        <!-- Basic Page Info -->
        <meta charset="utf-8">
        <title>Via Mobile</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Site favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="/vendors/images/cropped-faviconFinal-50x50.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/vendors/images/cropped-faviconFinal-50x50.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/vendors/images/cropped-faviconFinal-50x50.png">

        <!-- Mobile Specific Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="/vendors/styles/core.css">
        <link rel="stylesheet" type="text/css" href="/vendors/styles/icon-font.min.css">
        <link rel="stylesheet" type="text/css" href="/src/plugins/sweetalert2/sweetalert2.css">
        <link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" type="text/css" href="/src/plugins/datatables/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" type="text/css" href="/vendors/styles/style.css">

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'UA-119386393-1');
        </script>
    </head>
    <body>
        <div class="pre-loader">
            <div class="pre-loader-box">
                <div class="loader-logo"><img src="/vendors/images/LOGOLOADING.png" alt=""></div>
                <div class='loader-progress' id="progress_div">
                    <div class='bar' id='bar1'></div>
                </div>
                <div class='percent' id='percent1'>0%</div>
                <div class="loading-text">
                    Loading...
                </div>
            </div>
        </div>
        @yield('content')

        <!-- js -->
        <script src="/vendors/scripts/core.js"></script>
        <script src="/vendors/scripts/script.min.js"></script>
        <script src="/vendors/scripts/process.js"></script>
        <script src="/vendors/scripts/layout-settings.js"></script>
        <script src="/src/plugins/apexcharts/apexcharts.min.js"></script>
        <script src="/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
        <script src="/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
        <script src="/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
        <script src="/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
        <script src="/vendors/scripts/dashboard.js"></script>
        <!-- buttons for Export datatable -->
        <script src="/src/plugins/datatables/js/dataTables.buttons.min.js"></script>
        <script src="/src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
        <script src="/src/plugins/datatables/js/buttons.print.min.js"></script>
        <script src="/src/plugins/datatables/js/buttons.html5.min.js"></script>
        <script src="/src/plugins/datatables/js/buttons.flash.min.js"></script>
        <script src="/src/plugins/datatables/js/pdfmake.min.js"></script>
        <script src="/src/plugins/datatables/js/vfs_fonts.js"></script>
        <!-- Datatable Setting js -->
        <script src="/vendors/scripts/datatable-setting.js"></script>
        <!-- custom Java script -->
        <script src="/vendors/scripts/custom.js"></script>

        <!--add sweet alert js & css in footer -->
        <script src="/src/plugins/sweetalert2/sweetalert2.all.js"></script>
        <script src="/src/plugins/sweetalert2/sweet-alert.init.js"></script>
    </body>
</html>
