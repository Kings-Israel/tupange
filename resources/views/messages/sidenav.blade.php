<div id="sidepanel">
   <div id="profile">
      <div class="wrap">
         <img src="{{ auth()->user()->getAvatar(auth()->user()->avatar) }}" class="rounded-circle online" alt="" />
         <p>{!! Auth::user()->f_name !!} {!! Auth::user()->l_name !!}</p>
      </div>
   </div>
   <livewire:message-sidenav />
</div>
