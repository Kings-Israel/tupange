@extends('layouts.master')
@section('title', 'Upload Image')
@section('content')
<div class="container">
   <div class="site-main" id="main">
      <form action="{{ route('upload-image') }}" method="POST" enctype="multipart/form-data" >
         @csrf
         <div class="row">
             <div class="col-lg-4">
                 <div class="field-group field-file">
                     <label>Service Image</label>
                     <label class="preview">
                         <input type="file" name="image" accept=".jpg,.png,.jpeg" class="upload-file" data-max-size="50000">
                         <img class="img_preview" src="{{ asset('assets/images/no-image.png') }}" alt="" />
                         <i class="la la-cloud-upload-alt"></i>
                     </label>
                     <div class="field-note">Maximum file size: 5 MB.</div>
                     <x-input-error for="service_image"></x-input-error>
                 </div>
             </div>
         </div>
         <br>
         <input type="submit" value="Submit" class="btn">
      </form>
   </div>
</div>
@endsection
