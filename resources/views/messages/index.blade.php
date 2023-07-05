@extends('layouts.master')
@section('title', 'Messages')
@section('css')
    <style>
        .chats {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #202020;
            font-family: "proxima-nova", "Source Sans Pro", sans-serif;
            font-size: 1em;
            letter-spacing: 0.1px;
            color: #32465a;
            text-rendering: optimizeLegibility;
            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.004);
            -webkit-font-smoothing: antialiased;
        }
        #frame {
            width: 95%;
            min-width: 360px;
            max-width: 1200px;
            height: 92vh;
            min-height: 300px;
            max-height: 720px;
            background: #e6eaea;
        }
        #frame #sidepanel {
            float: left;
            min-width: 280px;
            max-width: 340px;
            width: 40%;
            height: 100%;
            background: #2c3e50;
            color: #f5f5f5;
            overflow: hidden;
            position: relative;
        }
        #frame #sidepanel #profile {
            width: 80%;
            margin: 25px auto;
        }
        #frame #sidepanel #profile.expanded .wrap {
            height: 210px;
            line-height: initial;
        }
        #frame #sidepanel #profile.expanded .wrap p {
            margin-top: 20px;
        }
        #frame #sidepanel #profile.expanded .wrap i.expand-button {
            -moz-transform: scaleY(-1);
            -o-transform: scaleY(-1);
            -webkit-transform: scaleY(-1);
            transform: scaleY(-1);
            filter: FlipH;
            -ms-filter: "FlipH";
        }

        #frame #sidepanel #profile .wrap {
            /* height: 60px; */
            /* line-height: 60px; */
            overflow: hidden;
            -moz-transition: 0.3s height ease;
            -o-transition: 0.3s height ease;
            -webkit-transition: 0.3s height ease;
            transition: 0.3s height ease;
        }
        #frame #sidepanel #profile .wrap img {
            width: 60px !important;
            height: 80px !important;
            border-radius: 50% !important;
            object-fit: cover;
            padding: 3px;
            /* border: 2px solid #e74c3c; */
            height: auto;
            float: left;
            cursor: pointer;
            -moz-transition: 0.3s border ease;
            -o-transition: 0.3s border ease;
            -webkit-transition: 0.3s border ease;
            transition: 0.3s border ease;
        }
        #frame #sidepanel #profile .wrap img.online {
            /* border: 2px solid #2ecc71; */
        }
        #frame #sidepanel #profile .wrap img.away {
            border: 2px solid #f1c40f;
        }
        #frame #sidepanel #profile .wrap img.busy {
            border: 2px solid #e74c3c;
        }
        #frame #sidepanel #profile .wrap img.offline {
            border: 2px solid #95a5a6;
        }
        #frame #sidepanel #profile .wrap p {
            float: left;
            margin-left: 15px;
        }
        #frame #sidepanel #profile .wrap i.expand-button {
            float: right;
            margin-top: 23px;
            font-size: 0.8em;
            cursor: pointer;
            color: #435f7a;
        }
        #frame #sidepanel #profile .wrap #status-options {
            position: absolute;
            opacity: 0;
            visibility: hidden;
            width: 150px;
            margin: 70px 0 0 0;
            border-radius: 6px;
            z-index: 99;
            line-height: initial;
            background: #435f7a;
            -moz-transition: 0.3s all ease;
            -o-transition: 0.3s all ease;
            -webkit-transition: 0.3s all ease;
            transition: 0.3s all ease;
        }
        #frame #sidepanel #profile .wrap #status-options.active {
            opacity: 1;
            visibility: visible;
            margin: 75px 0 0 0;
        }
        #frame #sidepanel #profile .wrap #status-options:before {
            content: "";
            position: absolute;
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-bottom: 8px solid #435f7a;
            margin: -8px 0 0 24px;
        }
        #frame #sidepanel #profile .wrap #status-options ul {
            overflow: hidden;
            border-radius: 6px;
        }
        #frame #sidepanel #profile .wrap #status-options ul li {
            padding: 15px 0 30px 18px;
            display: block;
            cursor: pointer;
        }
        #frame #sidepanel #profile .wrap #status-options ul li:hover {
            background: #496886;
        }
        #frame #sidepanel #profile .wrap #status-options ul li span.status-circle {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 5px 0 0 0;
        }
        #frame #sidepanel #profile .wrap #status-options ul li span.status-circle:before {
            content: "";
            position: absolute;
            width: 14px;
            height: 14px;
            margin: -3px 0 0 -3px;
            background: transparent;
            border-radius: 50%;
            z-index: 0;
        }
        #frame #sidepanel #profile .wrap #status-options ul li p {
            padding-left: 12px;
        }
        #frame #sidepanel #profile .wrap #status-options ul li#status-online span.status-circle {
            background: #2ecc71;
        }
        #frame #sidepanel #profile .wrap #status-options ul li#status-online.active span.status-circle:before {
            border: 1px solid #2ecc71;
        }
        #frame #sidepanel #profile .wrap #status-options ul li#status-away span.status-circle {
            background: #f1c40f;
        }
        #frame #sidepanel #profile .wrap #status-options ul li#status-away.active span.status-circle:before {
            border: 1px solid #f1c40f;
        }
        #frame #sidepanel #profile .wrap #status-options ul li#status-busy span.status-circle {
            background: #e74c3c;
        }
        #frame #sidepanel #profile .wrap #status-options ul li#status-busy.active span.status-circle:before {
            border: 1px solid #e74c3c;
        }
        #frame #sidepanel #profile .wrap #status-options ul li#status-offline span.status-circle {
            background: #95a5a6;
        }
        #frame #sidepanel #profile .wrap #status-options ul li#status-offline.active span.status-circle:before {
            border: 1px solid #95a5a6;
        }
        #frame #sidepanel #profile .wrap #expanded {
            padding: 100px 0 0 0;
            display: block;
            line-height: initial !important;
        }
        #frame #sidepanel #profile .wrap #expanded label {
            float: left;
            clear: both;
            margin: 0 8px 5px 0;
            padding: 5px 0;
        }
        #frame #sidepanel #profile .wrap #expanded input {
            border: none;
            margin-bottom: 6px;
            background: #32465a;
            border-radius: 3px;
            color: #f5f5f5;
            padding: 7px;
            width: calc(100% - 43px);
        }
        #frame #sidepanel #profile .wrap #expanded input:focus {
            outline: none;
            background: #435f7a;
        }
        #frame #sidepanel #search {
            border-top: 1px solid #32465a;
            border-bottom: 1px solid #32465a;
            font-weight: 300;
        }
        #frame #sidepanel #search label {
            position: absolute;
            margin: 10px 0 0 20px;
        }
        #frame #sidepanel #search input {
            font-family: "proxima-nova", "Source Sans Pro", sans-serif;
            padding: 10px 0 10px 46px;
            width: calc(100% - 25px);
            border: none;
            background: #32465a;
            color: #f5f5f5;
        }
        #frame #sidepanel #search input:focus {
            outline: none;
            background: #435f7a;
        }
        #frame #sidepanel #search input::-webkit-input-placeholder {
            color: #f5f5f5;
        }
        #frame #sidepanel #search input::-moz-placeholder {
            color: #f5f5f5;
        }
        #frame #sidepanel #search input:-ms-input-placeholder {
            color: #f5f5f5;
        }
        #frame #sidepanel #search input:-moz-placeholder {
            color: #f5f5f5;
        }
        #frame #sidepanel #contacts {
            height: calc(100% - 177px);
            overflow-y: scroll;
            overflow-x: hidden;
        }
        #frame #sidepanel #contacts.expanded {
            height: calc(100% - 334px);
        }
        #frame #sidepanel #contacts::-webkit-scrollbar {
            width: 8px;
            background: #2c3e50;
        }
        #frame #sidepanel #contacts::-webkit-scrollbar-thumb {
            background-color: #243140;
        }
        #frame #sidepanel #contacts ul li.contact {
            position: relative;
            padding: 10px 0 15px 0;
            font-size: 0.9em;
            cursor: pointer;
        }
        #frame #sidepanel #contacts ul li.contact:hover {
            background: #32465a;
        }
        #frame #sidepanel #contacts ul li.contact.active {
            background: #32465a;
            border-right: 5px solid #435f7a;
        }
        #frame #sidepanel #contacts ul li.contact.active span.contact-status {
            border: 2px solid #32465a !important;
        }
        #frame #sidepanel #contacts ul li.contact .wrap {
            width: 88%;
            margin: 0 auto;
            position: relative;
        }
        #frame #sidepanel #contacts ul li.contact .wrap span {
            position: absolute;
            left: 0;
            margin: -2px 0 0 -2px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 2px solid #2c3e50;
            background: #95a5a6;
        }
        #frame #sidepanel #contacts ul li.contact .wrap span.online {
            background: #2ecc71;
        }
        #frame #sidepanel #contacts ul li.contact .wrap span.away {
            background: #f1c40f;
        }
        #frame #sidepanel #contacts ul li.contact .wrap span.busy {
            background: #e74c3c;
        }
        #frame #sidepanel #contacts ul li.contact .wrap img {
            width: 40px;
            border-radius: 50%;
            float: left;
            margin-right: 10px;
        }
        #frame #sidepanel #contacts ul li.contact .wrap .meta {
            padding: 5px 0 0 0;
        }
        #frame #sidepanel #contacts ul li.contact .wrap .meta .name {
            font-weight: 600;
        }
        #frame #sidepanel #contacts ul li.contact .wrap .meta .preview {
            margin: 5px 0 0 0;
            padding: 0 0 1px;
            font-weight: 400;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            -moz-transition: 1s all ease;
            -o-transition: 1s all ease;
            -webkit-transition: 1s all ease;
            transition: 1s all ease;
        }
        #frame #sidepanel #contacts ul li.contact .wrap .meta .preview span {
            position: initial;
            border-radius: initial;
            background: none;
            border: none;
            padding: 0 2px 0 0;
            margin: 0 0 0 1px;
            opacity: 0.5;
        }
        #frame #sidepanel #bottom-bar {
            position: absolute;
            width: 100%;
            bottom: 0;
        }
        #frame #sidepanel #bottom-bar button {
            float: left;
            border: none;
            width: 50%;
            padding: 10px 0;
            background: #32465a;
            color: #f5f5f5;
            cursor: pointer;
            font-size: 0.85em;
            font-family: "proxima-nova", "Source Sans Pro", sans-serif;
        }
        #frame #sidepanel #bottom-bar button:focus {
            outline: none;
        }
        #frame #sidepanel #bottom-bar button:nth-child(1) {
            border-right: 1px solid #2c3e50;
        }
        #frame #sidepanel #bottom-bar button:hover {
            background: #435f7a;
        }
        #frame #sidepanel #bottom-bar button i {
            margin-right: 3px;
            font-size: 1em;
        }
        #frame .content {
            float: right;
            width: 60%;
            height: 100%;
            overflow: hidden;
            position: relative;
        }
        #frame .content .contact-profile {
            width: 100%;
            height: 60px;
            line-height: 60px;
            background: #f5f5f5;
        }
        #frame .content .contact-profile img {
            width: 40px;
            border-radius: 50%;
            float: left;
            margin: 9px 12px 0 9px;
        }
        #frame .content .contact-profile p {
            float: left;
        }
        #frame .content .contact-profile .social-media {
            float: right;
        }
        #frame .content .contact-profile .social-media i {
            margin-left: 14px;
            cursor: pointer;
        }
        #frame .content .contact-profile .social-media i:nth-last-child(1) {
            margin-right: 20px;
        }
        #frame .content .contact-profile .social-media i:hover {
            color: #435f7a;
        }
        #frame .content .messages {
            height: auto;
            min-height: calc(100% - 93px);
            max-height: calc(100% - 93px);
            overflow-y: scroll;
            overflow-x: hidden;
        }
        #frame .content .messages::-webkit-scrollbar {
            width: 8px;
            background: rgba(0, 0, 0, 0);
        }
        #frame .content .messages::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.3);
        }
        #frame .content .messages ul li {
            display: inline-block;
            clear: both;
            float: left;
            margin: 15px 15px 5px 15px;
            width: calc(100% - 25px);
            font-size: 0.9em;
        }
        #frame .content .messages ul li:nth-last-child(1) {
            margin-bottom: 20px;
        }
        #frame .content .messages ul li.sent img {
            margin: 6px 8px 0 0;
        }
        #frame .content .messages ul li.sent p {
            background: #435f7a;
            color: #f5f5f5;
        }
        #frame .content .messages ul li.replies img {
            float: right;
            margin: 6px 0 0 8px;
        }
        #frame .content .messages ul li.replies p {
            background: #f5f5f5;
            float: right;
        }
        #frame .content .messages ul li img {
            width: 22px;
            border-radius: 50%;
            float: left;
        }
        #frame .content .messages ul li p {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 20px;
            max-width: 205px;
            line-height: 130%;
        }

        #frame .content .message-input {
            position: absolute;
            bottom: 0;
            width: 100%;
            z-index: 99;
        }
        #frame .content .message-input .wrap {
            position: relative;
        }
        #frame .content .message-input .wrap input {
            font-family: "proxima-nova", "Source Sans Pro", sans-serif;
            float: left;
            border: none;
            width: calc(100% - 90px);
            padding: 11px 32px 10px 8px;
            font-size: 0.8em;
            color: #32465a;
        }
        #frame .content .message-input .wrap input:focus {
            outline: none;
        }
        #frame .content .message-input .wrap .attachment {
            position: absolute;
            right: 60px;
            z-index: 4;
            margin-top: 10px;
            font-size: 1.1em;
            color: #435f7a;
            opacity: 0.5;
            cursor: pointer;
        }
        #frame .content .message-input .wrap .attachment:hover {
            opacity: 1;
        }
        #frame .content .message-input .wrap button {
            float: right;
            border: none;
            width: 50px;
            padding: 12px 0;
            cursor: pointer;
            background: #32465a;
            color: #f5f5f5;
        }
        #frame .content .message-input .wrap button:hover {
            background: #435f7a;
        }
        #frame .content .message-input .wrap button:focus {
            outline: none;
        }
        .no-conversation-container {
         height: 100%;
        }
        .no-conversation-text-main {
         font-weight: 600;
         font-size: 22px;
         text-align: center;
        }
        .no-conversation-text-side {
         font-weight: 600;
         font-size: 22px;
         text-align: center;
        }
        .orders-link {
         color: lawngreen;
        }

        @media screen and (min-width: 900px) {
            #frame .content {
                width: calc(100% - 340px);
            }
        }

        @media screen and (min-width: 735px) {
            #frame .content .messages ul li p {
                max-width: 300px;
            }
        }
        @media screen and (max-width: 735px) {
         .no-conversation-text-side {
            display: none;
         }
           #frame {
              margin-top: 100px;
           }
            #frame #sidepanel #contacts ul li.contact {
                padding: 6px 0 46px 8px;
            }
            #frame #sidepanel #contacts {
                height: calc(100% - 149px);
                overflow-y: scroll;
                overflow-x: hidden;
            }
            #frame #sidepanel #contacts::-webkit-scrollbar {
                display: none;
            }
            #frame #sidepanel #contacts ul li.contact .wrap {
                width: 100%;
            }
            #frame #sidepanel #contacts ul li.contact .wrap img {
                margin-right: 0px;
            }
            #frame #sidepanel #contacts ul li.contact .wrap .meta {
                display: none;
            }
            #frame #sidepanel #bottom-bar button {
                float: none;
                width: 100%;
                padding: 15px 0;
            }
            #frame #sidepanel #bottom-bar button:nth-child(1) {
                border-right: none;
                border-bottom: 1px solid #2c3e50;
            }
            #frame #sidepanel #bottom-bar button i {
                font-size: 1.3em;
            }
            #frame #sidepanel #bottom-bar button span {
                display: none;
            }
            #frame .content {
                width: calc(100% - 58px);
                min-width: 300px !important;
            }
            #frame .content .messages {
                max-height: calc(100% - 105px);
            }
            #frame .content .message-input .wrap .attachment {
                margin-top: 17px;
                right: 65px;
            }
            #frame .content .message-input .wrap input {
                padding: 15px 32px 16px 8px;
            }
            #frame .content .message-input .wrap button {
                padding: 16px 0;
            }
            #frame #sidepanel #profile .wrap #status-options ul li {
                padding: 15px 0 35px 22px;
            }
            #frame #sidepanel #profile .wrap #status-options.active {
                margin-top: 62px;
            }
            #frame #sidepanel #profile .wrap i.expand-button {
                display: none;
            }
            #frame #sidepanel #profile .wrap p {
                display: none;
            }
            #frame #sidepanel {
                width: 58px;
                min-width: 58px;
            }
            #frame #sidepanel #profile {
                width: 100%;
                margin: 0 auto;
                padding: 5px 0 0 0;
                background: #32465a;
            }
            #frame #sidepanel #profile .wrap {
                height: 55px;
            }
            #frame #sidepanel #profile .wrap img {
                width: 40px;
                margin-left: 4px;
            }
            #frame #sidepanel #profile .wrap #status-options {
                width: 58px;
                margin-top: 57px;
            }
            #frame #sidepanel #profile .wrap #status-options:before {
                margin-left: 23px;
            }
            #frame #sidepanel #profile .wrap #status-options ul li span.status-circle {
                width: 14px;
                height: 14px;
            }
            #frame #sidepanel #profile .wrap #status-options ul li p {
                display: none;
            }
            #frame #sidepanel #profile .wrap #status-options ul li span.status-circle:before {
                height: 18px;
                width: 18px;
            }
            #frame #sidepanel #search {
                display: none;
            }
        }
        @media screen and (max-width: 360px) {
            #frame {
                width: 100%;
                height: 100vh;
            }
        }
    </style>
@endsection
@section('content')
   <div class="chats">
      <div id="frame">
         @include('messages.sidenav')
         <div class="content">
            <div class="no-conversation-container">
               <p class="no-conversation-text-main">Select a conversation to start chatting...</p>
            </div>
         </div>
      </div>
   </div>
   <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
   <script src="https://use.typekit.net/hoy3lrg.js"></script>
   <script>try{Typekit.load({ async: true });}catch(e){}</script>
   <script>
      if (checkIfEmailInString(message)) {
            toastr.options =
               {
                  "closeButton" : true,
                  "progressBar" : true,
                  "positionClass" : "toast-bottom-right"
               }
            toastr.error("You cannot share email addresses!!");

            return
         }
         if (checkIfPhoneNumberInString(message)) {
            toastr.options =
               {
                  "closeButton" : true,
                  "progressBar" : true,
                  "positionClass" : "toast-bottom-right"
               }
            toastr.error("You cannot share phone numbers!!");

            return
         }
      $(".messages").animate({ scrollTop: $(document).height() }, "fast");

      $("#profile-img").click(function() {
         $("#status-options").toggleClass("active");
      });

      $(".expand-button").click(function() {
         $("#profile").toggleClass("expanded");
         $("#contacts").toggleClass("expanded");
      });

      $("#status-options ul li").click(function() {
         $("#profile-img").removeClass();
         $("#status-online").removeClass("active");
         $("#status-away").removeClass("active");
         $("#status-busy").removeClass("active");
         $("#status-offline").removeClass("active");
         $(this).addClass("active");

         if($("#status-online").hasClass("active")) {
               $("#profile-img").addClass("online");
         } else if ($("#status-away").hasClass("active")) {
               $("#profile-img").addClass("away");
         } else if ($("#status-busy").hasClass("active")) {
               $("#profile-img").addClass("busy");
         } else if ($("#status-offline").hasClass("active")) {
               $("#profile-img").addClass("offline");
         } else {
               $("#profile-img").removeClass();
         };

         $("#status-options").removeClass("active");
      });

      function newMessage() {
         message = $(".message-input input").val();
         if($.trim(message) == '') {
               return false;
         }
         $('<li class="sent"><img src="http://emilcarlsson.se/assets/mikeross.png" alt="" /><p>' + message + '</p></li>').appendTo($('.messages ul'));
         $('.message-input input').val(null);
         $('.contact.active .preview').html('<span>You: </span>' + message);
         $(".messages").animate({ scrollTop: $('.messages').height() }, "fast");
      };

      $('.submit').click(function() {
         newMessage();
      });

      $(window).on('keydown', function(e) {
         if (e.which == 13) {
               newMessage();
               return false;
         }
      });
   </script>
@endsection
