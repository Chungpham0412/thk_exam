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

    public function showEdit(): View
    {
        try {
            $id = request()->input('hotel_id');
            $validation = Validator::make(request()->all(), [
                'hotel_id' => 'required|exists:hotels,hotel_id',
            ]);
            if ($validation->fails()) {
                return view('admin.errors.404');
            }
            $data['hotel'] = Hotel::find($id);
            $data['prefectures'] = Prefecture::get();
            return view('admin.hotel.edit', $data);
        } catch (\Exception $e) {
            return view('admin.errors.404');
        }
    }

    public function showCreate(): View
    {
        $data['prefectures'] = Prefecture::get();
        return view('admin.hotel.create', $data);
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

    public function edit(Request $request): \Illuminate\Http\RedirectResponse
    {
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(), [
                'hotel_id' => 'required|exists:hotels,hotel_id',
                'hotel_name' => 'required|string|max:255',
                'prefecture_id' => 'required|exists:prefectures,prefecture_id',
                'file_path' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation->errors())->withInput();
            }

            $hotel = Hotel::find($request->input('hotel_id'));
            $hotel->hotel_name = $request->input('hotel_name');
            $hotel->prefecture_id = $request->input('prefecture_id');

            if ($request->hasFile('file_path')) {
                $file = $request->file('file_path');
                $folder = 'hotel';
                $file_path = Storage::disk('custom_upload')->putFileAs($folder, $file, $file->getClientOriginalName());

                if (!$file_path) {
                    return redirect()->back()->with('error', 'Failed to upload file.')->withInput();
                }

                $hotel->file_path = $file_path;
            }

            $hotel->save();
            DB::commit();
            return redirect()->route('adminHotelEditPage')->with('success', 'Hotel updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function create(Request $request): \Illuminate\Http\RedirectResponse
    {
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(), [
                'hotel_name' => 'required|string|max:255',
                'prefecture_id' => 'required|exists:prefectures,prefecture_id',
                'file_path' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation->errors())->withInput();
            }

            if ($request->hasFile('file_path')) {
                $file = $request->file('file_path');
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
            return redirect()->route('adminHotelCreatePage')->with('success', 'Hotel created successfully.');
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
