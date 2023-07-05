@push('scripts')
   <script>
      @if(Session::has('success'))
         toastr.options =
         {
               "closeButton" : true,
               "progressBar" : true,
               "positionClass" : "toast-bottom-right"
         }
         toastr.success("{{ session('success') }}");
         {{ session()->forget('success') }}
      @endif
      @if(Session::has('error'))
         toastr.options =
         {
               "closeButton" : true,
               "progressBar" : true,
               "positionClass" : "toast-bottom-right"
         }
         toastr.error("{{ session('error') }}");
         {{ session()->forget('error') }}
      @endif
      @if(Session::has('info'))
         toastr.options =
         {
               "closeButton" : true,
               "progressBar" : true,
               "positionClass" : "toast-bottom-right"
         }
         toastr.info("{{ session('info') }}");
         {{ session()->forget('info') }}
      @endif
      @if(Session::has('message'))
         toastr.options =
         {
               "closeButton" : true,
               "progressBar" : true,
               "positionClass" : "toast-bottom-right"
         }
         toastr.info("{{ session('message') }}");
         {{ session()->forget('message') }}
      @endif
      @if(Session::has('warning'))
         toastr.options =
         {
               "closeButton" : true,
               "progressBar" : true,
               "positionClass" : "toast-bottom-right"
         }
         toastr.warning("{{ session('warning') }}");
         {{ session()->forget('warning') }}
      @endif
   </script>
@endpush
