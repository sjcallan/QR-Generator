<script type="text/javascript">

	$(function(){
		
		$(".delete").click(function(){
			if(confirm("Are you sure you want to delete this code?"))
			{
				return true;
			}
			else
			{
				return false;
			}
		});
		
	});

</script>
<div style="float:left;margin-right:10px"><?= $qr_code ?></div>

<div style="margin-left:110px">
	<h3>Details</h3>
	<p><strong>URL:</strong> <a href="<?= $redirect_details["redirect_url"] ?>"><?= $redirect_details["redirect_url"] ?></a></p>
	<p><strong>QR URL:</strong> <a href="<?= site_url("r/" . $redirect_details["redirect_key"]) ?>"><?= site_url("r/" . $redirect_details["redirect_key"]) ?></a></p>
	<p><strong>Date Created:</strong> <?= $redirect_details["redirect_date_created"] ?></p>
	<p><strong>Total Scans:</strong> <?= $redirect_details["redirect_click_count"] ?></p>
	<p><a href="<?= site_url("create/codes/generate/" . $redirect_details["redirect_key"] . "/100/1200") ?>" class="btn primary">Download Code</a></p>
</div>

<hr />

<h3>Scan Stats</h3>
<?= $graph_source ?>

<hr />

<h3>Actions</h3>
<p><a href="<?= site_url("create/codes/delete/" . $redirect_details["redirect_key"]) ?>" class="btn danger delete">Delete this QR Code</a></p>