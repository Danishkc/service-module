<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- plugins:css -->
    <link rel="stylesheet" href="/asset/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="/asset/vendors/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/asset/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/asset/vendors/jquery-bar-rating/css-stars.css">
    <link rel="stylesheet" href="/asset/vendors/mdi/css/materialdesignicons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <!-- textarea-->
    <link rel="stylesheet" href="/asset/vendors/summernote/dist/summernote-bs4.css">
    <link rel="stylesheet" href="/asset/vendors/quill/quill.snow.css">
    <link rel="stylesheet" href="/asset/vendors/simplemde/simplemde.min.css">
    <!-- plugins:select box css -->
    <link rel="stylesheet" href="/asset/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/asset/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">

    <link rel="stylesheet" href="/asset/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="/asset/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- upload images-->
    <link rel="stylesheet" href="/asset/vendors/dropify/dropify.min.css">
    <link rel="stylesheet" href="/asset/vendors/jquery-file-upload/uploadfile.css">
    <link rel="stylesheet" href="/asset/vendors/jquery-tags-input/jquery.tagsinput.min.css">
    <link rel="stylesheet" href="/asset/css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="https://greatindia.biz/upload/business/94fd8f56dcf7c72ac46d029821be2f85.svg" />
    <style type="text/css">
    .select2-container {
        width: 100% !important;
        padding: 0;
    }
    </style>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="sidebar-dark">
    @inertia
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="/asset/vendors/js/vendor.bundle.base.js"></script>
    <!--clipboard -->
    <script src="/asset/vendors/clipboard/clipboard.min.js"></script>
    <!-- textarea-->
    <script src="/asset/vendors/summernote/dist/summernote-bs4.min.js"></script>
    <script src="/asset/vendors/tinymce/tinymce.min.js"></script>
    <script src="/asset/vendors/quill/quill.min.js"></script>
    <script src="/asset/vendors/simplemde/simplemde.min.js"></script>
    <!-- alert js-->
    <script src="/asset/vendors/sweetalert/sweetalert.min.js"></script>
    <script src="/asset/vendors/jquery.avgrund/jquery.avgrund.min.js"></script>
    <!-- table:js -->
    <script src="/asset/vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="/asset/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>

    <script src="/asset/vendors/typeahead.js/typeahead.bundle.min.js"></script>
    <script src="/asset/vendors/select2/select2.min.js"></script>

    <script src="/asset/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- upload file -->
    <script src="/asset/vendors/dropify/dropify.min.js"></script>
    <script src="/asset/vendors/jquery-file-upload/jquery.uploadfile.min.js"></script>
    <script src="/asset/vendors/jquery-tags-input/jquery.tagsinput.min.js"></script>
    <script src="/asset/vendors/dropzone/dropzone.js"></script>
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <script src="/asset/vendors/jquery-bar-rating/jquery.barrating.min.js"></script>
    <!-- inject:js -->
    <script src="/asset/js/off-canvas.js"></script>
    <script src="/asset/js/hoverable-collapse.js"></script>
    <script src="/asset/js/template.js"></script>
    <script src="/asset/js/settings.js"></script>
    <script src="/asset/js/todolist.js"></script>
    <!-- data table:js -->
    <script src="/asset/js/data-table.js"></script>
    <!-- endinject -->
    <script src="/asset/js/formpickers.js"></script>
    <script src="/asset/js/form-addons.js"></script>
    <script src="/asset/js/dropify.js"></script>
    <script src="/asset/js/dropzone.js"></script>
    <script src="/asset/js/file-upload.js"></script>
    <script src="/asset/js/typeahead.js"></script>

    <script src="/asset/js/select2.js"></script>

    <!-- alert js-->
    <script src="/asset/js/avgrund.js"></script>
    <script src="/asset/js/editorDemo.js"></script>
    <script src="/asset/js/modal-demo.js"></script>
    <script src="/asset/js/clipboard.js"></script>
    <script src="/asset/js/profile-demo.js"></script>
    <!-- End custom js for this page-->
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>


</body>

</html>