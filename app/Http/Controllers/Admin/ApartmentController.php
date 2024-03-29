<?php

namespace App\Http\Controllers\Admin;

use App\Amenity;
use App\Apartment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apartments = Apartment::where('user_id', Auth::id())->get();
        $amenities = Amenity::all();

        return view('admin.apartments.index', compact('apartments', 'amenities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $amenities = Amenity::all();
        return view('admin.apartments.create', compact('amenities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|min:2',
            'description' => 'required|min:10',
            'rooms' => 'required|numeric|min:1|max:30',
            'beds' => 'required|numeric|min:1|max:40',
            'bathrooms' => 'required|numeric|min:1|max:20',
            'square_meters' => 'required|numeric|min:10|max:1000',
            'image' => 'required|image|max:2048',
            'availability' => 'required|boolean',
            'address' => 'required|min:2',
            'amenities' => 'required'
        ]);


        $data = $request->all();

        $slug = Str::slug($data['title']);
        $counter = 1;
        while (Apartment::where('slug', $slug)->first()) {
            $slug = Str::slug($data['title']) . '-' . $counter;
            $counter++;
        }
        $data['slug'] = $slug;

        if (isset($data['image'])) {
            $image_path = Storage::put('images', $data['image']);
            $data['image'] = $image_path;
        }

        $apartment = new Apartment();
        $apartment->user_id = Auth::id();
        $apartment->fill($data);
        $apartment->save();

        if (isset($data['amenities']))
            $apartment->amenities()->sync($data['amenities']);

        return redirect()->route('admin.apartments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $apartment = Apartment::where('slug', '=', $slug)->with(['amenities'])->first();

        if ($apartment->user_id !== Auth::id()) {
            abort(404);
        };

        $now = Carbon::now();
        $apartmentDateTime = Carbon::create($apartment->created_at);
        $diffInDays = $now->diffInDays($apartmentDateTime);

        $amenities = Amenity::all();

        return view('admin.apartments.show', compact('apartment', 'amenities', 'diffInDays'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $apartment = Apartment::where('slug', '=', $slug)->with(['amenities'])->first();

        if ($apartment->user_id !== Auth::id()) {
            abort(404);
        };
        $amenities = Amenity::all();
        return view('admin.apartments.edit', compact('apartment', 'amenities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apartment $apartment)
    {
        $request->validate([
            'title' => 'required|min:2',
            'description' => 'required|min:10',
            'rooms' => 'required|numeric|min:1|max:30',
            'beds' => 'required|numeric|min:1|max:40',
            'bathrooms' => 'required|numeric|min:1|max:20',
            'square_meters' => 'required|numeric|min:10|max:1000',
            'image' => 'image|max:2048',
            'availability' => 'required|boolean',
            'address' => 'required|min:2',
            'amenities' => 'required'
        ]);

        $userId = Auth::user()->id;
        $data = $request->all();

        $slug = Str::slug($data['title']);

        $counter = 1;

        if ($apartment->slug != $slug) {
            while (Apartment::where('slug', $slug)->first()) {
                $slug = Str::slug($data['title']) . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }

        if (isset($data['image'])) {
            Storage::delete($apartment->image);
            $image_path = Storage::put('images', $data['image']);
            $data['image'] = $image_path;
        } else {
            $data['image'] = $apartment->image;
        }

        $apartment->update($data);
        $apartment->save();

        if (isset($data['amenities'])) {
            $apartment->amenities()->sync($data['amenities']);
        }

        return redirect()->route('admin.apartments.index', compact('apartment'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apartment $apartment)
    {
        Storage::delete($apartment->image);
        if ($apartment->user_id !== Auth::id()) {
            abort(404);
        };
        $apartment->delete();
        return redirect()->route('admin.apartments.index');
    }
}
