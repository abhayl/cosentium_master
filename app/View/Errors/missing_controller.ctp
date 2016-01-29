<?php 
 if($this->layout == 'home')
	echo "<div style=\"min-height:475px;\">";
?>
<div id="contactinner">
<h1>Page Not Found</h1>
We are sorry, the page you are looking for could not be found.  Either we moved the content or the address was mistyped.  Please try your request again.  If you arrived at this page by clicking on a link on our site, or a link in an email we sent you, please <a href="mailto:<?php echo Configure::read('SUPPORT_EMAIL'); ?>?subject=<?php echo Configure::read('SUPPORT_SUBJECT'); ?>">let us know</a> so that we can fix it.<br><br><br><br>         
</div>
<?php 
	if($this->layout == 'home')
		echo "</div>";
?>