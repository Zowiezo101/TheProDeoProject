<?php 
    // Make it easier to copy/paste code or make a new file
    // Less change of errors
    $id = basename(filter_input(INPUT_SERVER, 'PHP_SELF'), '.php');
    require 'page/template.php';
?>

<script>
    // Function to load the content in the content div
    function onLoadHome() {
        
    }
</script>

<!--<div class="py-5" style="	background-image: url(img/background_home.svg);	background-position: top left;	background-size: 100% 32px;	background-repeat: repeat repeat-y;">
        <div class="container">
          <div class="row">
            <div class="col-md-11 mb-3">
              <h1 class="text-center pb-2 pt-2 mb-0" style="background-color: var(--dark-purple)">O my friend</h1>
              <h5 class="text-center pb-2 pt-2 mb-0" style="background-color: var(--light-purple)">A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.&nbsp; <br> <br>When, while the lovely valley teems with vapour around me, and the meridian sun strikes the upper surface of the impenetrable foliage of my trees, and but a few stray gleams steal into the inner sanctuary, I throw myself down among the tall grass by the trickling stream; and, as I lie close to the earth, a thousand unknown plants are noticed by me.</h5>
              <h6 class="pb-2 text-center font-weight-bold" style="background-color: var(--light-purple)">Posted by <a href="settings.html" class="text-decoration-none text-body">Zowiezo</a> @ 2021-01-19 at 19:00 PM </h6>
            </div>
          </div>
          <div class="row">
            <div class="col-md-11 mb-3">
              <h1 class="text-center pb-2 pt-2 mb-0 bg-warning" style="background-color: var(--dark-yellow)">O my friend</h1>
              <h5 class="pb-2 pt-2 mb-0 text-center" style="background-color: var(--light-yellow)">A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.&nbsp; <br> <br>When, while the lovely valley teems with vapour around me, and the meridian sun strikes the upper surface of the impenetrable foliage of my trees, and but a few stray gleams steal into the inner sanctuary, I throw myself down among the tall grass by the trickling stream; and, as I lie close to the earth, a thousand unknown plants are noticed by me.</h5>
              <h6 class="pb-2 text-center font-weight-bold" style="background-color: var(--light-yellow)">Posted by <a href="settings.html" class="text-decoration-none text-body">Zowiezo</a> @ 2021-01-19 at 19:00 PM </h6>
            </div>
          </div>
          <div class="row">
            <div class="col-md-11 mb-3">
              <h1 class="text-center pb-2 pt-2 mb-0" style="background-color: var(--dark-red)">O my friend</h1>
              <h5 class="text-center pb-2 pt-2 mb-0" style="background-color: var(--light-red)">A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.&nbsp; <br> <br>When, while the lovely valley teems with vapour around me, and the meridian sun strikes the upper surface of the impenetrable foliage of my trees, and but a few stray gleams steal into the inner sanctuary, I throw myself down among the tall grass by the trickling stream; and, as I lie close to the earth, a thousand unknown plants are noticed by me.</h5>
              <h6 class="pb-2 text-center font-weight-bold" style="background-color: var(--light-red)">Posted by <a href="settings.html" class="text-decoration-none text-body">Zowiezo</a> @ 2021-01-19 at 19:00 PM </h6>
            </div>
          </div>
          <div class="row">
            <div class="col-md-11 mb-3">
              <h1 class="text-center pb-2 pt-2 mb-0" style="background-color: var(--dark-green)">O my friend</h1>
              <h5 class="text-center pb-2 pt-2 mb-0" style="background-color: var(--light-green)">A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.&nbsp; <br> <br>When, while the lovely valley teems with vapour around me, and the meridian sun strikes the upper surface of the impenetrable foliage of my trees, and but a few stray gleams steal into the inner sanctuary, I throw myself down among the tall grass by the trickling stream; and, as I lie close to the earth, a thousand unknown plants are noticed by me.</h5>
              <h6 class="pb-2 text-center font-weight-bold" style="background-color: var(--light-green)">Posted by <a href="settings.html" class="text-decoration-none text-body">Zowiezo</a> @ 2021-01-19 at 19:00 PM </h6>
            </div>
          </div>
          <div class="row">
            <div class="col-md-11 mb-3">
              <h1 class="text-center pb-2 pt-2 mb-0" style="background-color: var(--dark-blue)">O my friend</h1>
              <h5 class="text-center pb-2 pt-2 mb-0" style="background-color: var(--light-blue)">A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.&nbsp; <br> <br>When, while the lovely valley teems with vapour around me, and the meridian sun strikes the upper surface of the impenetrable foliage of my trees, and but a few stray gleams steal into the inner sanctuary, I throw myself down among the tall grass by the trickling stream; and, as I lie close to the earth, a thousand unknown plants are noticed by me.</h5>
              <h6 class="pb-2 text-center font-weight-bold" style="background-color: var(--light-blue)">Posted by <a href="settings.html" class="text-decoration-none text-body">Zowiezo</a> @ 2021-01-19 at 19:00 PM </h6>
            </div>
          </div>
        </div>
      </div>
    -->