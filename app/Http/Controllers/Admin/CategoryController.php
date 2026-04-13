<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (!$adminUser || !$adminUser->hasPermission('categories.view')) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        $categories = Category::withCount('products')->orderByDesc('id')->paginate(10);
        $totalCategories = Category::count();
        $activeCategories = Category::where('status', true)->count();
        $inactiveCategories = Category::where('status', false)->count();
        $canCreateCategory = $adminUser->hasPermission('categories.create');
        $canUpdateCategory = $adminUser->hasPermission('categories.update');
        $canDeleteCategory = $adminUser->hasPermission('categories.delete');
        $canToggleCategory = $adminUser->hasPermission('categories.toggle_status');

        return view('admin.pages.categories.index', compact(
            'categories',
            'totalCategories',
            'activeCategories',
            'inactiveCategories',
            'canCreateCategory',
            'canUpdateCategory',
            'canDeleteCategory',
            'canToggleCategory'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (!$adminUser || !$adminUser->hasPermission('categories.create')) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug'],
            'description' => ['nullable', 'string'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'status' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $validated['status'] = $request->boolean('status');
        $validated['image'] = $this->storeImage($request);

        Category::create($validated);

        flash('Thêm danh mục thành công.', 'success');
        return redirect()->route('admin.categories.index');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (!$adminUser || !$adminUser->hasPermission('categories.update')) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug,' . $category->id],
            'description' => ['nullable', 'string'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'status' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $validated['status'] = $request->boolean('status');
        $validated['image'] = $this->storeImage($request, $category);

        $category->update($validated);

        flash('Cập nhật danh mục thành công.', 'success');
        return redirect()->route('admin.categories.index');
    }

    public function destroy(Category $category): RedirectResponse
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (!$adminUser || !$adminUser->hasPermission('categories.delete')) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        if ($category->products()->exists()) {
            flash('Không thể xóa danh mục đang chứa sản phẩm.', 'error');
            return redirect()->route('admin.categories.index');
        }

        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        flash('Xóa danh mục thành công.', 'success');
        return redirect()->route('admin.categories.index');
    }

    public function toggleStatus(Category $category): RedirectResponse
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (!$adminUser || !$adminUser->hasPermission('categories.toggle_status')) {
            abort(403, 'Bạn không có quyền thực hiện thao tác này.');
        }

        $category->status = ! $category->status;
        $category->save();

        flash(
            $category->status ? 'Danh mục đã được kích hoạt.' : 'Danh mục đã được ẩn.',
            'success'
        );

        return redirect()->route('admin.categories.index');
    }

    private function storeImage(Request $request, ?Category $category = null): ?string
    {
        if (! $request->hasFile('image_file')) {
            return $category?->image;
        }

        if ($category?->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $file = $request->file('image_file');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        return $file->storeAs('uploads/categories', $fileName, 'public');
    }
}
