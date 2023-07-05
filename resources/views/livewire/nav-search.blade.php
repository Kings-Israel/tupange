<div class="navigation-search" x-data="{ isOpen: true }" x-on:click.away="isOpen = false">
   <form action="{{ route('global.search') }}" method="POST">
      @csrf
      <div class="nav-search">
         <input
            type="text"
            autocomplete="off"
            wire:model.debounce.500ms="nav_search"
            name="search"
            class="form-control"
            required
            placeholder="Search for services and vendors.."
            @focus="isOpen = true"
            @keydown="isOpen = true"
            @keydown.escape.window="isOpen = false"
            @keydown.shift.tab="isOpen = false"
         >
         <div class="nav-search-btn">
            <button class="btn" x-on:click="isOpen = !isOpen">
               <i wire:loading.remove class="las la-search"></i>
               <div wire:loading class="lds-dual-ring"></div>
            </button>
         </div>
      </div>
      @if (strlen($nav_search) >= 2)
         <div class="search-results" x-show="isOpen" x-transition.duration.300ms>
            @if ($results->count() > 0)
               <ul>
                  @foreach ($results as $result)
                     <li class="result">
                        <a class="result-link" href="{{ $result->url }}" @if ($loop->last) @keydown.tab="isOpen = false" @endif>
                           <span>
                              {{ $result->title }}
                           </span>
                           <span class="result-type">
                              in {{ $result->type }}
                           </span>
                        </a>
                     </li>
                  @endforeach
                  <li>
                     <button class="btn m-1">Show All</button>
                  </li>
               </ul>
            @else
               <div class="no-result">
                  <p>
                     No Results Found
                  </p>
               </div>
            @endif
         </div>
      @endif
   </form>
</div>
