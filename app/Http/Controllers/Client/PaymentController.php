<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\VnpayService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $vnpayService;

    public function __construct(VnpayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    /**
     * Xử lý return từ VNPAY
     */
    public function vnpayReturn(Request $request)
    {
        try {
            $data = $request->all();

            // Xác minh chữ ký
            if (!$this->vnpayService->validateResponse($data)) {
                Log::warning('VNPAY Invalid signature');
                return redirect()->route('checkout')
                    ->with('error', 'Chữ ký thanh toán không hợp lệ');
            }

            $vnp_TxnRef = $data['vnp_TxnRef'] ?? null;
            $vnp_ResponseCode = $data['vnp_ResponseCode'] ?? null;
            $vnp_TransactionNo = $data['vnp_TransactionNo'] ?? null;
            $vnp_Amount = ($data['vnp_Amount'] ?? 0) / 100;

            if (!$vnp_TxnRef) {
                Log::warning('VNPAY Missing TxnRef');
                return redirect()->route('checkout')
                    ->with('error', 'Dữ liệu thanh toán không hợp lệ');
            }

            // Tìm order by payment vnp_txn_ref
            $payment = \App\Models\Payment::where('vnp_txn_ref', $vnp_TxnRef)->first();
            $order = $payment ? $payment->order : null;

            if (!$order || !$payment) {
                Log::warning('VNPAY Transaction not found', ['txn_ref' => $vnp_TxnRef, 'transaction_no' => $vnp_TransactionNo]);
                return redirect()->route('checkout')
                    ->with('error', 'Không tìm thấy đơn hàng');
            }

            // Xử lý kết quả thanh toán
            $paymentStatus = $this->vnpayService->getPaymentStatus($vnp_ResponseCode);

            if ($vnp_ResponseCode === '00') {
                // Thanh toán thành công
                DB::transaction(function () use ($order, $payment, $vnp_TransactionNo, $vnp_TxnRef, $vnp_Amount) {
                    // Cập nhật order
                    $orderUpdated = $order->update([
                        'payment_status' => 'completed',
                        'status' => 'confirmed',
                    ]);
                    
                    // Cập nhật payment (sử object được lookup thắp hơn)
                    $paymentUpdated = $payment->update([
                        'status' => 'completed',
                        'transaction_id' => $vnp_TransactionNo,
                        'paid_at' => now(),
                    ]);
                    
                    Log::info('VNPAY Payment successful - DB Updates', [
                        'order_id' => $order->id,
                        'order_updated' => $orderUpdated,
                        'payment_updated' => $paymentUpdated,
                        'txn_ref' => $vnp_TxnRef,
                        'amount' => $vnp_Amount,
                        'transaction_no' => $vnp_TransactionNo,
                    ]);
                });

                Log::info('VNPAY Payment successful', [
                    'order_id' => $order->id,
                    'txn_ref' => $vnp_TxnRef,
                    'amount' => $vnp_Amount,
                    'transaction_no' => $vnp_TransactionNo,
                ]);

                // Gửi email xác nhận
                // Mail::send(new OrderConfirmationMail($order));

                return redirect()->route('order.detail', $order->id)
                    ->with('success', 'Thanh toán thành công! Đơn hàng của bạn đã được xác nhận.');

            } else {
                // Thanh toán thất bại
                DB::transaction(function () use ($order, $payment, $vnp_TransactionNo) {
                    // Cập nhật order (giữ status pending để user có thể thanh toán lại)
                    $orderUpdated = $order->update([
                        'payment_status' => 'failed',
                    ]);
                    
                    // Cập nhật payment
                    $paymentUpdated = $payment->update([
                        'status' => 'failed',
                        'transaction_id' => $vnp_TransactionNo,
                    ]);
                    
                    Log::warning('VNPAY Payment failed - DB Updates', [
                        'order_id' => $order->id,
                        'order_updated' => $orderUpdated,
                        'payment_updated' => $paymentUpdated,
                    ]);
                });
                
                Log::warning('VNPAY Payment failed', [
                    'order_id' => $order->id,
                    'response_code' => $vnp_ResponseCode,
                    'message' => $paymentStatus['message'],
                ]);

                return redirect()->route('order.detail', $order->id)
                    ->with('error', 'Thanh toán thất bại: ' . $paymentStatus['message']);
            }

        } catch (\Exception $e) {
            Log::error('VNPAY Return Error', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return redirect()->route('checkout')
                ->with('error', 'Lỗi xử lý thanh toán: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý IPN từ VNPAY (server call server)
     */
    public function vnpayIpn(Request $request)
    {
        try {
            $data = $request->all();

            Log::info('VNPAY IPN received', $data);

            // Xác minh chữ ký
            if (!$this->vnpayService->validateResponse($data)) {
                Log::warning('VNPAY IPN Invalid signature');
                return response()->json(['RspCode' => '97', 'Message' => 'Invalid signature']);
            }

            $vnp_TxnRef = $data['vnp_TxnRef'] ?? null;
            $vnp_ResponseCode = $data['vnp_ResponseCode'] ?? null;
            $vnp_TransactionNo = $data['vnp_TransactionNo'] ?? null;
            $vnp_Amount = ($data['vnp_Amount'] ?? 0) / 100;

            if (!$vnp_TxnRef) {
                Log::warning('VNPAY IPN Missing TxnRef');
                return response()->json(['RspCode' => '97', 'Message' => 'Invalid TxnRef']);
            }

            // Tìm order by payment vnp_txn_ref
            $payment = \App\Models\Payment::where('vnp_txn_ref', $vnp_TxnRef)->first();
            $order = $payment ? $payment->order : null;

            if (!$order || !$payment) {
                Log::warning('VNPAY IPN Order not found', ['txn_ref' => $vnp_TxnRef, 'transaction_no' => $vnp_TransactionNo]);
                return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
            }

            // Kiểm tra số tiền
            if ($order->total_price != $vnp_Amount) {
                Log::warning('VNPAY IPN Invalid amount', [
                    'expected' => $order->total_price,
                    'received' => $vnp_Amount,
                ]);
                return response()->json(['RspCode' => '04', 'Message' => 'Invalid amount']);
            }

            if ($vnp_ResponseCode === '00') {
                // Kiểm tra nếu đã xử lý trước đó
                if ($order->payment_status === 'completed') {
                    Log::info('VNPAY IPN Already processed', ['order_id' => $order->id]);
                    return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
                }

                // Cập nhật trạng thái thanh toán
                DB::transaction(function () use ($order, $payment, $vnp_TransactionNo, $vnp_TxnRef, $vnp_Amount) {
                    $orderUpdated = $order->update([
                        'payment_status' => 'completed',
                        'status' => 'confirmed',
                    ]);
                    
                    $paymentUpdated = $payment->update([
                        'status' => 'completed',
                        'transaction_id' => $vnp_TransactionNo,
                        'paid_at' => now(),
                    ]);
                    
                    Log::info('VNPAY IPN Payment confirmed - DB Updates', [
                        'order_id' => $order->id,
                        'order_updated' => $orderUpdated,
                        'payment_updated' => $paymentUpdated,
                        'txn_ref' => $vnp_TxnRef,
                        'amount' => $vnp_Amount,
                        'transaction_no' => $vnp_TransactionNo,
                    ]);
                });

                Log::info('VNPAY IPN Payment confirmed', [
                    'order_id' => $order->id,
                    'txn_ref' => $vnp_TxnRef,
                    'amount' => $vnp_Amount,
                    'transaction_no' => $vnp_TransactionNo,
                ]);

                return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);

            } else {
                // Giao dịch thất bại
                DB::transaction(function () use ($order, $payment, $vnp_TransactionNo) {
                    $orderUpdated = $order->update([
                        'payment_status' => 'failed',
                    ]);
                    
                    $paymentUpdated = $payment->update([
                        'status' => 'failed',
                        'transaction_id' => $vnp_TransactionNo,
                    ]);
                    
                    Log::warning('VNPAY IPN Payment failed - DB Updates', [
                        'order_id' => $order->id,
                        'order_updated' => $orderUpdated,
                        'payment_updated' => $paymentUpdated,
                    ]);
                });

                Log::warning('VNPAY IPN Payment failed', [
                    'order_id' => $order->id,
                    'response_code' => $vnp_ResponseCode,
                ]);

                return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
            }

        } catch (\Exception $e) {
            Log::error('VNPAY IPN Error', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json(['RspCode' => '99', 'Message' => 'Processing error']);
        }
    }
}
