<?php
	require APPROOT . '/views/includes/head.php';
?>

<div id="section-landing">
	<?php
	require APPROOT . '/views/includes/navigation.php';
	?>
	
</div>

<div class="container-search">
	<div class="wrapper-search">
		<form action="<?php echo URLROOT; ?>/users/results" method="POST">
	      <input type="text" placeholder="Search.." name="search">
	      <button id="submit" type="submit" value="submit">Submit</button>
	    </form>



	</div>
</div>
