    <nav class="navbar navbar-expand-md navbar-light bg-light">
        <a class="navbar-brand text-primary" href="#">
            <img src="img/logo.bmp" class="d-inline-block align-top rounded" alt="" width="75" height="75" style="">
            <b class="text-secondary" style=""> ProDeo Database</b>
        </a>
        <span class="navbar-text text-secondary">For God, to You</span>
        <div class="container"> 
            <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbar4" style="">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar4">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item shadow-none rounded <?php echo $id == "home" ? "bg-primary" : "" ?>" style=""> 
                        <a class="nav-link active" href="index.html">Home</a> 
                    </li>
                    <li class="nav-item dropdown rounded  <?php echo $id == "database" ? "bg-primary" : "" ?>"> 
                        <a class="nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Database </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="books.html">Books</a>
                            <a class="dropdown-item" href="events.html">Events</a>
                            <a class="dropdown-item" href="persons.html">Persons</a>
                            <a class="dropdown-item" href="locations.html">Locations</a>
                            <a class="dropdown-item" href="specials.html">Specials</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="search.html">Search</a>
                        </div>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="timeline.html">Timeline</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="familytree.html">Familytree</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="worldmap.html">Worldmap</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="aboutus.html">About us</a> </li>
                </ul> 
                <a class="btn navbar-btn ml-md-2 btn-light">Contact us</a>
            </div>
        </div>
    </nav>

