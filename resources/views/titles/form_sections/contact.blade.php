
<form id="contact-form" action="{{ route("titleEditContact",['uuid' => $title->uuid]) }}" method="post"
      class="ajaxForm">
    <div class="row row-cols-1 row-cols-md-2">
        <div class="col">
            <div class="card mt-4 shadow-none">
                <div class="card-header bg-white">
                    <h4>Primary Contact Info:</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">

                            <input type="hidden" name="title_id" id="title_id" value="{{ $title->id }}">
                            <div class="form-group">
                                <label for="primary_name">Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="primary_name" type="text"
                                       placeholder="Enter Your Primary Name"
                                       value="{{ $primaryContact->getContact->name ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="primary_role">Role <span class="text-danger">*</span></label>
                                <input class="form-control" name="primary_role" type="text"
                                       placeholder="Enter Your Primary Role"
                                       value="{{ $primaryContact->getContact->role ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="primary_email">Email <span class="text-danger">*</span></label>
                                <input class="form-control" name="primary_email" type="text"
                                       placeholder="Enter Your Primary Email"
                                       value="{{ $primaryContact->getContact->email ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="primary_phone">Phone <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control select2-dropdown" name="primary_phone_code">
                                        @foreach($countries as $country)
                                            <option value="+{{ $country->country_code }}"
                                                {{ isset($primaryContact->getContact->phone_country_code) && $primaryContact->getContact->phone_country_code == $country->country_code ? 'selected' : '' }}>
                                                +{{ $country->country_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input class="form-control ps-3" name="primary_phone" type="text"
                                           placeholder="Enter Your Primary Phone"
                                           value="{{ $primaryContact->getContact->phone ?? '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="primary_whatsapp_number">Whatsapp Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control select2-dropdown" name="primary_whatsapp_code">
                                        @foreach($countries as $country)
                                            <option value="+{{ $country->country_code }}"
                                                {{ isset($primaryContact->getContact->whatsapp_country_code) && $primaryContact->getContact->whatsapp_country_code == $country->country_code ? 'selected' : '' }}>
                                                +{{ $country->country_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input class="form-control ps-3" name="primary_whatsapp_number" type="text"
                                           placeholder="Enter Your Primary Whatsapp Number"
                                           value="{{ $primaryContact->getContact->whatsapp_number ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mt-4 shadow-none">
                <div class="card-header bg-white d-flex align-items-center">
                    <h4>Secondary Contact Info:</h4>
                    &nbsp;&nbsp;&nbsp;
                    <div class="form-check form-switch">
                        @php
                            $sameContact = true;
                        @endphp
                        @if($primaryContact && $secondaryContact && ($primaryContact->getContact->name != $secondaryContact->getContact->name ||
                                $primaryContact->getContact->role != $secondaryContact->getContact->role ||
                                $primaryContact->getContact->email != $secondaryContact->getContact->email ||
                                $primaryContact->getContact->phone != $secondaryContact->getContact->phone ||
                                $primaryContact->getContact->whatsapp_number != $secondaryContact->getContact->whatsapp_number))
                            @php
                                $sameContact = false;
                            @endphp
                        @endif
                        <input class="form-check-input" name="sameContact" type="checkbox"
                               {{ $sameContact ? 'checked' : '' }} role="switch"
                               id="sameAsPrimary">
                        <label class="form-check-label" for="sameAsPrimary">Same as primary</label>
                    </div>
                </div>
                <div class="card-body" style="display: {{ $sameContact ? 'none' : 'block' }}">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="secondary_name">Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="secondary_name" type="text"
                                       placeholder="Enter Your Secondary Name"
                                       value="{{ $secondaryContact->getContact->name ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="secondary_role">Role <span class="text-danger">*</span></label>
                                <input class="form-control" name="secondary_role" type="text"
                                       placeholder="Enter Your Secondary Role"
                                       value="{{ $secondaryContact->getContact->role ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="secondary_email">Email <span class="text-danger">*</span></label>
                                <input class="form-control" name="secondary_email" type="text"
                                       placeholder="Enter Your Secondary Email"
                                       value="{{ $secondaryContact->getContact->email ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="secondary_phone">Phone <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control select2-dropdown" name="secondary_phone_code">
                                        @foreach($countries as $country)
                                            <option value="+{{ $country->country_code }}"
                                                {{ isset($secondaryContact->getContact->phone_country_code) && $secondaryContact->getContact->phone_country_code == $country->country_code ? 'selected' : '' }}>
                                                +{{ $country->country_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input class="form-control ps-3" name="secondary_phone" type="text"
                                           placeholder="Enter Your Secondary Phone"
                                           value="{{ $secondaryContact->getContact->phone ?? '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="secondary_whatsapp_number">Whatsapp Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control select2-dropdown" name="secondary_whatsapp_code">
                                        @foreach($countries as $country)
                                            <option value="+{{ $country->country_code }}"
                                                {{ isset($secondaryContact->getContact->whatsapp_country_code) && $secondaryContact->getContact->whatsapp_country_code == $country->country_code ? 'selected' : '' }}>
                                                +{{ $country->country_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input class="form-control ps-3" name="secondary_whatsapp_number" type="text"
                                           placeholder="Enter Your Secondary Whatsapp Number"
                                           value="{{ $secondaryContact->getContact->whatsapp_number ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3 d-flex justify-content-end">
        <div class="col-md-3">
            <div class="form-group d-flex">
                <button type="button" class="form-control custom_submit_btn text-white back_tab">Previous</button>
                <button type="submit" class="form-control custom_submit_btn text-white">Save & Next</button>
            </div>
        </div>
    </div>
</form>



