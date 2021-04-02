<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="css/theme_redW.css">
  <link rel="stylesheet" href="css/theme_purple.css">
</head>

<body>
  <nav class="navbar navbar-expand-md navbar-light bg-light">
    <a class="navbar-brand text-primary" href="#"><img src="img/logo.bmp" class="d-inline-block align-top rounded" alt="" width="75" height="75" style="">
      <b class="text-secondary" style=""> ProDeo Database</b>
    </a><span class="navbar-text text-secondary">For God, to You</span>
    <div class="container"> <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbar4" style="">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbar4">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item shadow-none rounded" style=""> <a class="nav-link active" href="index.html">Home</a> </li>
          <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Database </a>
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
          <li class="nav-item"> <a class="nav-link" href="#">Worldmap</a> </li>
          <li class="nav-item"> <a class="nav-link" href="aboutus/html">About us</a> </li>
        </ul> <a class="btn navbar-btn ml-md-2 btn-secondary text-body">Contact us</a>
      </div>
    </div>
  </nav>
  <div class="py-5">
    <div class="container-fluid">
      <div class="row">
        <div class="col-3">
          <ul class="nav nav-pills flex-column">
            <li class="nav-item"> <a href="" class="active nav-link" data-toggle="pill" data-target="#tabadd"> ADD BLOG <i class="fa fa-list text-muted fa-lg"></i></a> </li>
            <li class="nav-item"> <a class="nav-link" href="" data-toggle="pill" data-target="#tabedit"> EDIT BLOG <i class="fa fa-pie-chart text-muted fa-lg"></i></a> </li>
            <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabdelete"> DELETE BLOG <i class="fa fa-cog text-muted fa-lg"></i></a> </li>
            <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tablogout"> LOG OUT <i class="fa fa-sign-out text-muted fa-lg"></i></a> </li>
          </ul>
        </div>
        <div class="col-9">
          <div class="tab-content">
            <div class="tab-pane fade show active" id="tabadd" role="tabpanel">
              <form class="">
                <p class="lead">In my soul and absorb its power, like the form of a beloved mistress, then I often think with longing. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.</p>
                <div class="form-group">
                  <label for="exampleFormControlSelect1">Example select</label>
                  <select class="form-control w-75" id="exampleFormControlSelect1">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                  </select>
                </div>
                <div class="form-group"> <label>Email address</label> <input type="text" class="form-control w-75" placeholder="Enter email"> </div>
                <div class="form-group"> <label>Password</label> <textarea class="form-control w-75" placeholder="Password" rows="5"></textarea> </div>
                <div class="form-group"> <label>Email address</label> <input type="text" class="form-control w-75" placeholder="Enter email"> </div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>
            <div class="tab-pane fade" id="tabedit" role="tabpanel">
              <form class="">
                <p class="lead">In my soul and absorb its power, like the form of a beloved mistress, then I often think with longing. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.</p>
                <div class="form-group">
                  <label for="exampleFormControlSelect1">Example select</label>
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                  </select>
                </div>
                <div class="form-group"> <label>Email address</label> <input type="text" class="form-control" placeholder="Enter email"> </div>
                <div class="form-group"> <label>Password</label> <textarea class="form-control" placeholder="Password" rows="5"></textarea> </div>
                <div class="form-group"> <label>Email address</label> <input type="text" class="form-control" placeholder="Enter email"> </div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>
            <div class="tab-pane fade" id="tabdelete" role="tabpanel">
              <form class="">
                <p class="lead">In my soul and absorb its power, like the form of a beloved mistress, then I often think with longing. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.</p>
                <div class="form-group">
                  <label for="exampleFormControlSelect1">Example select</label>
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>
                </div>
                <div class="form-group"> <label>Email address</label> <input type="text" class="form-control" placeholder="Enter email"> </div>
                <div class="form-group"> <label>Password</label> <textarea class="form-control" placeholder="Password" rows="5"></textarea> </div>
                <div class="form-group"> <label>Email address</label> <input type="text" class="form-control" placeholder="Enter email"> </div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>
            <div class="tab-pane fade" id="tablogout" role="tabpanel">
              <p class="">Which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite. When I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="py-3 bg-light">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center"> <img class="img-fluid d-block mx-auto" src="img/logo.bmp" width="50" height="50">
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <p class="mb-0">© 2014-2021 ProDeo Productions. All rights reserved</p>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <pingendo onclick="window.open('https://pingendo.com/', '_blank')" style="cursor:pointer;position: fixed;bottom: 20px;right:20px;padding:4px;background-color: #00b0eb;border-radius: 8px; width:220px;display:flex;flex-direction:row;align-items:center;justify-content:center;font-size:14px;color:white">Made with Pingendo Free&nbsp;&nbsp;<img src="https://pingendo.com/site-assets/Pingendo_logo_big.png" class="d-block" alt="Pingendo logo" height="16"></pingendo>
</body>

</html>