    <nav class="navbar navbar-expand-md navbar-light bg-light">
        <a class="navbar-brand text-primary" href="home">
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
                    <li class="nav-item shadow-none <?php echo $id == "home" ? "rounded bg-primary" : "" ?>" style=""> 
                        <a class="nav-link active" href="home">Home</a> 
                    </li>
                    <li class="nav-item dropdown <?php echo $dropdown == "database" ? "rounded bg-primary" : "" ?>"> 
                        <a class="nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Database </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item <?php echo $id == "books" ? "bg-primary" : "" ?>" href="books">Books</a>
                            <a class="dropdown-item <?php echo $id == "events" ? "bg-primary" : "" ?>" href="events">Events</a>
                            <a class="dropdown-item <?php echo $id == "persons" ? "bg-primary" : "" ?>" href="persons">Persons</a>
                            <a class="dropdown-item <?php echo $id == "locations" ? "bg-primary" : "" ?>" href="locations">Locations</a>
                            <a class="dropdown-item <?php echo $id == "specials" ? "bg-primary" : "" ?>" href="specials">Specials</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item <?php echo $id == "search" ? "bg-primary" : "" ?>" href="search">Search</a>
                        </div>
                    </li>
                    <li class="nav-item <?php echo $id == "timeline" ? "rounded bg-primary" : "" ?>"> <a class="nav-link" href="timeline">Timeline</a> </li>
                    <li class="nav-item <?php echo $id == "familytree" ? "rounded bg-primary" : "" ?>"> <a class="nav-link" href="familytree">Familytree</a> </li>
                    <li class="nav-item <?php echo $id == "worldmap" ? "rounded bg-primary" : "" ?>"> <a class="nav-link" href="worldmap">Worldmap</a> </li>
                    <li class="nav-item <?php echo $id == "aboutus" ? "rounded bg-primary" : "" ?>"> <a class="nav-link" href="aboutus">About us</a> </li>
                </ul> 
                <a class="btn navbar-btn ml-md-2 btn-secondary text-body" href="contact">Contact us</a>
            </div>
        </div>
    </nav>

