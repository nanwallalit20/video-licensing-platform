<div id="docusignWebEmbed"></div>
@section('scripts')
    <script src='{{ env('DOCUSIGN_JS_URL') }}'></script>

    <script>
        async function docusignWebEmbedForm(e) {
            let params = e.param

            $(e.form[0]).find('button').addClass('disabled')
            $('.loader').show()

            if (!e.param) {
                return false
            }

            let integration_key = '{{ env('DOCUSIGN_INTEGRATION_KEY') }}'
            let instance_token = params.instanceToken
            let formUrl = params.formUrl

            const docusignDivId = 'docusignWebEmbed'
            const {loadDocuSign} = window.DocuSign
            const docusign = await loadDocuSign(integration_key);

            const webFormOptions = {
                // Optional field that can prefill values in the form. This overrides the formValues field in the API request
                prefillValues: {},
                // Used with the runtime API workflow, for private webforms this is needed to render anything
                instanceToken: instance_token,
                // Controls whether the progress bar is shown or not
                hideProgressBar: false,
                // These styles get passed directly to the iframe that is rendered
                iframeStyles: {
                    minHeight: "1500px",
                },
                // Controls the auto resize behavior of the iframe
                autoResizeHeight: true
            };

            const webFormWidget = docusign.webforms({
                url: formUrl,
                options: webFormOptions,
            });

            //Basic milestones in this workflow
            webFormWidget.on('ready', (event) => {
                // event = { type: 'ready' };
                $('.loader').hide()
                console.log('debug form loaded', event);
            });

            webFormWidget.on('submitted', (event) => {
                // event = { type: 'submitted', envelopeId: 'abcd1234' };
                console.log('debug form submitted', event);
            });

            webFormWidget.on('signingReady', (event) => {
                // event = { type: 'submitted', envelopeId: 'abcd1234' };
                console.log('debug form signingReady', event);
            });

            webFormWidget.on('sessionEnd', (event) => {
                // event = { type: 'sessionEnd', sessionEndType: 'sessionTimeout' };
                // event = {
                //   type: 'sessionEnd',
                //   sessionEndType: 'signingResult',
                //   signingResultType: 'signing_complete',
                //   returnUrl: 'bigcorp.com',
                //   envelopeId: 'abcd1234',
                // };
                // event = { type: 'sessionEnd', sessionEndType: 'remoteSigningInitiated', envelopeId: 'abcd1234' };

                // Save info for InReview
                saveEnvelopeData(event)
                console.log('debug form signingResult', event);
            });

            //Less commonly used events
            webFormWidget.on('userActivity', (event) => {
                // event = { type: 'userActivity', activityType: 'click' | 'keydown' };
                console.log('debug form userActivity', event);
            });

            webFormWidget.mount("#" + docusignDivId);
        }

        function saveEnvelopeData(event) {
            let formData = new FormData()
            if (event.envelopeId) {
                formData.set('envelope_id', event.envelopeId)
                $.post('{{ route('title.revenuePlanSign', request()->route('slug')) }}', formData, function (res) {
                    window.location.href = res.url
                })
            }
        }
    </script>
@endsection
