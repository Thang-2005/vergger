<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function show_products(Request $request)
    {
        return $this->renderProductsPage($request);
    }

    public function create()
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (! $adminUser || ! $adminUser->hasPermission('products.create')) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        return $this->renderProductsPage(request(), [
            'openCreateModal' => true,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (! $adminUser || ! $adminUser->hasPermission('products.create')) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['in_stock', 'out_of_stock'])],
            'unit' => ['nullable', 'string', 'max:255'],
            'image_file' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'additional_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);

        $additionalImagesCount = count($request->file('additional_images') ?? []);
        if (1 + $additionalImagesCount > 5) {
            return back()->withErrors(['additional_images' => 'Tối đa 5 ảnh (1 ảnh chính + 4 ảnh phụ)']);
        }

        $validated['slug'] = $this->generateUniqueSlug($validated['name'], $validated['slug'] ?? null);

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'],
            'stock' => $validated['stock'],
            'status' => $validated['status'],
            'unit' => $validated['unit'] ?? null,
        ]);

        $this->storeProductImage($product, $request);
        $this->storeAdditionalImages($product, $request);

        flash('Thêm sản phẩm thành công.', 'success');

        return redirect()->route('admin.products.list');
    }

    public function detail(Product $product)
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (! $adminUser || ! $adminUser->hasPermission('products.view')) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return view('admin.pages.products.detail', [
            'product' => $product->load(['category', 'image']),
        ]);
    }

    public function edit(Product $product)
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (! $adminUser || ! $adminUser->hasPermission('products.update')) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        $categories = Category::orderBy('name')->get();

        return view('admin.pages.products.edit', [
            'product' => $product->load(['category', 'image']),
            'categories' => $categories,
        ]);
    }

    public function show(Product $product)
    {
        return $this->renderProductsPage(request(), [
            'selectedProduct' => $product->load(['category', 'firstImage']),
            'openShowModal' => true,
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (! $adminUser || ! $adminUser->hasPermission('products.update')) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($product->id)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product->id)],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['in_stock', 'out_of_stock'])],
            'unit' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'additional_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'deleted_images' => ['nullable', 'string'],
        ]);

        $currentImages = $product->image()->count();
        $newAdditionalCount = count($request->file('additional_images') ?? []);
        $deletedImagesCount = count(array_filter(explode(',', $request->get('deleted_images'))));
        $totalAfterUpdate = $currentImages - $deletedImagesCount + $newAdditionalCount;
        if ($request->hasFile('image_file')) {
            $totalAfterUpdate = $totalAfterUpdate - 1 + 1;
        }
        if ($totalAfterUpdate > 5) {
            return back()->withErrors(['additional_images' => 'Tối đa 5 ảnh tổng cộng']);
        }

        $validated['slug'] = $this->generateUniqueSlug($validated['name'], $validated['slug'] ?? null, $product->id);

        $product->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'],
            'stock' => $validated['stock'],
            'status' => $validated['status'],
            'unit' => $validated['unit'] ?? null,
        ]);

        // Handle deleted images
        if ($request->has('deleted_images')) {
            $deletedImages = array_filter(explode(',', $request->get('deleted_images')));
            foreach ($deletedImages as $imageId) {
                $this->deleteProductImage($imageId);
            }
        }

        // Update main image if provided
        if ($request->hasFile('image_file')) {
            // Get the first image before deletion to access its file path
            $firstImage = $product->image()->first();
            if ($firstImage) {
                // Delete from storage first
                if ($firstImage->image && Storage::disk('public')->exists('uploads/product/' . $firstImage->image)) {
                    Storage::disk('public')->delete('uploads/product/' . $firstImage->image);
                }
                // Then delete from database
                $firstImage->delete();
            }
            $this->storeProductImage($product, $request);
        }

        // Add additional images
        if ($request->hasFile('additional_images')) {
            $this->storeAdditionalImages($product, $request);
        }

        flash('Cập nhật sản phẩm thành công.', 'success');

        return redirect()->route('admin.products.list');

        return redirect()->route('admin.products.list');
    }

    public function destroy(Product $product): RedirectResponse
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (! $adminUser || ! $adminUser->hasPermission('products.delete')) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        $this->deleteProductImages($product);
        $product->delete();

        flash('Xóa sản phẩm thành công.', 'success');

        return redirect()->route('admin.products.list');
    }

    private function generateUniqueSlug(string $name, ?string $providedSlug = null, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($providedSlug ?: $name) ?: 'san-pham';
        $slug = $baseSlug;
        $suffix = 1;

        while (
            Product::query()
                ->when($ignoreId, function ($query) use ($ignoreId) {
                    $query->where('id', '!=', $ignoreId);
                })
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    private function storeProductImage(Product $product, Request $request): void
    {
        if (! $request->hasFile('image_file')) {
            return;
        }

        $file = $request->file('image_file');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('uploads/product', $fileName, 'public');

        ProductImage::create([
            'product_id' => $product->id,
            'image' => $fileName,
        ]);
    }

    private function storeAdditionalImages(Product $product, Request $request): void
    {
        if (! $request->hasFile('additional_images')) {
            return;
        }

        $files = $request->file('additional_images');
        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('uploads/product', $fileName, 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $fileName,
                ]);
            }
        }
    }

    private function deleteProductImage($imageId): void
    {
        $image = ProductImage::find($imageId);
        if ($image) {
            if ($image->image && Storage::disk('public')->exists('uploads/product/' . $image->image)) {
                Storage::disk('public')->delete('uploads/product/' . $image->image);
            }
            $image->delete();
        }
    }

    private function deleteProductImages(Product $product): void
    {
        $productImages = $product->image()->get();

        foreach ($productImages as $productImage) {
            if ($productImage->image && Storage::disk('public')->exists('uploads/product/' . $productImage->image)) {
                Storage::disk('public')->delete('uploads/product/' . $productImage->image);
            }
        }

        $product->image()->delete();
    }

    private function renderProductsPage(Request $request, array $extraData = [])
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (! $adminUser || ! $adminUser->hasPermission('products.view')) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        $categories = Category::orderBy('name')->get();

        $productsQuery = Product::with(['category', 'firstImage'])
            ->orderByDesc('id');

        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));

            $productsQuery->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('slug', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('category_id')) {
            $productsQuery->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('status')) {
            $productsQuery->where('status', $request->input('status'));
        }

        $filteredProductsCount = (clone $productsQuery)->count();
        $products = $productsQuery->paginate(10)->appends($request->query());

        $totalProducts = Product::count();
        $inStockProducts = Product::where('status', 'in_stock')->count();
        $outOfStockProducts = Product::where('status', 'out_of_stock')->count();
        $totalStock = Product::sum('stock');

        $selectedProduct = $extraData['selectedProduct'] ?? null;

        return view('admin.pages.products.show_products', array_merge(compact(
            'categories',
            'products',
            'totalProducts',
            'inStockProducts',
            'outOfStockProducts',
            'totalStock',
            'filteredProductsCount',
            'selectedProduct'
        ), $extraData));
    }

    public function getProductImages(Product $product)
    {
        $images = $product->image()->get();
        return response()->json([
            'images' => $images->map(fn($img) => [
                'id' => $img->id,
                'image' => $img->image,
            ])->values(),
        ]);
    }
}
