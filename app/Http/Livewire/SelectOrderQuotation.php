<?php

namespace App\Http\Livewire;

use App\Jobs\SendSms;
use App\Models\Order;
use App\Models\OrderQuotations;
use App\Notifications\VendorNotification;
use Livewire\Component;

class SelectOrderQuotation extends Component
{
    public $order;

    public function acceptQuotation(OrderQuotations $orderQuotation)
    {
        // Add order_quotation_id to orders table
        $order = Order::find($orderQuotation->order_id);
        $order->order_quotation_id = $orderQuotation->id;
        $order->save();

        $order->service->vendor->notify(new VendorNotification($order, 'Accept Quotation'));

        SendSms::dispatchAfterResponse($order->service->vendor->company_phone_number, 'A client accepted a custom quote for an order for the service '.$order->service->service_title);

        // Change all the other order_quotations to declined
        $orderQuotations = OrderQuotations::where('order_id', $orderQuotation->order_id)->get();
        foreach ($orderQuotations as $quotation) {
            $quotation->status = "Declined";
            $quotation->save();
        }

        $orderQuotation->status = 'Accepted';
        $orderQuotation->save();

        session()->flash('success', 'Quote added as price');
    }

    public function declineQuotation(OrderQuotations $orderQuotation)
    {
        $orderQuotation->status = 'Declined';
        $orderQuotation->save();

        $order = Order::find($orderQuotation->order_id);
        $order->service->vendor->notify(new VendorNotification($order, 'Decline Quotation'));
    }

    public function render()
    {
        return view('livewire.select-order-quotation', [
            'order_quotations' => OrderQuotations::where('order_id', $this->order->id)->where('status', '!=', 'Declined')->get(),
        ]);
    }
}
