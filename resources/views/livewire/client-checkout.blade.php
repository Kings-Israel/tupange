<table class="member-place-list owner-booking table-responsive">
   <thead>
      <tr>
         <th class="table-width-200">Order ID</th>
         <th class="table-width-200">Service</th>
         <th class="table-width-150">Vendor</th>
         <th class="table-width-200">Pricing</th>
         <th>Price</th>
         <th>Description</th>
         <th></th>
      </tr>
   </thead>
   <tbody>
      <form action="{{ route('orders.pay') }}" method="POST" id="checkout-form">
         @csrf
         <input type="hidden" name="Lite_Merchant_ApplicationId" value="{91D8E818-9FC9-4080-8A40-2B50858E7881}" id="Lite_Merchant_ApplicationId">
         <input type="hidden" name="Lite_Website_Successful_Url" value="{{ route('orders.iveri.success.callback') }}">
         <input type="hidden" name="Lite_Website_Fail_Url" value="{{ route('orders.iveri.fail.callback') }}">
         <input type="hidden" name="Lite_Website_TryLater_Url" value="{{ route('orders.iveri.fail.callback') }}">
         <input type="hidden" name="Lite_Website_Error_Url" value="{{ route('orders.iveri.fail.callback') }}">
         {{-- <input type="hidden" name="Lite_Website_Successful_Url" id="Lite_Website_Successful_Url" value="https://examples.iveri.net/Lite/Result.asp" />
         <input type="hidden" name="Lite_Website_Fail_Url" id="Lite_Website_Fail_Url" value="https://examples.iveri.net/Lite/Result.asp" />
         <input type="hidden" name="Lite_Website_Error_Url" id="Lite_Website_Error_Url" value="https://examples.iveri.net/Lite/Result.asp" />
         <input type="hidden" name="Lite_Website_Trylater_Url" id="Lite_Website_Trylater_Url" value="https://examples.iveri.net/Lite/Result.asp" /> --}}

         <input type="hidden" name="Lite_ConsumerOrderID_PreFix" value="ORD">
         <input type="hidden" name="Ecom_SchemaVersion" id="Ecom_SchemaVersion" />
         <input type="hidden" name="Ecom_TransactionComplete" id="Ecom_TransactionComplete" value="false" />
         <input type="hidden" name="Lite_Authorisation" id="Lite_Authorisation" value="false" />
         <input type="hidden" name="Ecom_Payment_Card_Protocols" id="Ecom_Payment_Card_Protocols" value="iVeri" />
         <input type="hidden" name="Lite_Order_Amount" value="{{ $price * 100 }}">
         <input type="hidden" name="Ecom_BillTo_Online_Email" id="Ecom_BillTo_Online_Email" value="{{ $user->email }}">
         @foreach ($orders as $order)
            <tr>
               <input type="hidden" name="orders[{{ $order->id }}]" value="{{ $order->id }}">
               <td data-title="ID" class="table-width-200">
                  <p>{{ $order->order_id }}</p>
               </td>
               <td data-title="Service" class="table-width-200">
                  {{$order->service->service_title }}
               </td>
               <td data-title="Vendor" class="table-width-150">
                  {{ $order->service->vendor->company_name }}
               </td>
               <td data-title="Package" class="table-width-200">
                  @if ($order->status === 'Paid')
                     <span>{{ $order->payment->first()->amount }}</span>
                  @else
                     <span>{{ $order->service_pricing ? $order->service_pricing->service_pricing_title : $order->order_quotation->order_pricing_title }}</span>
                  @endif
               </td>
               <td data-title="Price">
                  <p>Ksh.<span id="service_pricing_view">{{ $order->service_pricing ? $order->service_pricing->service_pricing_price : $order->order_quotation->order_pricing_price }}</span></p>
               </td>
               <td data-title="Description">
                  <p class="cart-service-description">{{ $order->service_pricing ? $order->service_pricing->service_pricing_description : $order->order_quotation->order_pricing_agreement }}</p>
               </td>
               {{-- <td>
                  <a href="" wire:click.prevent="deleteOrder({{ $order }})"><i class="la la-trash-alt"></i></a>
               </td> --}}
            </tr>
         @endforeach
         <div class="row mb-2">
            <div class="col-lg-3 col-sm-12">
               <div id="price-view">
                  <input type="hidden" name="total_price" value="{{ $price }}">
                  <p>Total Price: Ksh.<strong id="service_pricing_view">{{ $price }}</strong></p>
               </div>
            </div>
            <div class="col-lg-3">
               <div class="field-group field-input d-none">
                  <label for="Payment Option" class="label">Please select your payment option</label>
                  <select name="payment_method" id="payment_method" class="form-control" onchange="selectedPaymentMethod()">
                     {{-- <option value="Debit Card" {{ old('payment_method') == 'Debit Card' ? 'selected' : '' }}>Debit Card</option> --}}
                     <option value="Pesapal" {{ old('payment_method') == 'Pesapal' ? 'selected' : '' }}>Debit Card</option>
                     <option value="Mpesa" {{ old('payment_method') == 'Mpesa' ? 'selected' : '' }}>Mpesa</option>
                     {{-- <option value="Paypal" {{ old('payment_method') == 'Paypal' ? 'selected' : '' }}>Paypal</option> --}}
                  </select>
               </div>
            </div>
            <div class="col-lg-6 col-sm-12">
               <div class="payment-actions">
                  <a href="{{ route('client.orders') }}" class="btn" id="cancel-pay-btn" style="background-color: red">Cancel</a>
                  <input type="submit" value="Pay" class="btn" id="pay-btn">
               </div>
            </div>
         </div>
      </form>
   </tbody>
</table>
