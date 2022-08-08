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

