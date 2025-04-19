<template id="cast-template">
    <div class="row cast-row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="actor">Actor<span class="text-danger">*</span></label>
                <input type="text" name="actor[]" class="form-control">
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="character">Character<span class="text-danger">*</span></label>
                <input type="text" name="character[]" class="form-control">
            </div>
        </div>
        <div class="col-md-2 mt-auto">
            <div class="form-group">
                <button class="form-control remove-cast" type="button">Remove</button>
            </div>
        </div>
    </div>
</template>
