<?php 
    // Make it easier to copy/paste code or make a new file
    // Less chance for errors
    $id = basename(filter_input(INPUT_SERVER, 'PHP_SELF'), '.php');
    require 'src/template.php';
?>

<script>
    // Function to load the content in the content div
    function onLoadAboutus() {
        $("#content").append(
            $("<div>").addClass("container-fluid").append(
                $("<div>").addClass("row")
                    .append(`
                        <div class="col-md-12">
                            <div class="row mb-5 pb-5 text-center justify-content-center">
                                <div class="col-md-10">
                                    <h1 class="mb-3">` + dict["navigation.about_us"] + `</h1>
                                    <p class="lead">` + dict["about_us.overview"] + `</p>
                                    <h2>` + dict["about_prodeo.title"] + `</h2>
                                    <p class="lead">` + dict["about_prodeo.overview"] + `</p>
                                    <h2>` + dict["other_projects.title"] + `</h2>
                                    <p class="lead">` + dict["other_projects.overview"] + `</p>
                                </div>
                            </div>
                        </div>
                    `)
            )
        );
    }
</script>

        