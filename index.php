<!DOCTYPE html>
<html>   
<?php 
    // This needs to be started at the very beginning
    if(session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    
    // The page id, this is taken from the link we are currently on. If there is no page id given, go to the home page
    $id = filter_input(INPUT_GET,'page') !== null ? filter_input(INPUT_GET,'page') : "home";
    
    // All the basic stuff that is needed to make the website running
    // The javascript version is imported seperately
    require "src/tools/server/base.php";

    $dropdown = "";
    // Get the dropdown menu that needs to have it's button activated
    if (in_array($id, ['books', 'events', 'peoples', 'locations', 'specials', 'search'])) {
        $dropdown = "database";
    }
    
    // The theme that is used for this page
    switch($id) {
        case 'home':
        case 'search':
        case 'settings':
            $theme = "purple";
            break;
        
        case 'books':
        case 'aboutus':
            $theme = "pink";
            break;
        
        case 'events':
        case 'timeline':
            $theme = "orange";
            break;
        
        case 'peoples':
        case 'familytree':
            $theme = "red";
            break;
        
        case 'locations':
        case 'worldmap':
            $theme = "blue";
            break;
        
        case 'specials':
        case 'contact':
            $theme = "green";
            break;
        
        default:
            $theme = "purple";
            break;
    }
    
    $required = "src/pages/".$id.".php";
    require $required;
?>
    
    <head>
        <!-- Name shown on the tab -->
        <title><?php echo $dict["globals.prodeo_database"] ?></title>
        
        <!-- Some extra information used for viewing -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- External Javascript and script files -->
    
        <!-- Some libaries needed for easier programming -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-color@2.2.0/dist/jquery.color.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous" style=""></script>

        <!-- Extra functionality per page -->
        <script src="/src/pages/<?php echo $id; ?>.js"></script>
<?php if (in_array($id, ["books", "events", "peoples", "locations", "specials", "familytree", "timeline", "worldmap"])) { ?>
        
        <!-- For the sidebar used with many pages -->
        <script src="/src/tools/client/items.js"></script>
<?php } ?>
        
        <!-- The style sheets -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
        <link rel="stylesheet" href="/css/theme_<?php echo $theme; ?>.css">
      
        <!-- Fav icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="/../favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/../favicon-16x16.png">
<?php if (in_array($id, ["search"])) { ?>
        
        <!-- Bootstrap slider -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" type="text/css">
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
        <link rel="stylesheet" href="/css/slider_<?php echo $theme; ?>.css">
<?php } else if (in_array($id, ["familytree", "timeline"])) { ?>
        
        <!-- Tools for navigating and downloading the map -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/svg.js/3.1.1/svg.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/svg-pan-zoom@3.5.0/dist/svg-pan-zoom.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/svgsaver@0.9.0/browser.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/canvas-toBlob@1.0.0/canvas-toBlob.min.js"></script>
  
        <!-- The map maker -->
        <script src="/src/tools/map/calc.js"></script>
        <script src="/src/tools/map/draw.js"></script>
        <script src="/src/tools/map/view.js"></script>
<?php } else if (in_array($id, ["worldmap"])) { ?>
        
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyFq1pKyxT7asd87wAgr83_yWIrT-sz7E&v=weekly"></script>
        <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
<?php } else if (in_array($id, ["settings"])) { ?>
        
        <!-- Main Summernote library -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<?php } ?>
        
        <!-- Some basic functions we want everywhere -->
        <script src="/src/tools/client/base.js"></script>
        <script src="/src/tools/client/session.js"></script>

        <!-- Accessing the database -->
        <script src="/src/tools/client/database.js"></script>

        <!-- The translation files -->
        <script src="/locale/translation_<?php echo filter_input(INPUT_GET, "lang"); ?>.js"></script>
        
        <script>

            window.onload = function() {
                // Set some default stuff
                <?php echo "onLoad".ucfirst($id)."()"; ?>;
            };
        </script>
    </head>
    
    <body class="d-flex flex-column min-vh-100">
        <nav class="navbar navbar-expand-md navbar-light bg-light shadow">
            <a class="navbar-brand text-primary" href="<?php echo setParameters("home")?>">
                <img src="/img/logo.bmp" class="d-inline-block align-top rounded" alt="" width="75" height="75" style="">
                <b class="text-secondary" style=""> <?php echo $dict["globals.prodeo_database"] ?> </b>
            </a>
            <span class="navbar-text text-secondary"><?php echo $dict["globals.prodeo_slogan"] ?></span>
            <div class="container"> 
                <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbar4" style="">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar4">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item shadow-none <?php echo $id == "home" ? "rounded bg-primary" : "" ?>" style=""> 
                            <a class="nav-link active" href="<?php echo setParameters("home")?>"><?php echo $dict["navigation.home"] ?></a> 
                        </li>
                        <li class="nav-item dropdown <?php echo $dropdown == "database" ? "rounded bg-primary" : "" ?>"> 
                            <a class="nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?php echo $dict["navigation.database"] ?> </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item <?php echo $id == "books" ? "bg-primary" : "" ?>" href="<?php echo setParameters("books")?>"><?php echo $dict["navigation.books"] ?></a>
                                <a class="dropdown-item <?php echo $id == "events" ? "bg-primary" : "" ?>" href="<?php echo setParameters("events")?>"><?php echo $dict["navigation.events"] ?></a>
                                <a class="dropdown-item <?php echo $id == "peoples" ? "bg-primary" : "" ?>" href="<?php echo setParameters("peoples")?>"><?php echo $dict["navigation.peoples"] ?></a>
                                <a class="dropdown-item <?php echo $id == "locations" ? "bg-primary" : "" ?>" href="<?php echo setParameters("locations")?>"><?php echo $dict["navigation.locations"] ?></a>
                                <a class="dropdown-item <?php echo $id == "specials" ? "bg-primary" : "" ?>" href="<?php echo setParameters("specials")?>"><?php echo $dict["navigation.specials"] ?></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item <?php echo $id == "search" ? "bg-primary" : "" ?>" href="<?php echo setParameters("search")?>"><?php echo $dict["navigation.search"] ?></a>
                            </div>
                        </li>
                        <li class="nav-item <?php echo $id == "timeline" ? "rounded bg-primary" : "" ?>"> <a class="nav-link" href="<?php echo setParameters("timeline")?>"><?php echo $dict["navigation.timeline"] ?></a> </li>
                        <li class="nav-item <?php echo $id == "familytree" ? "rounded bg-primary" : "" ?>"> <a class="nav-link" href="<?php echo setParameters("familytree")?>"><?php echo $dict["navigation.familytree"] ?></a> </li>
                        <li class="nav-item <?php echo $id == "worldmap" ? "rounded bg-primary" : "" ?>"> <a class="nav-link" href="<?php echo setParameters("worldmap")?>"><?php echo $dict["navigation.worldmap"] ?></a> </li>
                        <li class="nav-item <?php echo $id == "aboutus" ? "rounded bg-primary" : "" ?>"> <a class="nav-link" href="<?php echo setParameters("aboutus")?>"><?php echo $dict["navigation.about_us"] ?></a> </li>
                    </ul> 
                    <a class="btn navbar-btn ml-md-2 btn-secondary text-body" href="<?php echo setParameters("contact")?>"><?php echo $dict["navigation.contact_us"] ?></a>
                </div>
            </div>
        </nav>
        
        <!-- Actual content of the page itself 
            This is defined in the corresponding php page -->
        <div id="content" class="py-5 flex-grow-1" style="background-color: hsl(0, 100%, 99%)">
        </div>
        
        <footer class="py-3 bg-light mt-auto">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center"> 
                        <h4 class="my-0"><?php insertLanguages(); ?></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center"> 
                        <img class="img-fluid d-block mx-auto mt-2 mb-2" src="/img/logo.bmp" width="50" height="50">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <p class="my-0"><?php echo "© 2014-".date('Y')." ".$dict["globals.prodeo_copyright"] ?></p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>