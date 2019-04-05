<?php
include 'dbconnect.php';
$regex = '/%%input,([a-zA-Z0-9]+),([0-9]+)%%/is';

if(isset($_GET['id']))
{
	$id = (int)$_GET['id'];
	$sql = "SELECT * from test_digital_document_templates where id='$id'";
	$res = $con->query($sql);
	$row = $res->fetch_object();
	$content =  $row->content;
	$content = preg_replace($regex, '<input type="text" name="$1" maxlength="$2" placeholder="$1" required>', $content);
}
else
{
	header("Location: view-templates.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>View Template</title>
	<!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style type="text/css">
    	.wrapper {
		  position: relative;
		  width: 400px;
		  height: 200px;
		  -moz-user-select: none;
		  -webkit-user-select: none;
		  -ms-user-select: none;
		  user-select: none;
		}

		.signature-pad {
		  position: absolute;
		  left: 0;
		  top: 0;
		  width:400px;
		  height:200px;
		  background-color: white;
		  border: 2px solid #000;
		}
    </style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<form id="formSubmit" action="generate-pdf.php?id=<?php echo $id;?>" method="post">
					<input type="hidden" name="image" id="image">
					<p><?php echo $content;?></p>
					<div class="form-group" style="margin:20px 0;">
						<div class="wrapper">
					  		<canvas id="signature-pad" class="signature-pad" width=400 height=200></canvas>
						</div>
						<button style="margin:20px 0;" type="button" class="btn btn-sm btn-warning" id="clear">Clear</button>
					</div>
					<button type="button" class="btn btn-sm btn-success" onclick="submitForm()">Submit</button>
				</form>
			</div>
		</div>
	</div>
	<script
    src="https://code.jquery.com/jquery-2.2.4.min.js"
    integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
    crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
    <script type="text/javascript">
		var canvas = document.getElementById('signature-pad');

		// Adjust canvas coordinate space taking into account pixel ratio,
		// to make it look crisp on mobile devices.
		// This also causes canvas to be cleared.
		function resizeCanvas() {
		    // When zoomed out to less than 100%, for some very strange reason,
		    // some browsers report devicePixelRatio as less than 1
		    // and only part of the canvas is cleared then.
		    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
		    canvas.width = canvas.offsetWidth * ratio;
		    canvas.height = canvas.offsetHeight * ratio;
		    canvas.getContext("2d").scale(ratio, ratio);
		}

		//window.onresize = resizeCanvas;
		resizeCanvas();

		var signaturePad = new SignaturePad(canvas, {
		  backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
		});


		document.getElementById('clear').addEventListener('click', function () {
		  signaturePad.clear();
		});

		
	    function submitForm() {
	    	var count = 0;
	    	var dataURL = canvas.toDataURL();
	        $('#image').val(dataURL);
	        $( "input" ).each(function() {
			  if($( this ).val() == '') {
			  	count++;
			  }
			});	
			if(count > 0) {
				alert('All fields are mandatory.');
				return false;
			}
			else if (signaturePad.isEmpty()) {
		    	return alert("Please provide a signature");
		  	}
			else
			{
				$('#formSubmit').submit();	
			}
	        
	    }
	    
    </script>
</body>