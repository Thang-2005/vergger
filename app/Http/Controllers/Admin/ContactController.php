<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Mail\ContactReplyMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(Request $request){
        $filter = $request->query('filter', 'all'); // all, replied, unreplied
        
        $query = Contact::orderBy('created_at', 'desc');
        
        if ($filter === 'replied') {
            $query->where('is_Reply', 1);
        } elseif ($filter === 'unreplied') {
            $query->where('is_Reply', 0);
        }
        
         
        $customerMessage = Contact::select('message')->first()->message ?? 'Không có nội dung tin nhắn.';
            
        $contacts = $query->get();
        return view('admin.pages.contacts.index', compact('contacts', 'filter'));
    }

    public function show(Contact $contact){
        return view('admin.pages.contacts.contact_detail', compact('contact'));
    }

    public function reply(Request $request, Contact $contact){
        try {
            $replyContent = $request->input('reply_content');

            // Save reply content to database
            $contact->update([
                'is_Reply' => 1,
                'reply_content' => $replyContent,
            ]);

            // Send email with reply using Mailable
            Mail::send(new ContactReplyMail(
                $contact->full_name,
                $replyContent,
                $contact
            ));

            // Update is_Reply status to 1
            $contact->update(['is_Reply' => 1]);

            flash('Phản hồi đã được gửi thành công!', 'success');
            return back();

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Contact $contact){
        $contact->delete();
        return response()->json(['success' => true, 'message' => 'Xóa liên hệ thành công']);
    }
}
