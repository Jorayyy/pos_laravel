<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function show(Sale $sale)
    {
        $sale->load(['items.product', 'items.service', 'user', 'owner', 'pet']);

        return view('admin.receipts.show', compact('sale'));
    }

    public function pdf(Sale $sale)
    {
        $sale->load(['items.product', 'items.service', 'user', 'owner', 'pet']);

        $pdf = Pdf::loadView('admin.receipts.pdf', compact('sale'))
            ->setPaper([0, 0, 226.77, 600], 'portrait');

        return $pdf->stream("receipt-{$sale->invoice_number}.pdf");
    }
}
