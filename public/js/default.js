let defaultJs = {
    ready: function () {
        $(document).on('change', '.title_revenue_plan', defaultJs.title_revenue_plan)
        $(document).on('click', '.viewAgreementModal', defaultJs.viewAgreementModal)
        $(document).on('click', '.view_title_profile', defaultJs.viewTitleProfile)
        defaultJs.defaultTrigger()
    },
    defaultTrigger: function () {
        $(document).find('.title_revenue_plan').trigger('change')
    },
    title_revenue_plan: function () {
        let plan = $(this).val()
        let selector = $('.plan_details_area')
        selector.find('div').addClass('d-none')
        selector.find('.plan_' + plan).removeClass('d-none')
    },
    viewAgreementModal: function () {
        let selectorModal = $('#view_agreement_modal');
        let documentUrl = $(this).data('document-url')
        $('.loader').show()
        $.get(documentUrl, function (e) {
            let document = e.data[0] ?? null
            let iframeHtml = '<iframe id="doc_iframe" src="' + document.url + '" width="100%" height="100%"></iframe>';
            selectorModal.find('.modal-body').html(iframeHtml)
            selectorModal.modal('show')
            $('.loader').hide()
        })
        setTimeout(function () {
            console.log('hide now')
            $('.loader').hide()
        }, 5000)
    },
    viewTitleProfile: function () {
        let selectorModal = $('#view_title_profile_modal');
        let titleProfileRoute = $(this).data('title-profile-route')
        $('.loader').show()
        $.get(titleProfileRoute, function (html) {
            selectorModal.find('.modal-body').html(html)
            selectorModal.modal('show')
            $('.loader').hide()
        })
        setTimeout(function () {
            $('.loader').hide()
        }, 5000)
    }
}
var justify;
function pageReload(){
    setTimeout(() => {
        window.location.reload();
    }, 3000);
}
function closeTitleRequestPaymentModal(){
    $('#paymentLinkModalContent').html('');
    $('#paymentLinkModal').modal('hide');
}

$(document).ready(function () {
    defaultJs.ready()
    justify = new Justify({
        debug: true,
        loaderClass: 'loader',
        underfieldError: true,
        showBorderError: true,
        splitMessage: true,
        csrfTokenName: '_token',
        csrfToken: $('meta[name=csrf-token]').attr('content'),
        justifyError: true,
        customJustify: function (type, message) {
            setTimeout(function () {
                if (typeof notify === 'undefined') {
                    console.error("Noty is not defined. Make sure Noty.js is included.");
                    return;
                }
                notify.show(type, message)
            }, 200)

        }
    });

    const tooltipTriggerList = $('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.tooltip();

    // Handle copy functionality for any element with copy-btn class
    $(document).on('click', '.copy-btn', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const textToCopy = $btn.data('copy-text');

        // Copy to clipboard
        navigator.clipboard.writeText(textToCopy)
        .then(() => {
            // Get tooltip instance
            const tooltip = bootstrap.Tooltip.getInstance($btn[0]);

            // Update tooltip and show it
            $btn
                .attr('data-bs-original-title', 'Copied!')
                .tooltip('show');

            // Reset tooltip after delay
            setTimeout(() => {
                $btn
                    .attr('data-bs-original-title', 'Copy')
                    .tooltip('hide');
            }, 1000);
        })
        .catch(err => {
            console.error('Failed to copy text: ', err);
            // Show error tooltip
            $btn.attr('data-bs-original-title', 'Failed to copy!').tooltip('show');
        });
    });
})
