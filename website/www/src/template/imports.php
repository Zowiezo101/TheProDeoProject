        <!-- Some libaries needed for easier programming -->
        <!-- [jQuery](https://api.jquery.com/) -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
		<!-- [jQuery Color](https://www.jsdelivr.com/package/npm/jquery-color) -->
        <script src="https://cdn.jsdelivr.net/npm/jquery-color@2.2.0/dist/jquery.color.min.js"></script>
		<!-- [Popper.js](https://popper.js.org) -->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" crossorigin="anonymous"></script>
		<!-- [Bootstrap](https://getbootstrap.com/docs/4.0/getting-started/introduction/) -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous" style=""></script>

        <!-- The style sheets -->
		<!-- [Font Awesome](https://fontawesome.com/v4/icons/) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
        <link rel="stylesheet" href="/css/theme_<?= $theme; ?>.css">

        <!-- Fav icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

        <!-- Being able to save and retrieve session settings -->
        <script src="/src/tools/session.js"></script>

        <!-- Accessing the database and other base functions -->
        <script src="/src/tools/database.js"></script>
        <script src="/src/tools/base.js"></script>
        <script src="/src/tools/filter.js"></script>
        
        <!-- DataTables for all the item pages (https://datatables.net/) -->
        <script src="https://cdn.datatables.net/2.1.0/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.1.0/js/dataTables.bootstrap4.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.0/css/dataTables.bootstrap4.min.css" type="text/css">
        
        <!-- Bootstrap slider (https://seiyria.com/bootstrap-slider/) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.js"></script>

        <!-- The translation files -->
        <script src="/locale/translation_<?= filter_input(INPUT_GET, "lang"); ?>.js"></script>

<?php switch($page_id) {
    case "settings":?>
        <!-- Main Summernote library (https://summernote.org/getting-started/) -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
        <?php break;
    case "familytree":
    case "timeline":?>
        <!-- Tools for navigating and downloading the map -->
		<!-- [SVG.js](https://svgjs.dev/docs/3.0/) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/svg.js/3.1.1/svg.min.js"></script>
		<!-- [SVGPanZoom](https://github.com/bumbu/svg-pan-zoom) -->
        <script src="https://cdn.jsdelivr.net/npm/svg-pan-zoom@3.5.0/dist/svg-pan-zoom.min.js"></script>
		<!-- [SVGSaver](https://github.com/Hypercubed/svgsaver) -->
        <script src="https://cdn.jsdelivr.net/npm/svgsaver@0.9.0/browser.min.js"></script>
		<!-- [FileSaver](https://github.com/eligrey/FileSaver.js) -->
        <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>
		<!-- [Canvas.toBlob](https://github.com/eligrey/canvas-toBlob.js) -->
        <script src="https://cdn.jsdelivr.net/npm/canvas-toBlob@1.0.0/canvas-toBlob.min.js"></script>

        <!-- The map maker -->
        <script src="/src/tools/calc.js"></script>
        <script src="/src/tools/draw.js"></script>
        <script src="/src/tools/view.js"></script>
        <?php break;
    case "worldmap":?>
        <!-- Some elements of the map maker -->
        <script src="/src/tools/calc.js"></script>
        <script src="/src/tools/draw.js"></script>
        
		<!-- [Google Maps](https://developers.google.com/maps/documentation/javascript) -->
        <script src="https://maps.googleapis.com/maps/api/js?key=<?= $API_key; ?>&v=weekly"></script>
		<!-- [MarkerClusterer](https://googlemaps.github.io/js-markerclusterer/)
 -->
        <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
        <?php break;
} ?>
