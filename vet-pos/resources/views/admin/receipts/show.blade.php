<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt {{ $sale->invoice_number }}</title>
    @vite(['resources/css/app.css'])
    @php use App\Models\Setting; @endphp
    <style>
        @media print { body { margin: 0; } .no-print { display: none !important; } }
        .receipt { max-width: 320px; margin: 0 auto; font-family: monospace; font-size: 12px; line-height: 1.4; }
    </style>
</head>
<body class="bg-gray-100 p-4">
    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Print Receipt</button>
        <a href="{{ route('admin.sales.show', $sale) }}" class="ml-2 text-blue-600 text-sm hover:underline">Back to Sale</a>
    </div>
    <div class="receipt bg-white p-4 rounded-lg border border-gray-200">
        <div class="text-center mb-3">
            <div class="font-bold text-base">{{ Setting::get('business_name', 'VetPOS Clinic') }}</div>
            <div>{{ Setting::get('business_address', '') }}</div>
            <div>{{ Setting::get('business_phone', '') }}</div>
        </div>
        <hr class="border-dashed border-gray-300 my-2">
        <div class="text-center font-bold mb-2">RECEIPT</div>
        <div class="text-xs space-y-0.5">
            <div class="flex justify-between"><span>Invoice:</span><span class="font-mono">{{ $sale->invoice_number }}</span></div>
            <div class="flex justify-between"><span>Date:</span><span>{{ $sale->created_at->format('M d, Y g:i A') }}</span></div>
            <div class="flex justify-between"><span>Cashier:</span><span>{{ $sale->user->name }}</span></div>
            @if($sale->owner)
            <div class="flex justify-between"><span>Customer:</span><span>{{ $sale->owner->full_name }}</span></div>
            @endif
            @if($sale->pet)
            <div class="flex justify-between"><span>Pet:</span><span>{{ $sale->pet->name }} ({{ $sale->pet->species }})</span></div>
            @endif
        </div>
        <hr class="border-dashed border-gray-300 my-2">
        <table class="w-full text-xs">
            <thead><tr><th class="text-left">Item</th><th class="text-center">Qty</th><th class="text-right">Price</th><th class="text-right">Total</th></tr></thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td class="text-left">{{ $item->name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">₱{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">₱{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr class="border-dashed border-gray-300 my-2">
        <div class="text-xs space-y-0.5">
            <div class="flex justify-between"><span>Subtotal:</span><span>₱{{ number_format($sale->subtotal, 2) }}</span></div>
            @if($sale->discount > 0)
            <div class="flex justify-between"><span>Discount:</span><span>-₱{{ number_format($sale->discount, 2) }}</span></div>
            @endif
            @if($sale->tax > 0)
            <div class="flex justify-between"><span>Tax:</span><span>₱{{ number_format($sale->tax, 2) }}</span></div>
            @endif
            <div class="flex justify-between font-bold text-sm"><span>TOTAL:</span><span>₱{{ number_format($sale->total, 2) }}</span></div>
            <div class="flex justify-between"><span>Payment:</span><span class="capitalize">{{ str_replace('_', ' ', $sale->payment_method) }}</span></div>
            <div class="flex justify-between"><span>Cash Received:</span><span>₱{{ number_format($sale->amount_paid, 2) }}</span></div>
            <div class="flex justify-between"><span>Change:</span><span>₱{{ number_format($sale->change_amount, 2) }}</span></div>
        </div>
        <hr class="border-dashed border-gray-300 my-2">
        <div class="text-center text-xs text-gray-500 mt-2">
            {{ Setting::get('receipt_footer', 'Thank you!') }}
        </div>
    </div>
</body>
</html>
