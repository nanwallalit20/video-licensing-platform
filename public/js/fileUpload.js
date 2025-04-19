class ChunkUpload {
    chunkSize = 5 * 1024 * 1024; // 5MB chunk size
    uploadId = null; // Initialize uploadId outside the loop
    class_container = 'chunk_upload_container'
    class_progress = 'chunk_progress'
    class_cancel = 'chunk_upload_cancel_button'
    request_xhr = null
    is_cancelled = false

    /**
     *
     * @param file
     * @param selector
     */
    constructor(file, selector) {
        this.file = file
        this.current = selector
        this.url = this.current.closest('.' + this.class_container).data('url')
        this.is_cancelled = false

        let originalFilename = this.file.name
        let fileExtension = originalFilename.substring(originalFilename.lastIndexOf('.') + 1); // Get the extension
        this.generateUniqueName(fileExtension)
    }

    generateUniqueName(fileExtension) {
        this.fileName = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16) + '.' + fileExtension;
        });
    }

    init() {
        this.totalChunks = Math.ceil(this.file.size / this.chunkSize);
        if (this.totalChunks > 1000) {
            this.chunkSize = this.chunkSize * 2
            return this.init();
        }
        if (this.file) {
            this.progressBar(0)
            this.current.closest('.' + this.class_container).find('.fieldName').val('')
            this.startUpload(0); // Pass the variables
        }
    }

    progressBar(range) {
        let rangeNumber = parseFloat(range)
        if (rangeNumber >= 100) {
            this.current.closest('.' + this.class_container).find('.' + this.class_progress).text('Done')
        } else if (rangeNumber == 0) {
            this.current.closest('.' + this.class_container).find('.' + this.class_progress).text('Uploading...')
        } else {
            this.current.closest('.' + this.class_container).find('.' + this.class_progress).text(rangeNumber + '%')
        }

        if (rangeNumber == 0) {
            this.current.closest('.' + this.class_container).find('.' + this.class_progress).css('width', '100%')
        } else {
            this.current.closest('.' + this.class_container).find('.' + this.class_progress).css('width', rangeNumber + '%')
        }

    }

    startUpload(chunkIndex) {

        if (this.is_cancelled) {
            return false;
        }

        this.current.closest('.' + this.class_container).find('.' + this.class_cancel).show()

        let start = chunkIndex * this.chunkSize;
        let end = Math.min((chunkIndex + 1) * this.chunkSize, this.file.size);
        let chunk = this.file.slice(start, end);

        let formData = new FormData();
        formData.append('file', chunk, this.fileName);
        formData.append('chunkIndex', chunkIndex);
        formData.append('totalChunks', this.totalChunks);
        if (this.uploadId) {
            formData.append('uploadId', this.uploadId);
        }


        let _this = this

        this.request_xhr = $.ajax({
            url: this.url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.status == undefined) {
                    return;
                }

                if (!data.status) {
                    // Same if error
                    _this.startUpload(chunkIndex); // Pass in the recursive call
                }

                // Display percentage of uploaded file
                let per = ((chunkIndex + 1) / _this.totalChunks) * 100
                let percentageUploaded = per.toFixed(2)
                _this.progressBar(percentageUploaded)
                // _this.current.closest('.' + _this.class_container).find('.' + _this.class_progress).text(percentageUploaded + '%')
                // _this.current.closest('.' + _this.class_container).find('.' + _this.class_progress).css('width', percentageUploaded + '%')

                if (data.uploadId) {
                    _this.uploadId = data.uploadId;
                }

                if (chunkIndex < _this.totalChunks - 1) {
                    _this.startUpload(chunkIndex + 1); // Pass in the recursive call
                } else {
                    let fieldName = _this.current.data('name')
                    if (fieldName) {
                        _this.current.closest('.' + _this.class_container).find('.fieldName').val(data.file.name)
                    }
                    _this.current.closest('.' + _this.class_container).find('.' + _this.class_cancel).hide()
                    console.log("File uploaded successfully!");
                }
            },
            error: function (xhr, status, error) {
                _this.abort_upload()
                // Handle error
                _this.displayError(xhr)
            }
        });
    }

    abort_upload() {
        this.is_cancelled = true
        this.request_xhr.abort()
        this.progressBar(100)
        this.current.closest('.' + this.class_container).find('.' + this.class_progress).text('cancelled')
        // this.current.closest('.' + this.class_container).find('.' + this.class_progress).css('width', '100%')
        this.current.closest('.' + this.class_container).find('.' + this.class_cancel).hide()
    }

    displayError(xhr) {
        var errorMessage = 'An error occurred';
        if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.file) {
            errorMessage = xhr.responseJSON.errors.file.join(', ');
        } else if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
        } else if (xhr.responseText) {
            errorMessage = xhr.responseText;
        }
        this.current.closest('.' + this.class_container).find('.' + this.class_progress).text(errorMessage);

    }

}


let _fileUpload = {
    ready: function () {
        // $(document).on('change', '.chunk_upload', _fileUpload.chunk_upload);
        $(document).on('change', '.chunk_upload', _fileUpload.chunkUploadInitiate);
        $('.chunk_upload_container').find('.chunk_upload_cancel_button').hide()
    },
    chunkUploadInitiate(event) {
        let file = event.target.files[0];
        let defClass = new ChunkUpload(file, $(this))
        defClass.init()
        $(this).closest('.chunk_upload_container').find('.chunk_upload_cancel_button').on('click', function () {
            defClass.abort_upload()
        })
    }
};

$(document).ready(function () {  // Use $(document).ready() or $(function() {})
    _fileUpload.ready();
});
