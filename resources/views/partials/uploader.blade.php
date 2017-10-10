@php $dropzoneId = isset($dz_id) ? $dz_id : str_random(8); @endphp
<div id="{{$dropzoneId}}" class="dropzone">
    <div class="dz-default dz-message">
        <h3>{{ $title or  'Drop files here or click to upload.'}}</h3>
        <p class="text-muted">{{ $desc or 'Any related files you can upload' }} <br>
            <small>One file can be max {{ config('attachment.max_size', 0) / 1000 }} MB</small></p>
    </div>
</div>
<!-- Dropzone {{ $dropzoneId }} -->

@section('scripts')
<script>
    // Turn off auto discovery
    Dropzone.autoDiscover = false;

    $(function () {
        // Attach dropzone on element
        $("#{{ $dropzoneId }}").dropzone({
            url: "{{ route('attachments.store') }}",
            maxFiles: 2,
            addRemoveLinks: true,
            maxFilesize: {{ isset($maxFileSize) ? $maxFileSize : config('attachment.max_size', 1000) / 1000 }},
            acceptedFiles: "{!! isset($acceptedFiles) ? $acceptedFiles : config('attachment.allowed') !!}",
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
            params: {!! isset($params) ? json_encode($params) : '{}'  !!},
            init: function () {
                // uploaded files
                var uploadedFiles = [];

                @if(isset($uploadedFiles) && count($uploadedFiles))

                    // show already uploaded files
                    uploadedFiles = {!! json_encode($uploadedFiles) !!};
                    var self = this;

                    uploadedFiles.forEach(function (file) {
                        console.log(file);
                        // Create a mock uploaded file:
                        var uploadedFile = {
                            id: file.id,
                            name: file.filename,
                            size: file.size,
                            type: file.mime,
                            dataURL: file.url,
                        };

                        // Call the default addedfile event
                        self.emit("addedfile", uploadedFile);

                        self.files.push(uploadedFile);

                        // Image? lets make thumbnail
                        if( file.mime.indexOf('image') !== -1) {

                            console.log('self.options', self.options);

                            self.createThumbnailFromUrl(
                                uploadedFile,
                                self.options.thumbnailWidth,
                                self.options.thumbnailHeight,
                                self.options.thumbnailMethod,
                                true, function(thumbnail) {
                                    self.emit('thumbnail', uploadedFile, thumbnail);
                            }, 'anonymous');

                        } else {
                            // we can get the icon for file type
                            self.emit("thumbnail", uploadedFile, getIconFromFilename(uploadedFile));
                        }

                        // fire complete event to get rid of progress bar etc
                        self.emit("complete", uploadedFile);
                    });

                @endif

                // Add click event for 'Main' image
                this.on("thumbnail", function(file) {
                    // check if file is selected
                      // if yes add 'selected' class

                    console.log(file); // will send to console all available props
                    file.previewElement.addEventListener("click", function() {
                        var thumbs = document.getElementsByClassName('dz-image-preview');

                        for(var i =0; i < thumbs.length; i++) {
                            thumbs[i].classList.remove('selected');
                        }

                        this.classList.add('selected');

                       var found = uploadedFiles.find(function (item) {
                            return (item.filename === file.name);
                        })

                       if( found ) {
                            console.log(found.id);
                            // ajax request - update main image selection
                        }
                    });
                });

                //Handle added file
                this.on('success', function(file) {
                    // var thumb = getIconFromFilename(file);
                    // $(file.previewElement).find(".dz-image img").attr("src", thumb);
                    console.log('handling ADDED FILE');
                    console.log(JSON.parse(file.xhr.response));

                    var response = JSON.parse(file.xhr.response);

                    var uploadedFile = {
                        id: response.id,
                        filename: response.filename,
                        size: response.size,
                        type: response.mime,
                        dataURL: response.url,
                    };
                    uploadedFiles.push(uploadedFile);
                    console.log(uploadedFiles);
                })

                // handle remove file to delete on server
                this.on("removedfile", function (file) {
                    // try to find in uploadedFiles
                    var found = uploadedFiles.find(function (item) {
                        // check if filename and size matched
                        console.log(item.filename);
                        console.log(file.filename);
                        return (item.filename === file.name);
                    })

                    // If got the file lets make a delete request by id
                    if( found ) {
                        console.log('found.id', found.id);
                        $.ajax({
                            url: "/attachments/" + found.id,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                console.log('deleted');
                            }
                        });
                    }
                });

                // Handle errors
                this.on('error', function(file, response) {
                    var errMsg = response;

                    if( response.message ) errMsg = response.message;
                    if( response.file ) errMsg = response.file[0];

                    $(file.previewElement).find('.dz-error-message').text(errMsg);
                });
            }
        });
    })

// Get Icon for file type
function getIconFromFilename(file) {
    // get the extension
    var ext = file.name.split('.').pop().toLowerCase();

    // if its not an image
    if( file.type.indexOf('image') === -1 ) {

        // handle the alias for extensions
        if(ext === 'docx') {
            ext = 'doc'
        } else if (ext === 'xlsx') {
            ext = 'xls'
        }

        return "/images/icon/"+ext+".svg";
    }

    // return a placeholder for other files
    return '/images/icon/txt.svg';
}
</script>
@endsection