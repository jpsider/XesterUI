<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	$Log_Path=$_GET['Log_File'];  //value will be LogFile_Path
?>
<!-- Insert Head PHP -->
<!-- End Head PHP -->
    <div class="content-area"><!-- Start content-area -->
    <div>
    <h3>XesterUI Logfile:</h3>
				<div class="row">
		<?php
		$findstr = 'xml';
		$pos = strpos($Log_Path, $findstr);
		if ($pos === false) {
			$myfile = fopen("$Log_Path", "r") or die("Unable to open //file!");
			$pageText = fread($myfile,filesize("$Log_Path"));
			echo '<table class="table-striped table-bordered"><tr><td>'. nl2br($pageText) .'</td></tr></table>';
			fclose($myfile);
		}
		else {
			$XML = file_get_contents($Log_Path);
			$XML = str_replace('&', '&amp;', $XML);
			$XML = str_replace('<', '&lt;', $XML);
			echo '<pre>' . $XML . '</pre>';
		}
		?>
		</div>
		</div> <!-- End of Row -->
		
	</div><!-- End content-area -->
    <nav class="sidenav">
		<?php
			require_once 'components/Side_Bar.html';
		?>
	</nav>
</div><!-- End content-container (From Header) -->
</body>
</html>