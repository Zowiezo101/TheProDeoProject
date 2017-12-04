<?php 
	$id = "home";
	require "layout/header.php"; 
	require "tools/blogHelper.php"; 
?>

<div id="home">
	<?php ShowBlogs(); ?>
</div>

<?php require "layout/footer.php" ?>

<script>
window.onload = CheckBlogs;
</script>