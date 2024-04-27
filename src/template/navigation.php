        <nav class="navbar navbar-expand-md navbar-light bg-light shadow">
            <a class="navbar-brand text-primary" href="<?= setParameters("home")?>">
                <img src="/img/logo.bmp" class="d-inline-block align-top rounded" alt="" width="75" height="75" style="">
                <b class="text-secondary" style=""> <?= $dict["globals.prodeo_database"] ?> </b>
            </a>
            <span class="navbar-text text-secondary"><?= $dict["globals.prodeo_slogan"] ?></span>
            <div class="container"> 
                <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbar4" style="">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar4">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item shadow-none <?= $page_id == "home" ? "rounded bg-primary" : "" ?>" style=""> 
                            <a class="nav-link active" href="<?= setParameters("home")?>"><?= $dict["navigation.home"] ?></a> 
                        </li>
                        <li class="nav-item dropdown <?= $dropdown == "database" ? "rounded bg-primary" : "" ?>"> 
                            <a class="nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?= $dict["navigation.database"] ?> </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item <?= $page_id == "books" ? "bg-primary" : "" ?>" href="<?= setParameters("books")?>"><?= $dict["navigation.books"] ?></a>
                                <a class="dropdown-item <?= $page_id == "events" ? "bg-primary" : "" ?>" href="<?= setParameters("events")?>"><?= $dict["navigation.events"] ?></a>
                                <a class="dropdown-item <?= $page_id == "peoples" ? "bg-primary" : "" ?>" href="<?= setParameters("peoples")?>"><?= $dict["navigation.peoples"] ?></a>
                                <a class="dropdown-item <?= $page_id == "locations" ? "bg-primary" : "" ?>" href="<?= setParameters("locations")?>"><?= $dict["navigation.locations"] ?></a>
                                <a class="dropdown-item <?= $page_id == "specials" ? "bg-primary" : "" ?>" href="<?= setParameters("specials")?>"><?= $dict["navigation.specials"] ?></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item <?= $page_id == "search" ? "bg-primary" : "" ?>" href="<?= setParameters("search")?>"><?= $dict["navigation.search"] ?></a>
                            </div>
                        </li>
                        <li class="nav-item <?= $page_id == "timeline" ? "rounded bg-primary" : "" ?>"> <a class="nav-link" href="<?= setParameters("timeline")?>"><?= $dict["navigation.timeline"] ?></a> </li>
                        <li class="nav-item <?= $page_id == "familytree" ? "rounded bg-primary" : "" ?>"> <a class="nav-link" href="<?= setParameters("familytree")?>"><?= $dict["navigation.familytree"] ?></a> </li>
                        <li class="nav-item <?= $page_id == "worldmap" ? "rounded bg-primary" : "" ?>"> <a class="nav-link" href="<?= setParameters("worldmap")?>"><?= $dict["navigation.worldmap"] ?></a> </li>
                        <li class="nav-item <?= $page_id == "aboutus" ? "rounded bg-primary" : "" ?>"> <a class="nav-link" href="<?= setParameters("aboutus")?>"><?= $dict["navigation.about_us"] ?></a> </li>
                    </ul> 
                    <a class="btn navbar-btn ml-md-2 btn-secondary text-body" href="<?= setParameters("contact")?>"><?= $dict["navigation.contact_us"] ?></a>
                </div>
            </div>
        </nav>
