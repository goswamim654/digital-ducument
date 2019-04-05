<?php
include 'dbconnect.php';
$sql = "SELECT * from test_digital_document_templates";
$res = $con->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Demo Template</title>
	<!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4>View Templates</h4>
			<div class="table-responsive">          
			  <table class="table">
			    <thead>
			      <tr>
			        <th>#</th>
			        <th>Title</th>
			        <th>Action</th>
			      </tr>
			    </thead>
			    <tbody>
			    	<?php  while( $row = $res->fetch_object() )
                	{ ?>
				      <tr>
				        <td><?php echo $row->id;?></td>
				        <td><?php echo $row->title;?></td>
				        <td><a href="view-template.php?id=<?php echo $row->id;?>">View</a></td>
				      </tr>
				    <?php } ?>
			    </tbody>
			  </table>
			</div>
		</div>
	</div>
	<div id="previewTemplate" class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Preview Template</h4>
	      </div>
	      <form action="generate-pdf.php" method="post">
	      		<input type="hidden" name="id" id="id">
				<div class="modal-body" id="previewTemplateContent"></div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-sm btn-success">Submit</button>
				</div>
			</form>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<script
    src="https://code.jquery.com/jquery-2.2.4.min.js"
    integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
    crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script type="text/javascript">
    	function viewTemplate(id) {
    		$.ajax({

			    url : 'getTemplateContent.php',
			    type : 'post',
			    data : {
			        'id' : id
			    },
			    dataType:'json',
			    success : function(data) { 
			    	var regex = /%%input,([a-zA-Z0-9]+),([0-9]+)%%/g;
					content = data.replace(regex, '<input type="text" name="$1" maxlength="$2" placeholder="$1" required>');             
			        $('#previewTemplateContent').html(content);
			        $('#previewTemplate').modal('show');
			        $('#id').val(id);
			    },
			    error : function(request,error)
			    {
			        alert("Request: "+JSON.stringify(request));
			    }
			});
    	}
    </script>
</body>
</html>