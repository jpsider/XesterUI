<!DOCTYPE html>
<html lang="en">
<?php
	$Log_Path=$_GET['Log_File'];  //value will be LogFile_Path
?>
<?php
	require_once 'components/header.php';
?>
<script> 
	$(document).ready(function() {
		$('#example').dataTable();
	});
</script>
<body>
    <div class="container" style="margin-left:10px">
    	<div class="row">
			<?php
				require_once 'components/Side_Bar.html';
			?>
			<div class="col-sm-9 col-md-10 col-lg-10 main">
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
			//header('Content-type: text/xml');
			//echo "<a href='singleImage.php?Log_Path=$Log_Path' target='_blank'>View Image</a>";
			//echo "<pre class='prettyprint linenums'>";
			//	echo "<code class='language-xml'>";
			//	htmlspecialchars(file_get_contents("$Log_Path"), ENT_QUOTES); 
			//echo "</code></pre>";
			//$xml = simplexml_load_string($Log_Path);
			//echo $xml->asXML();
			$XML = file_get_contents($Log_Path);
			$XML = str_replace('&', '&amp;', $XML);
			$XML = str_replace('<', '&lt;', $XML);
			echo '<pre>' . $XML . '</pre>';
		}
		?>
			</div>
		</div>
	</div> <!-- /container -->
</body>
<?php
	require_once 'components/footer.php';
?>
</html>