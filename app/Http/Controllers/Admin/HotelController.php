<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Hotel;
use App\Models\Prefecture;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    /** get methods */

    public function showSearch(): View
    {
        $var['prefectures'] = Prefecture::get();
        return view('admin.hotel.search', $var);
    }

    public function showResult(): View
    {
        $var['prefectures'] = Prefecture::get();
        return view('admin.hotel.result', $var);
    }

    public function showEdit($hotel_id): View
    {
        try {
            $validation = Validator::make([
                'hotel_id' => $hotel_id,
            ], [
                'hotel_id' => 'required|exists:hotels,hotel_id',
            ]);
            $data['route'] = route('adminHotelEditProcess', ['hotel_id' => $hotel_id]);
            if ($validation->fails()) {
                return view('admin.errors.404');
            }
            $data['hotel'] = Hotel::find($hotel_id);
            $data['prefectures'] = Prefecture::get();
            return view('admin.hotel.form', $data);
        } catch (\Exception $e) {
            return view('admin.errors.404');
        }
    }

    public function showCreate(): View
    {
        $data['route'] = route('adminHotelCreateProcess');
        $data['prefectures'] = Prefecture::get();
        return view('admin.hotel.form', $data);
    }

    /** post methods */

    public function searchResult(Request $request): View
    {
        $var = [];

        $hotelList = Hotel::getList($request->all());

        $var['hotelList'] = $hotelList;
        $var['prefectures'] = Prefecture::get();
        $var['request'] = $request->all();

        return view('admin.hotel.result', $var);
    }

    public function edit($hotel_id, Request $request): \Illuminate\Http\RedirectResponse
    {
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(), [
                'hotel_name' => 'required|string|max:255',
                'prefecture_id' => 'required|exists:prefectures,prefecture_id',
                'file_path' => 'nullable|string',
                'image_hotel' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation->errors())->withInput();
            }

            $hotel = Hotel::find($hotel_id);
            $hotel->hotel_name = $request->input('hotel_name');
            $hotel->prefecture_id = $request->input('prefecture_id');

            if ($request->hasFile('image_hotel')) {
                $file = $request->file('image_hotel');
                $folder = 'hotel';
                $file_path = Storage::disk('custom_upload')->putFileAs($folder, $file, $file->getClientOriginalName());

                if (!$file_path) {
                    return redirect()->back()->with('error', 'Failed to upload file.')->withInput();
                }
                $file_path;
            } elseif ($request->input('file_path') != null) {
                $file_path = $request->input('file_path');
            }
            $hotel->file_path = $file_path ?? null;
            $hotel->save();
            DB::commit();
            return redirect()->route('adminHotelEditPage', ['hotel_id' => $hotel_id])->with('success', 'Hotel updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function create(Request $request): \Illuminate\Http\RedirectResponse
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'hotel_name' => 'required|string|max:255',
                'prefecture_id' => 'required|exists:prefectures,prefecture_id',
                'image_hotel' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if ($request->hasFile('image_hotel')) {
                $file = $request->file('image_hotel');
                $folder = 'hotel';
                $file_path = Storage::disk('custom_upload')->putFileAs($folder, $file, $file->getClientOriginalName());

                if (!$file_path) {
                    return redirect()->back()->with('error', 'Failed to upload file.')->withInput();
                }
            }

            $hotel = new Hotel();
            $hotel->hotel_name = $request->input('hotel_name');
            $hotel->prefecture_id = $request->input('prefecture_id');
            $hotel->file_path = $file_path ?? null;
            $hotel->save();

            DB::commit();
            return redirect()->route('adminHotelEditPage', ['hotel_id' => $hotel->hotel_id])->with('success', 'Hotel created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete(Request $request): \Illuminate\Http\RedirectResponse
    {
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(), [
                'hotel_id' => 'required|exists:hotels,hotel_id',
            ]);
            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation->errors());
            }
            $hotel = Hotel::find($request->input('hotel_id'));
            $hotel->delete();
            DB::commit();
            return redirect()->route('adminHotelSearchPage')->with('success', 'Hotel deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
