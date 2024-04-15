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
        <link rel="icon" type="image/png" sizes="32x32" href="/../favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/../favicon-16x16.png">

        <!-- Some basic functions we want everywhere -->
        <script src="/src/tools/base.js"></script>
        <script src="/src/tools/session.js"></script>

        <!-- Accessing the database -->
        <script src="/src/tools/database.js"></script>

        <!-- The translation files -->
        <script src="/locale/translation_<?= filter_input(INPUT_GET, "lang"); ?>.js"></script>
        