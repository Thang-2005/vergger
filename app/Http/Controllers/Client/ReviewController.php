<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Product;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.min' => 'Số sao phải từ 1 trở lên.',
            'rating.max' => 'Số sao tối đa là 5.',
            'comment.required' => 'Vui lòng nhập nhận xét của bạn.',
            'comment.min' => 'Nhận xét phải tối thiểu 10 ký tự.',
            'comment.max' => 'Nhận xét tối đa 500 ký tự.',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        
        if ($review->user_id != Auth::id()) {
            return back()->with('error', 'Bạn không có quyền cập nhật đánh giá này.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.min' => 'Số sao phải từ 1 trở lên.',
            'rating.max' => 'Số sao tối đa là 5.',
            'comment.required' => 'Vui lòng nhập nhận xét của bạn.',
            'comment.min' => 'Nhận xét phải tối thiểu 10 ký tự.',
            'comment.max' => 'Nhận xét tối đa 500 ký tự.',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Đánh giá đã được cập nhật.');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        
        if ($review->user_id != Auth::id()) {
            return back()->with('error', 'Bạn không có quyền xóa đánh giá này.');
        }

        $review->delete();
        return back()->with('success', 'Đánh giá đã được xóa.');
    }
}
