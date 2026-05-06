<?php /*if($this->session->userdata('logged_in') !== TRUE){
      redirect('hem');
    } */
?>
<div class="container">
<div class="row">
    <div class="col-md-12">
			<div class="page-header">
				<h1>Mina djur dt</h1>
			</div>
            <div>
            	<p>Djur...</p>
				
				<p>Du är inloggad som: <?php echo $_SESSION["username"]; ?></p>
			</div>
			<table id="mina_djur" class="table table-bordered table-striped table-hover">
				<thead>
					<tr><td>SE-nummer</td><td>Individnummer</td><td>Kön</td><td>Födelsedatum</td><td>Ras</td><td>Namn</td><td>Mor</td><td>Far</td></tr>
				</thead>
			<tbody>
			</tbody>
			</table>
			<script type="text/javascript">
				$(document).ready(function() {
					$('#mina_djur').DataTable(
					{
						"ajax": {
							url : "<?php echo site_url("pages/djurlista") ?>",
							type : 'GET'
						},
					}
					
					
					);
				});
			</script>
		</div>
	</div>
