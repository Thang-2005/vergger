<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class VnpayService
{
    private $vnp_TmnCode;
    private $vnp_HashSecret;
    private $vnp_Url;
    private $vnp_ReturnUrl;
    private $vnp_IpnUrl;

    public function __construct()
    {
        $this->vnp_TmnCode = config('services.vnpay.tmn_code');
        $this->vnp_HashSecret = config('services.vnpay.hash_secret');
        $this->vnp_Url = config('services.vnpay.url');
        $this->vnp_ReturnUrl = config('services.vnpay.return_url');
        $this->vnp_IpnUrl = config('services.vnpay.ipn_url');
    }

    /**
     * Tạo URL thanh toán VNPAY
     */
    public function createPaymentUrl($vnp_OrderId, $vnp_Amount, $vnp_OrderInfo, $vnp_IpAddr = null)
    {
        $vnp_Amount = $vnp_Amount * 100; // VNPAY tính bằng đồng (nhân 100)
        
        $createDate = date('YmdHis');
        // Thời gian hết hạn: 120 phút (2 tiếng) để đủ thời gian user thanh toán
        $expireDate = date('YmdHis', strtotime('+120 minutes'));

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => (int)$vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $createDate,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr ?? $this->getIpAddr(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $this->vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_OrderId,
            "vnp_ExpireDate" => $expireDate,
        );

        ksort($inputData);

        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= "&" . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Remove trailing &
        $query = rtrim($query, '&');

        $vnp_Url = $this->vnp_Url . "?" . $query;
        if (isset($this->vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
            $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;
        }

        Log::info('VNPAY Payment URL created', [
            'order_id' => $vnp_OrderId,
            'amount' => $vnp_Amount / 100,
            'create_date' => $createDate,
            'expire_date' => $expireDate,
            'return_url' => $this->vnp_ReturnUrl,
            'has_secure_hash' => isset($this->vnp_HashSecret),
        ]);

        return $vnp_Url;
    }

    /**
     * Xác minh response từ VNPAY
     */
    public function validateResponse($data)
    {
        $vnp_SecureHash = $data['vnp_SecureHash'] ?? null;
        unset($data['vnp_SecureHash']);

        ksort($data);
        $i = 0;
        $hashdata = "";
        foreach ($data as $key => $value) {
            if ($i == 1) {
                $hashdata .= "&" . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash) {
            return true;
        }

        Log::warning('VNPAY Invalid Secure Hash', [
            'expected' => $secureHash,
            'received' => $vnp_SecureHash,
        ]);

        return false;
    }

    /**
     * Lấy địa chỉ IP
     */
    private function getIpAddr()
    {
        $ipaddr = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddr = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddr = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddr = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddr = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddr = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddr = getenv('REMOTE_ADDR');
        else
            $ipaddr = '127.0.0.1';

        return $ipaddr;
    }

    /**
     * Kiểm tra kết quả thanh toán
     */
    public function getPaymentStatus($responseCode)
    {
        switch ($responseCode) {
            case '00':
                return ['status' => 'success', 'message' => 'Giao dịch thành công'];
            case '01':
                return ['status' => 'failed', 'message' => 'Gọi API không thành công'];
            case '02':
                return ['status' => 'failed', 'message' => 'Merchant không hợp lệ'];
            case '03':
                return ['status' => 'failed', 'message' => 'Lỗi từ phía VNPAY'];
            case '04':
                return ['status' => 'failed', 'message' => 'Thẻ/Tài khoản bị khóa'];
            case '05':
                return ['status' => 'failed', 'message' => 'Giao dịch bị từ chối'];
            case '07':
                return ['status' => 'failed', 'message' => 'Trừ tiền thành công nhưng chuyển khoản không thành công'];
            case '09':
                return ['status' => 'failed', 'message' => 'Giao dịch bị hủy'];
            case '10':
                return ['status' => 'failed', 'message' => 'Checksum không hợp lệ'];
            case '11':
                return ['status' => 'failed', 'message' => 'Merchant không cấu hình được CreateDate'];
            case '12':
                return ['status' => 'failed', 'message' => 'Giao dịch không tìm thấy trên Gateway VNPAY'];
            case '13':
                return ['status' => 'failed', 'message' => 'Trạng thái giao dịch không hợp lệ để hoàn tiền'];
            case '91':
                return ['status' => 'failed', 'message' => 'Đã hết hạn chờ thanh toán'];
            case '94':
                return ['status' => 'pending', 'message' => 'Giao dịch đang chờ xử lý'];
            case '99':
                return ['status' => 'unknown', 'message' => 'Người dùng hủy giao dịch'];
            default:
                return ['status' => 'unknown', 'message' => 'Trạng thái giao dịch không xác định'];
        }
    }
}
