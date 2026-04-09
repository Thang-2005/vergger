<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function show_contact()
    {
        return view('clients.pages.contact');
    }

    public function submit_contact(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|min:5',
        ], [
            'name.required' => 'Tên là bắt buộc',
            'name.string' => 'Tên phải là chuỗi ký tự',
            'name.max' => 'Tên không được vượt quá 255 ký tự',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không hợp lệ',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'phone.string' => 'Số điện thoại không hợp lệ',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự',
            'message.required' => 'Tin nhắn là bắt buộc',
            'message.string' => 'Tin nhắn phải là chuỗi ký tự',
            'message.min' => 'Tin nhắn phải có ít nhất 5 ký tự',
        ]);

        // Create a new contact record in the database
        Contact::create([
            'full_name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone'),
            'is_Reply' => '0',
            'message' => $request->input('message'),
        ]);

        // Return JSON response for AJAX requests (same flow as other async forms)
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'message' => 'Cảm ơn bạn đã liên hệ với chúng tôi! Chúng tôi sẽ phản hồi sớm nhất có thể.',
                'redirect' => route('contact')
            ]);
        }
        
        // Redirect back with a success message
        flash('Cảm ơn bạn đã liên hệ với chúng tôi! Chúng tôi sẽ phản hồi sớm nhất có thể.', 'success');
        return redirect()->route('contact');
    }
}
