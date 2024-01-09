<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Storage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Database;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ConcessionController extends Controller
{
    protected $database;
    protected $storage;

    public function __construct(Database $database, Storage $storage)
    {
        $this->database = $database;
        $this->storage = $storage;
    }

    public function index(Request $request)
    {
        $concessionsSnapshot = $this->database->getReference('tconcessions')->getSnapshot();
        $concessionsData = $concessionsSnapshot->getValue();
        if (is_array($concessionsData)) {
            foreach ($concessionsData as $concessionKey => $concessionData) {
                $concessionData['image'] = $this->storage->getBucket(env('FIREBASE_STORAGE_BUCKET'))->object( $concessionData['image'] )->signedUrl(new \DateTime('tomorrow'));
                $concessions[] = array_merge(['id' => $concessionKey], $concessionData);
            }
        } else {
            $concessions = [];
        }

        $search = $request->input('search');
        if ($search) {
            $concessions = array_filter($concessions, function ($concession) use ($search) {
                return strpos(strtolower($concession['name']), strtolower($search)) !== false;
            });
        }

        $filter = $request->input('filter', 'newest');
        if ($filter === 'oldest') {
            $concessions = array_reverse($concessions);
        }
    
        $perPage = 5;
        $currentPage = Paginator::resolveCurrentPage('page');

        $currentItems = array_slice($concessions, ($currentPage - 1) * $perPage, $perPage);

        $concessions = new LengthAwarePaginator(
            $currentItems,
            count($concessions),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
    

        return view('admin/concession/master', compact('concessions'));
    }

    public function viewadd() {
        return view('admin/concession/add');
    }

    public function viewedit($id) 
    {
        $concessionSnapshot = $this->database->getReference("tconcessions/$id")->getSnapshot();
        
        if (!$concessionSnapshot->exists()) {
            abort(404); 
        }

        $concession = array_merge(['id' => $id], $concessionSnapshot->getValue());
        $concession['image'] = $this->storage->getBucket(env('FIREBASE_STORAGE_BUCKET'))->object( $concessionSnapshot->getValue()['image'] )->signedUrl(new \DateTime('tomorrow'));
    
        return view('admin/concession/edit', compact('concession'));
    }

    public function store(Request $request)
    {

        $rules = [
            'image' => 'required|image',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
        ];
        
        $messages = [
            'image.required' => 'The image is required.',
            'image.image' => 'The file must be an image.',
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than :max characters.',
            'description.required' => 'The description field is required.',
            'description.string' => 'The description must be a string.',
            'price.required' => 'The price field is required.',
            'price.integer' => 'The price must be an integer.',
            'price.min' => 'The price must be at least :min.',
            'stock.required' => 'The stock field is required.',
            'stock.integer' => 'The stock must be an integer.',
            'stock.min' => 'The stock must be at least :min.',
        ];
        
        $request->validate($rules, $messages);

        $file = request()->file('image');
        $filename = 'images/' . $file->getClientOriginalName(); 
        
        $this->storage->getBucket(env('FIREBASE_STORAGE_BUCKET'))->upload(
            fopen($file->getPathname(), 'r'),
            ['name' => $filename]
        );

        $data = $request->only(['name', 'description', 'category', 'stock', 'price']);

        $data['image'] = $filename;
        $data['created_at'] = ['.sv' => 'timestamp'];
        $data['updated_at'] = ['.sv' => 'timestamp'];

        $concessionsRef = $this->database->getReference('tconcessions')->push();
        $concessionsRef->set($data);

        return redirect()->route('toMasterConcession')->with('success', 'Concession added successfully');
    }

    public function edit($id, Request $request)
    {

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
        ];
        
        $messages = [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than :max characters.',
            'description.required' => 'The description field is required.',
            'description.string' => 'The description must be a string.',
            'price.required' => 'The price field is required.',
            'price.integer' => 'The price must be an integer.',
            'price.min' => 'The price must be at least :min.',
            'stock.required' => 'The stock field is required.',
            'stock.integer' => 'The stock must be an integer.',
            'stock.min' => 'The stock must be at least :min.',
        ];
        
        $request->validate($rules, $messages);
            
        $data = $request->only(['name', 'description', 'category', 'stock', 'price']);

        if ($request->file('image'))
        {
            $file = request()->file('image');
            $filename = 'images/' . $file->getClientOriginalName(); 
            
            $this->storage->getBucket(env('FIREBASE_STORAGE_BUCKET'))->upload(
                fopen($file->getPathname(), 'r'),
                ['name' => $filename]
            );

            $data = $request->only(['name', 'description', 'category', 'stock', 'price']);

            $data['image'] = $filename;
        }

        $data['updated_at'] = ['.sv' => 'timestamp'];

        $concessionsRef = $this->database->getReference("tconcessions/$id");
        $concessionsRef->update($data);

        return redirect()->route('toMasterConcession')->with('success', 'Concession edited successfully');
    }

    public function destroy($id)
    {
        $concessionsRef = $this->database->getReference('tconcessions')->getChild($id);

        if ($concessionsRef->getSnapshot()->exists()) {
            // $concessionsRef->remove();
            $concessionsRef->update([
                'is_deleted' => true,
                'deleted_at' => time(),
            ]);

            return redirect()->route('toMasterConcession')->with('success', 'Concession deleted successfully');
        }

        return redirect()->route('toMasterConcession')->with('error', 'Concession not found');
    }

    public function toCashierConcessions(Request $request) {
        $concessionsSnapshot = $this->database->getReference('tconcessions')->getSnapshot();
        $concessionsData = $concessionsSnapshot->getValue();
        if (is_array($concessionsData)) {
            foreach ($concessionsData as $concessionKey => $concessionData) {
                $concessionData['image'] = $this->storage->getBucket(env('FIREBASE_STORAGE_BUCKET'))->object( $concessionData['image'] )->signedUrl(new \DateTime('tomorrow'));
                $concessions[] = array_merge(['id' => $concessionKey], $concessionData);
            }
        } else {
            $concessions = [];
        }

        $search = $request->input('search');
        if ($search) {
            $concessions = array_filter($concessions, function ($concession) use ($search) {
                return strpos(strtolower($concession['name']), strtolower($search)) !== false;
            });
        }

        $filter = $request->input('filter', 'newest');
        if ($filter === 'oldest') {
            $concessions = array_reverse($concessions);
        }
    
        $perPage = 5;
        $currentPage = Paginator::resolveCurrentPage('page');

        $currentItems = array_slice($concessions, ($currentPage - 1) * $perPage, $perPage);

        $concessions = new LengthAwarePaginator(
            $currentItems,
            count($concessions),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
    

        return view('admin/dashboard/cashier-concessions/cashier', compact('concessions'));
    }

    public function addToCart($id, Request $request)
    {
        $cashier = $request->session()->get('cashier') ?? [];

        foreach ($cashier as $key => $value) {
            if($value['id'] == $id)
            {
                $cashier[$key]['qty'] += $request->amount_to_add;
                $request->session()->put('cashier', $cashier);
                return redirect()->route('toCashierConcessions')->with('success', 'Concession qty added');
            }
        }

        $concessionsSnapshot = $this->database->getReference("tconcessions/$id")->getSnapshot();
        $concessionsData = $concessionsSnapshot->getValue();
        $concessions = array_merge(['id' => $id, 'qty' => $request->amount_to_add], $concessionsData);
        $concessions['image'] = $this->storage->getBucket(env('FIREBASE_STORAGE_BUCKET'))->object( $concessionsData['image'] )->signedUrl(new \DateTime('tomorrow'));

        $cashier[] = $concessions;
        
        $request->session()->put('cashier', $cashier);

        return redirect()->route('toCashierConcessions')->with('success', 'Concession added to cart');
    }

    public function removeFromCart($id, Request $request)
    {
        $cashier = $request->session()->get('cashier') ?? [];

        foreach ($cashier as $key => $value) {
            if ($value['id'] == $id)
            {
                unset($cashier[$key]);
            }
        }

        $request->session()->put('cashier', $cashier);

        return redirect()->route('toCashierConcessions')->with('success', 'Concession removed from cart');
    }

    public function addToUsersCart($id, Request $request)
    {
        $cashier = $request->session()->get('cart') ?? [];

        foreach ($cashier as $key => $value) {
            if($value['id'] == $id)
            {
                $cashier[$key]['qty'] += $request->amount_to_add;
                $request->session()->put('cart', $cashier);
                return redirect()->route('toConcessions')->with('success', 'Concession qty added');
            }
        }

        $concessionsSnapshot = $this->database->getReference("tconcessions/$id")->getSnapshot();
        $concessionsData = $concessionsSnapshot->getValue();
        $concessions = array_merge(['id' => $id, 'qty' => $request->amount_to_add], $concessionsData);

        $cashier[] = $concessions;
        
        $request->session()->put('cart', $cashier);

        return redirect()->route('toConcessions')->with('success', 'Concession added to cart');
    }

    public function removeFromUsersCart($id, Request $request)
    {
        $cashier = $request->session()->get('cart') ?? [];

        foreach ($cashier as $key => $value) {
            if ($value['id'] == $id)
            {
                unset($cashier[$key]);
            }
        }

        $request->session()->put('cart', $cashier);

        return redirect()->route('toConcessions')->with('success', 'Concession removed from cart');
    }

    public function clearCart(Request $request)
    {
        $request->session()->forget('cashier');

        return redirect()->route('toCashierConcessions')->with('success', 'Cart cleared');
    }

    public function clearUsersCart(Request $request)
    {
        $request->session()->forget('cart');

        return redirect()->back();
    }
}
