<div class="pagination align-left">
    <div class="pagination__numbers">
        @if($paginator->hasPages())
            @if (!$paginator->onFirstPage())
                <a wire:click.prevent="previousPage" title="Prev" href="#">
                    <i class="la la-angle-left"></i>
                </a>
            @endif
            <span>{{$paginator->currentPage()}}</span>
            @if($paginator->hasMorePages())
                <a wire:click.prevent="nextPage" title="Next" href="#">
                    <i class="la la-angle-right"></i>
                </a>
            @endif
        @endif
    </div>
</div>
