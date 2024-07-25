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

     public function show(Item $item)
    {
         return view('show', compact('item'));
    }
    public function index()
    {
        $items = Item::with('uploadImage')->get();
        return view('index', compact('items'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create');
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
        $itemId = $item->id;

        if($request->hasFile('images'))
        {
            $uploadPath = 'uploads/items/';
            $i = 1;
            foreach($request->file('images') as $image)
            {
                $extension = $image->getClientOriginalExtension();
                $filename = time() . $i++ . '.' . $extension;
                $image->move($uploadPath, $filename);
                $finalImagePath = $uploadPath . $filename;

                $newimage = new Image();
                $newimage->url = $finalImagePath;
                $newimage->item_id = $itemId;
                $newimage->save();
            }
        }

        return redirect()->route('item.create')
                         ->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        return view('edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'nullable',
            'description' => 'nullable',
            'price' => 'nullable',
        ]);

        $item->update($request->all());

        return redirect()->route('item.index')
                         ->with('success', 'Successfully edited!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('item.index')
                         ->with('success', 'Product deleted successfully.');
    }
}
