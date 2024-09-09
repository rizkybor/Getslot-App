<?php

namespace App\Services;

use App\Models\BookingTransaction;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\TicketRepositoryInterface;
use Illuminate\Support\Facades\DB;

class BookingService
{

    protected $ticketRepository;
    protected $bookingRepository;

    public function __construct(TicketRepositoryInterface $ticketRepository, BookingRepositoryInterface $bookingRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->bookingRepository = $bookingRepository;
    }

    public function getBookingDetails($validated)
    {
        return $this->bookingRepository->findByTrxIdAndPhoneNumber($validated['booking_trx_id'], $validated['phone_number']);
    }

    public function calculateTotals($ticketId, $totalParticipant)
    {
        $ppn = 0.11;
        $price = $this->ticketRepository->getPrice($ticketId);

        $subTotal = $totalParticipant * $price;
        $ppnTotal = $subTotal * $ppn;
        $totalAmount = $subTotal + $ppnTotal;

        return [
            'sub_total' => $subTotal,
            'ppn_total' => $ppnTotal,
            'total_amount' => $totalAmount
        ];
    }

    public function storeBookingInSession($ticket, $validateData, $totals)
    {
        session()->put('booking', [
            'ticket_id' => $ticket->id,
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'phone_number' => $validateData['phone_number'],
            'started_at' => $validateData['started_at'],
            'total_participant' => $validateData['total_participant'],
            'sub_total' => $totals['sub_total'],
            'ppn_total' => $totals['ppn_total'],
            'total_amount' => $totals['total_amount']
        ]);
    }

    public function payment()
    {
        $booking = session()->get('booking');
        $ticket = $this->ticketRepository->find($booking['ticket_id']);

        return compact('booking', 'ticket');
    }

    public function paymentStore(array $validated)
    {
        $booking = session('booking');
        $bookingTransactionId = null;
        

        DB::transaction(function() use ($validated, &$bookingTransactionId, $booking){
            if(isset($validated['proof'])){
                $proofPath = $validated['proof']->store('proofs', 'public');
                $validated['proof'] = $proofPath;
            }
            
            $validated['name'] = $booking['name'];
            $validated['email'] = $booking['email'];
            $validated['phone_number'] = $booking['phone_number'];

            $validated['total_participant'] = $booking['total_participant'];
            $validated['started_at'] = $booking['started_at'];
            $validated['total_amount'] = $booking['total_amount'];

            $validated['ticket_id'] = $booking['ticket_id'];
            $validated['is_paid'] = false;
            
            $validated['booking_trx_id'] = BookingTransaction::generateUniqueTrxId();

            $newBooking = $this->bookingRepository->createBooking($validated);
            $bookingTransactionId = $newBooking->id;
        });

        return $bookingTransactionId;
    }
}
