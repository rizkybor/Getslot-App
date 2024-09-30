<x-mail::message>
Hi {{ $booking->name }}, pesanan anda dengan kode booking {{ $booking->booking_trx_id }} telah berhasil!
Silakan datang saat Technical Meeting untuk mengambil Racepack dengan menunjukkan kode booking anda.

<x-mail::button :url="route('front.check_booking')">
Contact CS
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
