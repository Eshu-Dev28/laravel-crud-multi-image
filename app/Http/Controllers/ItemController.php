<?php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Image;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with('images')->get();
        return response()->json(['items' => $items]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return response()->json(['item' => $item->load('images')]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $item = Item::create($request->all());

        if ($request->hasFile('images')) {
            $uploadPath = 'uploads/items/';
            $i = 1;
            foreach ($request->file('images') as $image) {
                $extension = $image->getClientOriginalExtension();
                $filename = time() . $i++ . '.' . $extension;
                $image->move(public_path($uploadPath), $filename);
                $finalImagePath = $uploadPath . $filename;

                Image::create([
                    'url' => $finalImagePath,
                    'item_id' => $item->id,
                ]);
            }
        }

        return response()->json(['success' => 'Product created successfully.', 'item' => $item->load('images')]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $item->update($request->only('name', 'description', 'price'));

        if ($request->hasFile('images')) {
            // Delete old images
            foreach ($item->images as $image) {
                if (file_exists(public_path($image->url))) {
                    unlink(public_path($image->url));
                }
                $image->delete();
            }

            $uploadPath = 'uploads/items/';
            $i = 1;
            foreach ($request->file('images') as $imageFile) {
                $extension = $imageFile->getClientOriginalExtension();
                $filename = time() . $i++ . '.' . $extension;
                $imageFile->move(public_path($uploadPath), $filename);
                $finalImagePath = $uploadPath . $filename;

                Image::create([
                    'url' => $finalImagePath,
                    'item_id' => $item->id,
                ]);
            }
        }

        return response()->json(['success' => 'Item updated successfully!', 'item' => $item->load('images')]);
    }

    /**
     * Upload images for the specified item.
     */
    public function uploadImages(Request $request, Item $item)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        if ($request->hasFile('images')) {
            $uploadPath = public_path('uploads/items');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
    
            $i = 1;
            foreach ($request->file('images') as $imageFile) {
                $extension = $imageFile->getClientOriginalExtension();
                $filename = time() . $i++ . '.' . $extension;
                $imageFile->move($uploadPath, $filename);
                $finalImagePath = 'uploads/items/' . $filename;
    
                $image = Image::create([
                    'url' => $finalImagePath,
                    'item_id' => $item->id,
                ]);
    
                if (!$image) {
                    \Log::error("Failed to save image in the database: $finalImagePath for item ID: {$item->id}");
                    return response()->json(['error' => 'Failed to save image in the database.'], 500);
                }
            }
        } else {
            \Log::error("No images found in the request.");
        }
    
        return response()->json(['success' => 'Images uploaded successfully.', 'item' => $item->load('images')]);
    }
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        // Delete images associated with the item
        foreach ($item->images as $image) {
            if (file_exists(public_path($image->url))) {
                unlink(public_path($image->url));
            }
            $image->delete();
        }

        // Delete the item
        $item->delete();

        return response()->json(['success' => 'Product deleted successfully.']);
    }
}
