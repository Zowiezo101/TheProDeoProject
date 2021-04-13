<?php 
    // Make it easier to copy/paste code or make a new file
    // Less change of errors
    $id = basename(filter_input(INPUT_SERVER, 'PHP_SELF'), '.php');
    require 'page/template.php';
?>

<script>
    function onLoadSettings() {
        $("#content").append(
            $("<div>").addClass("container-fluid").append(
                $("<div>").addClass("row")
                    // The column with the tabs
                    .append(getTabsMenu())
                    // The column with the selected tabs
                    .append(getTabsContent())
            )
        );
    }
</script>