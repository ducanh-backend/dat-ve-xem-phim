<?php

namespace App\Controllers;

class BookingController extends Controller {
    public function seat() {
        return $this->view('booking.seat');
    }

    public function checkout() {
        return $this->view('booking.checkout');
    }

    public function success() {
        return $this->view('booking.success');
    }

    public function history() {
        return $this->view('booking.history');
    }
}
