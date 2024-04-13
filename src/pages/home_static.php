<?php
    require "src/tools/database.php";
    
    $data = getItems($TYPE_BLOG);

    if (isset($data->records)) {
        ?><div class="container blogs"><?php
            $blogs = $data->records;
            foreach($blogs as $idx => $blog) {
                // Use five different colors that will loop
                $color = ["purple", "yellow", "red", "green", "blue"][$idx % 5];
                
                // Some changes before they're inserted into the div
                $id = "blog_".$blog->id;
                $title = $blog->title;
                
                // If there is blog text, add either one or two breaklines
                // The amount of breaklines depends on if the text ends with a paragraph
                $text = $blog->text ? 
                            $blog->text.
                                (str_ends_with($blog->text, "</p>") ? 
                                "<br>" : 
                                "<br><br>") : 
                            "";
                
                // If the user isn't defined for some reason, get it as anonymous
                $user = $blog->name === "undefined" ? 
                            $dict["blogs.anonymous"] : 
                            $blog->name;
                
                // Timezone of the server, convert it to UTC
                $timezone = date_default_timezone_get();
                $date_server = new DateTime($blog->date, new DateTimeZone($timezone));
                $date_utc = $date_server->setTimezone(new DateTimeZone("UTC"));
                $date = $date_utc->format("j-n-Y H:i:s");
                
                ?><div class="row justify-content-center">
                    <div class="col-md-11 mb-3">
                        <h1 class="text-center pb-2 pt-2 mb-0" style="background-color: var(--dark-<?= $color; ?>)"><?= $title;?></h1>
                        <div class="text-center pb-2 pt-2 px-3 mb-0 h5" style="word-break:break-word; background-color: var(--light-<?= $color; ?>)" id="<?= $id; ?>"><?= $text;?></div>
                        <h6 class="pb-2 text-center font-weight-bold" style="background-color: var(--light-<?= $color; ?>)"><?= $dict["blogs.posted_by"];?><a href="<?= setParameters("settings")?>" class="text-decoration-none text-body"><?= $user; ?></a> @ <?= $date?> (UTC)</h6>
                    </div>
                </div><?php
            }
        ?></div><?php
    } else {
        ?><div class="container blogs text-center h1"><?php
            echo $dict["settings.database_err"];
        ?></div><?php
    } 
?>