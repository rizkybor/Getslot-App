<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\StoreCheckBookingRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Models\BookingTransaction;
use App\Models\Ticket;
use App\Models\Type;

use App\Services\BookingService;

class BookingController extends Controller
{
    //
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function booking(Ticket $ticket)
    {
        $typeDetailsByTicket = $ticket->type;

        return view('front.booking', compact('ticket', 'typeDetailsByTicket'));
    }

    public function getInitials($typeId)
    {
        $initials = Type::find($typeId)->initial()->get();

        return response()->json($initials);
    }

    public function bookingStore(Ticket $ticket, StoreBookingRequest $request)
    {
        $validateData = $request->validated();
        
        $totals = $this->bookingService->calculateTotals($validateData['participants']);

        $this->bookingService->storeBookingInSession($ticket, $validateData, $totals);

        $booking = session()->get('booking');

        return redirect()->route('front.payment');
    }


    public function payment()
    {
        $data = $this->bookingService->payment();
        return view('front.payment', $data);
    }

    public function paymentStore(StorePaymentRequest $request)
    {
        $validated = $request->validated();

        $bookingTransactionId = $this->bookingService->paymentStore($validated);

        if ($bookingTransactionId) {
            return redirect()->route('front.booking_finished', $bookingTransactionId);
        }

        return redirect()->route('front.index')->withErrors(['error' => 'Payment failed, Please try again']);
    }

    public function bookingFinished(BookingTransaction $bookingTransaction)
    {
        return view('front.booking_finished', compact('bookingTransaction'));
    }

    public function checkBooking()
    {
        return view('front.check_booking');
    }

    public function checkBookingDetails(StoreCheckBookingRequest $request)
    {
        $validated = $request->validated();
        $bookingDetails = $this->bookingService->getBookingDetails($validated);

        if ($bookingDetails) {
            return view('front.check_booking_details', compact('bookingDetails'));
        }

        return redirect()->route('front.check_booking')->withErrors(['error' => 'Transaction not found']);
    }
}
