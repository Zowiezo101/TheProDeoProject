<?php 
    // Make it easier to copy/paste code or make a new file
    // Less change of errors
    $id = basename(filter_input(INPUT_SERVER, 'PHP_SELF'), '.php');
    require 'page/template.php';
?>

<script>
    function onLoadSettings() {
<?php if (isset($_SESSION["login"])) { ?>
        $("#content").append(
            $("<div>").addClass("container-fluid").append(
                $("<div>").addClass("row")
                    // The column with the tabs
                    .append(getTabsMenu())
                    // The column with the selected tabs
                    .append(getTabsContent())
            )
        );
<?php } else { ?>
        $("#content").append(
            $("<div>").addClass("container-fluid").append(
                $("<div>").addClass("row")
                    // The column with the tabs
                    .append(getLoginMenu())
                    // The column with the selected tabs
                    .append(getLoginContent())
            )
        );
<?php } ?>
    }
</script>