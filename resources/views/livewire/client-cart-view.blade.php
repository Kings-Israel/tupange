<div>
    <table class="member-place-list owner-booking table-responsive">
        <thead>
            <tr>
               {{-- <th>#</th> --}}
                <th>Service</th>
                <th>Added To Cart</th>
                <th>Select Quote</th>
                <td class="table-width-250">Custom Message</td>
                <th>Actions</th>
            </tr>
        </thead>
         <form action="{{ route('client.checkout') }}" method="post" class="submit-cart-form">
            @csrf
            <tbody>
                @foreach ($items as $item)
                    <tr>
                       <input hidden type="checkbox" name="service[{{ $item->service->id }}]" id="{{ $item->id }}" value="{{ $item->service->id }}" checked>
                       <td hidden>
                           {{-- <input hidden type="checkbox" name="service[{{ $item->service->id }}]" id="{{ $item->id }}" value="{{ $item->service->id }}" {{ (is_array(old('service')) && in_array($item->service->id, old('service'))) ? ' checked' : '' }}>--}}
                        </td>
                        <td data-title="Service">
                            <a href="{{ route('client.service.one', $item->service) }}">
                                <b>{{ $item->service->service_title }}</b>
                            </a>
                            <p class="cart-service-description">{{ $item->service->service_description }}</p>
                        </td>
                        <td data-title="Date added">
                           {{ $item->created_at->diffForHumans() }}
                        </td>
                        <td data-title="Pricing">
                           <select name="service_pricing[{{ $item->service->id }}]" id="" class="form-control">
                              <option value="">Get Quote</option>
                              @if ($item->service->service_pricing->count())
                                 @foreach ($item->service->service_pricing as $pricing)
                                    <option value="{{ $pricing->id }}" @if ($item->service_pricing_id === $pricing->id) selected @endif>{{ $pricing->service_pricing_title }} (Ksh. <p id="service_pricing_view">{{ $pricing->service_pricing_price }}</p>)</option>
                                 @endforeach
                              @endif
                           </select>
                        </td>
                        <td class="table-width-250" data-title="Message">
                           <textarea name="order_message[{{ $item->service->id }}]" class="form-control order_message" id="order_message">{{ (is_array(old('order_message')) && array_key_exists($item->service->id, old('order_message'))) ? old('order_message['.$item->service->id.']') : '' }}</textarea>
                        </td>
                        <td>
                           <span class="delete" title="Delete" wire:click="removeFromCart({{ $item }})">
                              <button class="btn">Remove</button>
                           </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row">
            <div class="col-lg-6">
               {{ $items->links() }}
            </div>
            <div class="col-lg-6">
               <div class="row">
                  <div class="col-lg-7">
                     <div class="field-group field-input">
                        @php($order_event = session()->get('event_id'))
                        <label for="">Link to Event</label>
                        <select id="" class="form-control" name="event_id">
                            <option value="">Select Event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }} {{ $order_event && $order_event != NULL && $order_event == $event->id ? 'selected' : '' }}>{{ $event->event_name }} ({{ $event->status }})</option>
                            @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-lg-5">
                     <input type="submit" value="Send" class="btn submit-cart-btn" id="submit-cart" style="float: right">
                  </div>
               </div>
            </div>
        </div>
    </form>
</div>
