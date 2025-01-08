<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bookings;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        try {
            $data['bookings'] = Bookings::get();
            return view('admin.bookings.list', $data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function create(): View
    {
        try {
            $data['hotels'] = Hotel::get();
            $data['route'] = route('admin.bookings.store');
            $data['method'] = 'POST';
            return view('admin.bookings.form', $data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $validation = Validator::make($request->all(), [
                'hotel_id' => 'required|exists:hotels,hotel_id',
                'customer_name' => 'required|string|max:255',
                'customer_contact' => 'required|string|max:255',
                'chekin_time' => 'required|date',
                'checkout_time' => 'required|date',
            ]);
            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation->errors())->withInput();
            }
            $booking = Bookings::create($validation->validated());
            return redirect()->route('admin.bookings.edit', ['booking_id' => $booking->booking_id]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function edit($booking_id): View
    {
        try {
            $data['hotels'] = Hotel::get();
            $data['booking'] = Bookings::find($booking_id);
            $data['route'] = route('admin.bookings.update', ['booking_id' => $booking_id]);
            $data['method'] = 'PUT';
            return view('admin.bookings.form', $data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update(Request $request, $booking_id): \Illuminate\Http\RedirectResponse
    {
        try {
            $validation = Validator::make($request->all(), [
                'hotel_id' => 'required|exists:hotels,hotel_id',
                'customer_name' => 'required|string|max:255',
                'customer_contact' => 'required|string|max:255',
                'chekin_time' => 'required|date',
                'checkout_time' => 'required|date',
            ]);
            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation->errors())->withInput();
            }
            Bookings::find($booking_id)->update($validation->validated());
            return redirect()->route('admin.bookings.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function destroy($booking_id): \Illuminate\Http\RedirectResponse
    {
        try {
            $validation = Validator::make([
                'booking_id' => $booking_id,
            ], [
                'booking_id' => 'required|exists:bookings,booking_id',
            ]);
            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation->errors());
            }
            Bookings::find($booking_id)->delete();
            return redirect()->route('admin.bookings.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function searchResult(Request $request): View
    {
        $data['bookings'] = Bookings::getList($request->all());
        return view('admin.bookings.result', $data);
    }
}
