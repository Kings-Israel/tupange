<div class="modal fade signup" id="service-images-upload-{{ $service->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
        <div class="modal-content" id="sign-up">
            <span>
                <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                    <i class="las la-times la-24-black"></i>
                </a><!-- .popup__close -->
            </span>
            <div class="modal-body">
                <h3 class="modal-header-title">Service Image</h3>
                <div class="login-form">
                    <div class="submit-section">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <form action="{{ route('vendor.service.images.add') }}" method="POST" enctype="multipart/form-data" >
                                    @csrf
                                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="field-group field-file">
                                                <label>Service Image</label>
                                                <label class="preview">
                                                    <input type="file" name="service_image" accept=".jpg,.png,.jpeg" class="upload-file" data-max-size="50000" value="{{ old('service_image') }}">
                                                    <img class="img_preview" src="{{ asset('assets/images/no-image.png') }}" alt="" />
                                                    <i class="la la-cloud-upload-alt"></i>
                                                </label>
                                                <div class="field-note">Maximum file size: 5 MB.</div>
                                                <x-input-error for="service_image"></x-input-error>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="field-group field-input">
                                                <label class="label">Image Description</label>
                                                <textarea name="service_image_description" class="form-control" rows="5" id="service_image_description">{{ old('service_image_description') }}</textarea>
                                                <x-input-error for="service_image_description"></x-input-error>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <input type="submit" value="Submit" class="btn">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
