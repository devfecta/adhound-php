<script type="text/javascript">
	var UserType = <?php echo (int)$UserInfo['IA_Users_Type']; ?>;
	if(UserType != 2) 
	{
		LoadData(<?php echo $UserInfo['UserParentID']?>);
	}
</script>

	</div>
</div>
<div style="clear:both"></div>
<div id="Footer">
	<div id="Footer_Main">
		<?php 
			echo COPYRIGHT;
		?>
			 ~ <a href="http://www.itsadvertising.com/copyright.php" class="FooterLink" title="It's Advertising, LLC - Copyright Policy">Copyright Policy</a>
			 ~ <a href="http://www.itsadvertising.com/privacy.php" class="FooterLink" title="It's Advertising, LLC - Privacy Policy">Privacy Policy</a>
	</div>
</div>
<script type="text/javascript">
	if(document.getElementById('PageTitle')) 
	{
		document.title = document.getElementById('PageTitle').innerHTML;
	}
	else 
	{
		document.title = document.getElementsByTagName('h1')[0].innerHTML;
	}
</script>
<!--
</td>
</tr>
<tr><td colspan="2" style="background: url(images/footer_background.png) repeat-x; height:20px">
&nbsp;
</td></tr>
<tr>
<td colspan="2" class="footer">
	<?php 
		echo COPYRIGHT;
	?>
	 ~ <a href="http://www.itsadvertising.com/copyright.php" class="FooterLink" title="It's Advertising, LLC - Copyright Policy">Copyright Policy</a>
	 ~ <a href="http://www.itsadvertising.com/privacy.php" class="FooterLink" title="It's Advertising, LLC - Privacy Policy">Privacy Policy</a>
</td></tr>
</table>
-->
</body>
</html>

<?php ob_flush(); ?>