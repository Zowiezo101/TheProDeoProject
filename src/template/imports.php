        <!-- Some libaries needed for easier programming -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-color@2.2.0/dist/jquery.color.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous" style=""></script>

        <!-- The style sheets -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
        <link rel="stylesheet" href="/css/theme_<?= $theme; ?>.css">
        <link rel="stylesheet" href="/css/slider.css">

        <!-- Fav icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

        <!-- Some basic functions we want everywhere -->
        <script src="/src/tools/base.js"></script>
        <script src="/src/tools/session.js"></script>

        <!-- Accessing the database -->
        <script src="/src/tools/database.js"></script>

        <!-- The translation files -->
        <script src="/locale/translation_<?= filter_input(INPUT_GET, "lang"); ?>.js"></script>

<?php
switch($page_id) {
    case "settings":?>
        <!-- Main Summernote library -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script><?php
        break;
    case "familytree":
    case "timeline":?>
        <!-- Tools for navigating and downloading the map -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/svg.js/3.1.1/svg.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/svg-pan-zoom@3.5.0/dist/svg-pan-zoom.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/svgsaver@0.9.0/browser.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/canvas-toBlob@1.0.0/canvas-toBlob.min.js"></script>

        <!-- The map maker -->
        <script src="/src/maps/calc.js"></script>
        <script src="/src/maps/draw.js"></script>
        <script src="/src/maps/view.js"></script><?php
        break;
}
?>
        
        
        <!-- Global variables -->
        <script>
            var base_url = "<?= $base_url; ?>";
            var lang = "<?= filter_input(INPUT_GET, "lang"); ?>";
        </script>
        