<div class="modal fade signup" id="add-gift-{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered login-pop-form" role="document">
        <div class="modal-content" id="sign-up">
            <span>
                <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close m-2" style="float: right; font-size: 20px">
                    <i class="las la-times la-24-black"></i>
                </a><!-- .popup__close -->
            </span>
            <div class="modal-body">
                <h3 class="modal-header-title">Register Gift</h3>
                <div class="login-form">
                    <div class="submit-section">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <form action="{{ route('client.event.gift.add', $event) }}" method="POST" enctype="multipart/form-data" >
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{ $event->id }}" id="">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="field-group field-input">
                                                <label for="company_logo">Title</label>
                                                <input type="text" name="title" class="form-control" required>
                                                <x-input-error for="title"></x-input-error>
                                            </div>
                                            <div class="field-group field-input">
                                                <h4 class="label">Description</h4>
                                                <textarea name="description" id="" cols="41"></textarea>
                                                <x-input-error for="description"></x-input-error>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="field-group field-file">
                                                <label for="company_logo">Image</label>
                                                <label for="company_logo" class="preview">
                                                    <input type="file" id="company_logo" name="image" accept=".jpg,.png,.jpeg" class="upload-file" data-max-size="50000">
                                                    <img class="img_preview" src="{{ asset('assets/images/no-image.png') }}" alt="" />
                                                    <i class="la la-cloud-upload-alt"></i>
                                                </label>
                                                <div class="field-note">Maximum file size: 5 MB.</div>
                                                <x-input-error for="image"></x-input-error>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="field-group field-input">
                                                <label for="company_logo">Value</label>
                                                <input type="number" name="value" class="form-control" min="1">
                                                <x-input-error for="value"></x-input-error>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="field-group field-input">
                                                <h4 class="label">Received Date</h4>
                                                <input type="date" name="received_date" class="form-control" id="">
                                                <x-input-error for="notify_due"></x-input-error>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="field-group field-input">
                                                <label for="company_logo">Received By</label>
                                                <input type="text" name="received_by" class="form-control">
                                                <x-input-error for="received_by"></x-input-error>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="field-group field-input">
                                                <h4 class="label">Received From</h4>
                                                <input type="text" name="received_from" class="form-control" id="">
                                                <x-input-error for="received_from"></x-input-error>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="field-group field-input">
                                                <label for="company_logo">Phone</label>
                                                <input type="phone_number" name="phone" class="form-control">
                                                <x-input-error for="value"></x-input-error>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="field-group field-input">
                                                <h4 class="label">Email</h4>
                                                <input type="email" name="email" class="form-control" id="">
                                                <x-input-error for="email"></x-input-error>
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
