<div class="modal fade " id="request-more-info" tabindex="-1" role="dialog" aria-labelledby="request-more-infoTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="request-model-title">New message to seller</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="message_subject" class="col-form-label">Subject:</label>
                        <input type="text" class="form-control" id="message_subject">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Message:</label>
                        <textarea class="form-control" rows="7" cols="7" id="more-info-message"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="send_message_seller" class="btn bg-gradient-admin-blue">Send message
                    </button>
                </div>
            </div>
        </div>
    </div>
