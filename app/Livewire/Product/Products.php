<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Wishlist; // tambahkan
use Illuminate\Support\Facades\Auth; // tambahkan
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination, WithFileUploads;

    public $name, $image, $description, $stock, $price, $product_id;
    public $mode = '';
    public $modal_title = '';
    public $oldImage;
    public $deleteId = null;
    public $deleteName = null;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $brand_id;
    public $brands = [];
    public $categories = [];
    public $category_id;

    protected $listeners = ['reset-form' => 'resetInput'];
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        // Jika pengguna yang login bukan admin, paksa keluar.
        // if (! auth()->user()->isAdmin()) { // atau sesuai dengan logika role Anda
        //     abort(403, 'Unauthorized Access');
        // }
        $this->brands = Brand::orderBy('name')->get();
        $this->categories = Category::orderBy('name')->get();
    }

    public function rules()
    {
        $rules = [
            'name'        => 'required|string|max:255',
            'brand_id'    => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0|max:99999999999999999999999',
            'stock'       => 'required|integer|min:0|max:99999999999999999999999',
            'description' => 'required|string',
        ];

        $rules['image'] = $this->product_id
            ? 'nullable|image|max:2048'
            : 'required|image|max:2048';

        return $rules;
    }

    public function resetInput($full = true)
    {
        $this->product_id = null;
        $this->name = '';
        $this->description = '';
        $this->stock = '';
        $this->price = '';
        $this->brand_id = '';
        $this->image = null;

        if ($full) {
            $this->oldImage = null;
        }

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function create()
    {
        $this->resetInput(true);
        $this->mode = 'create';
        $this->modal_title = 'Add Product';
        $this->dispatch('open-modal');
    }

    public function store()
    {
        try {
            $this->validate();

            $path = $this->image ? $this->image->store('products', 'public') : null;

            Product::create([
                'name'        => $this->name,
                'brand_id'    => $this->brand_id,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'stock'       => $this->stock,
                'price'       => $this->price,
                'image'       => $path,
            ]);

            $this->dispatch('toastr', type: 'success', message: 'Product added successfully ✅');
            $this->dispatch('close-modal');
            $this->resetInput();
        } catch (\Exception $e) {
            dd($e->getMessage()); // debug
            $this->dispatch('toastr', type: 'error', message: 'Failed to save Product ❌');
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        $this->product_id  = $id;
        $this->name        = $product->name;
        $this->brand_id    = $product->brand_id;
        $this->category_id = $product->category_id;
        $this->description = $product->description;
        $this->stock       = $product->stock;
        $this->price       = $product->price;
        $this->image       = null;
        $this->oldImage    = $product->image;

        $this->mode        = 'edit';
        $this->modal_title = 'Edit Product';
        $this->dispatch('open-modal');
    }

    public function update()
    {
        try {
            $this->validate();

            $product = Product::findOrFail($this->product_id);

            $path = $this->oldImage;
            if ($this->image) {
                $path = $this->image->store('products', 'public');
            }

            $product->update([
                'name'        => $this->name,
                'brand_id'    => $this->brand_id,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'stock'       => $this->stock,
                'price'       => $this->price,
                'image'       => $path,
            ]);

            $this->oldImage = $path;

            $this->dispatch('toastr', type: 'success', message: 'Product updated successfully ✅');
            $this->dispatch('close-modal');
            $this->resetInput(false);
        } catch (\Exception $e) {
            dd($e->getMessage()); // debug
            $this->dispatch('toastr', type: 'error', message: 'Failed to update Product ❌');
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->deleteName = Product::find($id)?->name;
        $this->dispatch('open-delete-modal');
    }

    public function delete()
    {
        try {
            Product::find($this->deleteId)?->delete();
            $this->dispatch('close-delete-modal');
            $this->dispatch('toastr', type: 'success', message: 'Product deleted successfully 🗑️');
            $this->resetInput(['deleteId', 'deleteName']);
        } catch (\Exception $e) {
            $this->dispatch('toastr', type: 'error', message: 'Failed to delete Product ❌');
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    // 👉 Tambahkan wishlist
    public function toggleWishlist($productId)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $this->dispatch('toastr', type: 'info', message: 'Produk dihapus dari wishlist 🤍');
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            $this->dispatch('toastr', type: 'success', message: 'Produk ditambahkan ke wishlist ❤️');
        }
    }

    public function render()
    {
        $products = Product::with('brand')
            ->when($this->search !== '', function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhere('price', 'like', '%' . $this->search . '%')
                        ->orWhere('stock', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.product.products', [
            'products' => $products,
            'brands'   => $this->brands,
        ]);
    }
}
