<div class="modal-header">
    <h5 class="modal-title" id="paymentLinkModalLabel">Create Payment Link</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('superadmin.titleRequests.createPaymentLink') }}" method="post" class="ajaxForm">
    <input type="hidden" name="order_id" value="{{ $order->id }}">
    <div class="modal-body">
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" placeholder="Enter amount" class="form-control" id="amount" name="amount" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="currency" class="form-label">Currency</label>
            <select class="form-select" id="currency" name="currency" required>
                @foreach ($currencies as $currency)
                    <option value="{{ $currency->code }}">{{ $currency->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn bg-gradient-admin-blue">Create & Send Link</button>
    </div>
</form>
