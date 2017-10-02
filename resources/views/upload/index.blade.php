<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script src="http://demo.itsolutionstuff.com/plugin/jquery.js"></script>
    <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/bootstrap-3.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.1.1/dropzone.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.1.1/dropzone.js"></script>
</head>
<body>
	
	<div class="container">
	    <div class="row">
	        <div class="col-md-12">
	            <h1>Upload Multiple Images using dropzone.js and Laravel</h1>
	   

	            <form action="{{ route('dropzone.store') }}" method="POST" enctype="multipart/form-data" class="dropzone" id="image-upload">
	            	{{ csrf_field() }}

	            	<p>text</p>
	            	<img src="https://maxcdn.icons8.com/Share/icon/p1em/Photo_Video//camera1600.png" width="200" alt="">

	            </form>
	        </div>
	    </div>
	</div>

	<script type="text/javascript">
        Dropzone.options.imageUpload = {
            maxFilesize         :       1,
            acceptedFiles: ".jpeg,.jpg,.png,.gif"
        };
	</script>
</body>
</html>