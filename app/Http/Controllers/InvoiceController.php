<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\ServiceBooking;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $hotelId = auth()->user()->hotel_id;
        
        $query = Invoice::where('hotel_id', $hotelId)
            ->with(['guest', 'reservation']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $query->whereHas('guest', function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('invoices.index', compact('invoices'));
    }

    public function create(Request $request): View
    {
        $hotelId = auth()->user()->hotel_id;
        $reservation = null;
        
        if ($request->filled('reservation_id')) {
            $reservation = Reservation::where('hotel_id', $hotelId)
                ->with(['guest', 'roomType'])
                ->findOrFail($request->reservation_id);
        }
        
        return view('invoices.create', compact('reservation'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'due_date' => 'required|date',
            'line_items' => 'required|array',
            'line_items.*.description' => 'required|string',
            'line_items.*.quantity' => 'required|numeric|min:1',
            'line_items.*.unit_price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $reservation = Reservation::findOrFail($validated['reservation_id']);
        
        // Calculer les totaux
        $subtotal = 0;
        foreach ($validated['line_items'] as &$item) {
            $item['total'] = $item['quantity'] * $item['unit_price'];
            $subtotal += $item['total'];
        }
        
        $discountAmount = $validated['discount_amount'] ?? 0;
        $taxAmount = ($subtotal - $discountAmount) * 0.1925; // TVA Cameroun
        $totalAmount = $subtotal - $discountAmount + $taxAmount;

        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'hotel_id' => auth()->user()->hotel_id,
            'reservation_id' => $reservation->id,
            'guest_id' => $reservation->guest_id,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'due_date' => $validated['due_date'],
            'line_items' => $validated['line_items'],
            'notes' => $validated['notes'],
            'status' => 'draft',
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture créée avec succès.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['guest', 'reservation', 'payments']);
        
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Seules les factures en brouillon peuvent être modifiées.');
        }
        
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        if ($invoice->status !== 'draft') {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Seules les factures en brouillon peuvent être modifiées.');
        }

        $validated = $request->validate([
            'due_date' => 'required|date',
            'line_items' => 'required|array',
            'line_items.*.description' => 'required|string',
            'line_items.*.quantity' => 'required|numeric|min:1',
            'line_items.*.unit_price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Recalculer les totaux
        $subtotal = 0;
        foreach ($validated['line_items'] as &$item) {
            $item['total'] = $item['quantity'] * $item['unit_price'];
            $subtotal += $item['total'];
        }
        
        $discountAmount = $validated['discount_amount'] ?? 0;
        $taxAmount = ($subtotal - $discountAmount) * 0.1925;
        $totalAmount = $subtotal - $discountAmount + $taxAmount;

        $invoice->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'due_date' => $validated['due_date'],
            'line_items' => $validated['line_items'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture mise à jour avec succès.');
    }

    public function send(Invoice $invoice): RedirectResponse
    {
        $invoice->update(['status' => 'sent']);
        
        return back()->with('success', 'Facture envoyée au client.');
    }

    public function markAsPaid(Invoice $invoice): RedirectResponse
    {
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
        
        return back()->with('success', 'Facture marquée comme payée.');
    }

    public function downloadPdf(Invoice $invoice)
    {
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        
        return $pdf->download('facture-' . $invoice->invoice_number . '.pdf');
    }
}
