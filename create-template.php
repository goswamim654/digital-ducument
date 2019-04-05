<?php
include 'dbconnect.php';
$regex = '/%%input,([a-zA-Z0-9]+),([0-9]+)%%/is';
if(isset($_POST['createTemplate'])) {
	$title = trim(mysqli_real_escape_string($con, $_POST['title']));
	$content = trim(mysqli_real_escape_string($con, $_POST['template_content']));
	//$content = preg_replace($regex, '<input type="text" name="$1" maxlength="$2" placeholder="$1">', $content);

	// Save record
	$sql_ins = "INSERT INTO test_digital_document_templates (title, content) VALUES ('$title', '$content')";
	$res_ins = $con->query($sql_ins);
	if($res_ins) {
		header("Location: view-templates.php");
	}
	else 
	{
		$message = 'Error-> '.mysqli_error($con);
		echo $message;
		die();
	}
}
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
			<form style="margin-top: 30px;" action="create-template.php" method="post">
				<div class="form-group">
					<label>Title</label>
					<input type="text" name="title" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Message</label>
					<textarea id="template_content" name="template_content"></textarea>
				</div>
				<div>
					<button type="button" class="btn btn-success" onclick="addInputBox()">Add input box</button>
					<input class="btn btn-primary pull-right" type="submit" name="createTemplate" value="Create Template">
				</div>
			</form>
			
		</div>
	</div>
	<div id="input_attr_details" class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Input Attributes</h4>
	      </div>
	      <div class="modal-body">
	        <form class="form-horizontal">
			  <div class="form-group">
			    <label for="placeholder" class="col-sm-3 control-label">Placeholder text</label>
			    <div class="col-sm-9">
			      <input type="text" class="form-control" id="placeholder">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="size" class="col-sm-3 control-label">Max Size</label>
			    <div class="col-sm-9">
			      <input type="text" class="form-control" id="size">
			    </div>
			  </div>
			</form>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-primary" onclick="insertInputBox()">Insert</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<div id="previewTemplate" class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Preview Template</h4>
	      </div>
	      <div class="modal-body" id="previewTemplateContent">
	        
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<script
    src="https://code.jquery.com/jquery-2.2.4.min.js"
    integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
    crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="plugins/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
    	// init tinymce
		tinymce.init({
		    selector: '#template_content',
		    height: 300,
		    plugins: 'advlist autolink link lists charmap print preview',
		    toolbar1: 'newdocument, bold, italic, underline, strikethrough, alignleft, aligncenter, alignright, alignjustify, styleselect, formatselect, fontselect, fontsizeselect',
		    toolbar2: 'cut, copy, paste, bullist, numlist, outdent, indent, blockquote, undo, redo, removeformat, subscript, superscript'
		});

		function addInputBox() {
			$('#input_attr_details').modal('show');
		}

		function insertInputBox() {
			// check letters
			var regexAlpha = /^[a-zA-Z]+$/;
			var regexNumber = /^[0-9]+$/;
			var placeholder = $('#placeholder').val();
			var size = $('#size').val();
			if(placeholder == '' || placeholder == null) 
			{
				alert("Placeholder is mandatory");
   				return false;
			} 
			else if(size == '' || size == null)
			{
				alert("Size is mandatory");
   				return false;
			}
			else if(regexAlpha.test(placeholder) == false) {
				alert("Placeholder must be in alphabets only without any space");
				return false;
			}
			else if(regexNumber.test(size) == false){
				alert("Size must be in number only");
				return false;
			}
			else {
				//var editor_content = tinyMCE.activeEditor.getContent();
				var input_attr = `%%input,${placeholder},${size}%%`;
				//tinymce.get('template_content').setContent(editor_content);
				tinymce.activeEditor.execCommand('mceInsertContent', false, input_attr);
				$('#input_attr_details').modal('hide');
				$('#placeholder').val('');
				$('#size').val('');
			}
			
		}

		function previewTemplate() {
			var editor_content = tinyMCE.activeEditor.getContent();
			var regex = /%%input,([a-zA-Z0-9]+),([0-9]+)%%/g;
			editor_content = editor_content.replace(regex, '<input name="$1" maxlength="$2" placeholder="$1">');
			console.log(editor_content);
			$('#previewTemplateContent').html(editor_content);
			$('#previewTemplate').modal('show');
		}
    </script>
</body>
</html>