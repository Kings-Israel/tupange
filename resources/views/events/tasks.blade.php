@extends('layouts.master')
@section('title', 'Event Tasks')
@section('css')
   <style>
      #add-task-header {
         margin-bottom: 5px;
         width: 20%;
      }
      .submit-task {
         background: #8FCA27;
         border: none;
         border-radius: 15px;
      }
      .la-save {
         color: #fff !important;
         margin-top: 8px;
         margin-left: 8px;
      }
      .tasks-buttons {
         display: flex;
      }
   </style>
@endsection
@section('content')
   <livewire:event-tasks :event="$event" :categories="$categories" />
@push('scripts')
   <script>
      $('.add-task-form').attr('hidden', 'hidden')
      document.onkeypress = function(e) {
         console.log(e.key);
      }
      $('#assign-user-input').on('change', function(e) {
         console.log(e.target.value)

         if (e.target.value === 'New User') {
            $('#add-event-user-btn').click()
         }
      })
      function sendReminder(id) {
         $('.sending-reminder-text-'+id).removeAttr('hidden')
         $('.send-reminder-btn-'+id).attr('disabled', 'disabled')
         $.ajax({
            method: "GET",
            headers: {
               Accept: "application/json"
            },
            url: `{{ url('client/event/task/${id}/reminder/send') }}`,
            data: {"id": id},
            success: (response) => {
               toastr.options =
                  {
                     "closeButton" : true,
                     "progressBar" : true,
                     "positionClass" : "toast-bottom-right"
                  }
               toastr.success(response.message);
               $('.sending-reminder-text-'+id).attr('hidden', 'hidden')
               $('.send-reminder-btn-'+id).removeAttr('disabled')
            },
            error: (response) => {
               toastr.options =
               {
                  "closeButton" : true,
                  "progressBar" : true,
                  "positionClass" : "toast-bottom-right"
               }
               toastr.error(response.message);
               $('.sending-reminder-text-'+id).attr('hidden', 'hidden')
               $('.send-reminder-btn-'+id).removeAttr('disabled')
            }
         })
      }
   </script>
@endpush
@endsection
