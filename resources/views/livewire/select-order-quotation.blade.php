<div class="row">
    @if (! $order_quotations->count())
        <h2>No Custom Quotations added</h2>
    @endif
    @foreach ($order_quotations as $order_quotation)
        <div class="col-lg-4 col-md-6">
            <div class="place-item layout-02 place-hover">
                <div class="place-inner">
                    <div class="entry-detail">
                        <div class="place-type list-item">
                            <span id="service_pricing_view">Ksh. {{ $order_quotation->order_pricing_price }}</span>
                        </div>
                        <h3 class="place-title" style="margin-bottom: -5px">{{ $order_quotation->order_pricing_title }}</h3>
                       @if($order_quotation->order_pricing_agreement != NULL && $order_quotation->order_pricing_agreement !== '')
                           <p class="order_quotation_agreement">{{ $order_quotation->order_pricing_agreement }}</p>
                       @endif
                        <div class="entry-bottom">
                            <div class="place-price">
                                @if ($order_quotation->status === 'Pending')
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h4 style="color: green; cursor:pointer" wire:click="acceptQuotation({{ $order_quotation }})">Accept</h4>
                                        </div>
                                        <div class="col-lg-6">
                                            <h4 style="color: red; cursor:pointer;" wire:click="declineQuotation({{ $order_quotation }})">Decline</h4>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
