// Order Detail Page - PDF Invoice Functions
document.addEventListener('DOMContentLoaded', function () {

    const invoiceDataJsonElement = document.getElementById('invoiceDataJson');
    const invoiceDataJson = invoiceDataJsonElement ? JSON.parse(invoiceDataJsonElement.textContent) : {};

    // Download Invoice Button
    const downloadBtn = document.getElementById('downloadInvoiceBtn');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function (e) {
            e.preventDefault();
            try {
                if (typeof pdfMake === 'undefined') {
                    alert('Lỗi: Thư viện PDF không sẵn sàng. Vui lòng tải lại trang.');
                    return;
                }
                if (!invoiceDataJson || !invoiceDataJson.id) {
                    alert('Lỗi: Không có dữ liệu hóa đơn.');
                    return;
                }
                generateAndDownloadInvoice(invoiceDataJson);
            } catch (error) {
                console.error('Tải PDF thất bại:', error);
                alert('Lỗi khi tải PDF: ' + error.message);
            }
        });
    }

    // Print Invoice Button
    const printBtn = document.getElementById('printInvoiceBtn');
    if (printBtn) {
        printBtn.addEventListener('click', function (e) {
            e.preventDefault();
            try {
                if (typeof pdfMake === 'undefined') {
                    alert('Lỗi: Thư viện PDF không sẵn sàng. Vui lòng tải lại trang.');
                    return;
                }
                if (!invoiceDataJson || !invoiceDataJson.id) {
                    alert('Lỗi: Không có dữ liệu hóa đơn.');
                    return;
                }
                generateAndPrintInvoice(invoiceDataJson);
            } catch (error) {
                console.error('In hóa đơn thất bại:', error);
                alert('Lỗi khi in: ' + error.message);
            }
        });
    }

    function generateAndDownloadInvoice(order) {
        const currencyFormatter = new Intl.NumberFormat('vi-VN');

        const paymentMethodLabel = {
            cash: 'Thanh toán khi nhận hàng',
            cod: 'Thanh toán khi nhận hàng',
            vnpay: 'VNPAY',
            paypal: 'PayPal'
        }[order.payment_method] || 'Chưa cập nhật';

        const statusLabel = {
            pending: 'Chờ xác nhận',
            processing: 'Đang xử lý',
            shipped: 'Đang giao',
            completed: 'Hoàn thành',
            cancelled: 'Đã hủy',
            canceled: 'Đã hủy'
        }[order.status] || order.status;

        const rows = order.items.map(function (item, index) {
            return [
                { text: String(index + 1), alignment: 'center' },
                item.name,
                { text: String(item.quantity), alignment: 'center' },
                { text: currencyFormatter.format(item.price) + ' đ', alignment: 'right' },
                { text: currencyFormatter.format(item.total) + ' đ', alignment: 'right' },
            ];
        });

        const subTotal = Number(order.sub_total || order.total_price || 0);
        const discountAmount = Number(order.discount_amount || 0);
        const summaryRows = [
            [
                { text: 'Tạm tính', colSpan: 4, alignment: 'right', bold: true },
                {},
                {},
                {},
                { text: currencyFormatter.format(subTotal) + ' đ', alignment: 'right', bold: true },
            ]
        ];
        if (discountAmount > 0) {
            summaryRows.push([
                { text: 'Giảm giá' + (order.coupon_code ? ' (' + order.coupon_code + ')' : ''), colSpan: 4, alignment: 'right', color: '#2f6d3a', bold: true },
                {},
                {},
                {},
                { text: '-' + currencyFormatter.format(discountAmount) + ' đ', alignment: 'right', color: '#2f6d3a', bold: true },
            ]);
        }
        summaryRows.push([
            { text: 'Tổng cộng', colSpan: 4, alignment: 'right', bold: true },
            {},
            {},
            {},
            { text: currencyFormatter.format(order.total_price) + ' đ', alignment: 'right', bold: true },
        ]);

        const docDefinition = {
            pageSize: 'A4',
            pageMargins: [40, 48, 40, 48],
            content: [
                { text: 'VEGGIE', style: 'brand' },
                { text: 'HÓA ĐƠN BÁN HÀNG', style: 'title' },
                {
                    columns: [
                        [
                            { text: 'Thông tin đơn hàng', style: 'sectionTitle' },
                            { text: 'Mã đơn: #' + order.id },
                            { text: 'Ngày đặt: ' + (order.created_at || '-') },
                            { text: 'Trạng thái: ' + statusLabel },
                        ],
                        [
                            { text: 'Thông tin thanh toán', style: 'sectionTitle' },
                            { text: 'Phương thức: ' + paymentMethodLabel },
                            { text: 'Trạng thái: ' + (order.payment_status_label || 'Chưa cập nhật') },
                        ]
                    ],
                    columnGap: 24,
                    margin: [0, 0, 0, 16]
                },
                {
                    columns: [
                        [
                            { text: 'Người nhận', style: 'sectionTitle' },
                            { text: order.shipping ? order.shipping.full_name : 'Chưa có thông tin' },
                            { text: order.shipping ? order.shipping.phone : '' },
                            { text: order.shipping ? (order.shipping.address + ', ' + order.shipping.city) : '' },
                        ],
                        [
                            { text: 'Cửa hàng', style: 'sectionTitle' },
                            { text: 'Veggie' },
                            { text: 'Hotline: 0900 000 000' },
                            { text: 'Email: support@veggie.local' },
                        ]
                    ],
                    columnGap: 24,
                    margin: [0, 0, 0, 18]
                },
                {
                    table: {
                        headerRows: 1,
                        widths: [30, '*', 50, 85, 85],
                        body: [
                            [
                                { text: '#', style: 'tableHeader' },
                                { text: 'Sản phẩm', style: 'tableHeader' },
                                { text: 'SL', style: 'tableHeader' },
                                { text: 'Đơn giá', style: 'tableHeader' },
                                { text: 'Thành tiền', style: 'tableHeader' },
                            ],
                            ...rows,
                            ...summaryRows
                        ]
                    },
                    layout: 'lightHorizontalLines'
                },
                {
                    text: 'Cảm ơn bạn đã mua sắm tại Veggie.',
                    margin: [0, 18, 0, 0],
                    italics: true,
                }
            ],
            styles: {
                brand: {
                    fontSize: 18,
                    bold: true,
                    color: '#2f6d3a',
                    alignment: 'center'
                },
                title: {
                    fontSize: 15,
                    bold: true,
                    alignment: 'center',
                    margin: [0, 2, 0, 18]
                },
                sectionTitle: {
                    fontSize: 11,
                    bold: true,
                    color: '#2f6d3a',
                    margin: [0, 0, 0, 4]
                },
                tableHeader: {
                    bold: true,
                    fillColor: '#eef4ef'
                }
            },
            defaultStyle: {
                fontSize: 10
            }
        };

        pdfMake.createPdf(docDefinition).download('hoa-don-don-hang-' + order.id + '.pdf');
    }

    function generateAndPrintInvoice(order) {
        const currencyFormatter = new Intl.NumberFormat('vi-VN');

        const paymentMethodLabel = {
            cash: 'Thanh toán khi nhận hàng',
            cod: 'Thanh toán khi nhận hàng',
            vnpay: 'VNPAY',
            paypal: 'PayPal'
        }[order.payment_method] || 'Chưa cập nhật';

        const statusLabel = {
            pending: 'Chờ xác nhận',
            processing: 'Đang xử lý',
            shipped: 'Đang giao',
            completed: 'Hoàn thành',
            cancelled: 'Đã hủy',
            canceled: 'Đã hủy'
        }[order.status] || order.status;

        const rows = order.items.map(function (item, index) {
            return [
                { text: String(index + 1), alignment: 'center' },
                item.name,
                { text: String(item.quantity), alignment: 'center' },
                { text: currencyFormatter.format(item.price) + ' đ', alignment: 'right' },
                { text: currencyFormatter.format(item.total) + ' đ', alignment: 'right' },
            ];
        });

        const subTotal = Number(order.sub_total || order.total_price || 0);
        const discountAmount = Number(order.discount_amount || 0);
        const summaryRows = [
            [
                { text: 'Tạm tính', colSpan: 4, alignment: 'right', bold: true },
                {},
                {},
                {},
                { text: currencyFormatter.format(subTotal) + ' đ', alignment: 'right', bold: true },
            ]
        ];
        if (discountAmount > 0) {
            summaryRows.push([
                { text: 'Giảm giá' + (order.coupon_code ? ' (' + order.coupon_code + ')' : ''), colSpan: 4, alignment: 'right', color: '#2f6d3a', bold: true },
                {},
                {},
                {},
                { text: '-' + currencyFormatter.format(discountAmount) + ' đ', alignment: 'right', color: '#2f6d3a', bold: true },
            ]);
        }
        summaryRows.push([
            { text: 'Tổng cộng', colSpan: 4, alignment: 'right', bold: true },
            {},
            {},
            {},
            { text: currencyFormatter.format(order.total_price) + ' đ', alignment: 'right', bold: true },
        ]);

        const docDefinition = {
            pageSize: 'A4',
            pageMargins: [40, 48, 40, 48],
            content: [
                { text: 'VEGGIE', style: 'brand' },
                { text: 'HÓA ĐƠN BÁN HÀNG', style: 'title' },
                {
                    columns: [
                        [
                            { text: 'Thông tin đơn hàng', style: 'sectionTitle' },
                            { text: 'Mã đơn: #' + order.id },
                            { text: 'Ngày đặt: ' + (order.created_at || '-') },
                            { text: 'Trạng thái: ' + statusLabel },
                        ],
                        [
                            { text: 'Thông tin thanh toán', style: 'sectionTitle' },
                            { text: 'Phương thức: ' + paymentMethodLabel },
                            { text: 'Trạng thái: ' + (order.payment_status_label || 'Chưa cập nhật') },
                        ]
                    ],
                    columnGap: 24,
                    margin: [0, 0, 0, 16]
                },
                {
                    columns: [
                        [
                            { text: 'Người nhận', style: 'sectionTitle' },
                            { text: order.shipping ? order.shipping.full_name : 'Chưa có thông tin' },
                            { text: order.shipping ? order.shipping.phone : '' },
                            { text: order.shipping ? (order.shipping.address + ', ' + order.shipping.city) : '' },
                        ],
                        [
                            { text: 'Cửa hàng', style: 'sectionTitle' },
                            { text: 'Veggie' },
                            { text: 'Hotline: 0900 000 000' },
                            { text: 'Email: support@veggie.local' },
                        ]
                    ],
                    columnGap: 24,
                    margin: [0, 0, 0, 18]
                },
                {
                    table: {
                        headerRows: 1,
                        widths: [30, '*', 50, 85, 85],
                        body: [
                            [
                                { text: '#', style: 'tableHeader' },
                                { text: 'Sản phẩm', style: 'tableHeader' },
                                { text: 'SL', style: 'tableHeader' },
                                { text: 'Đơn giá', style: 'tableHeader' },
                                { text: 'Thành tiền', style: 'tableHeader' },
                            ],
                            ...rows,
                            ...summaryRows
                        ]
                    },
                    layout: 'lightHorizontalLines'
                },
                {
                    text: 'Cảm ơn bạn đã mua sắm tại Veggie.',
                    margin: [0, 18, 0, 0],
                    italics: true,
                }
            ],
            styles: {
                brand: {
                    fontSize: 18,
                    bold: true,
                    color: '#2f6d3a',
                    alignment: 'center'
                },
                title: {
                    fontSize: 15,
                    bold: true,
                    alignment: 'center',
                    margin: [0, 2, 0, 18]
                },
                sectionTitle: {
                    fontSize: 11,
                    bold: true,
                    color: '#2f6d3a',
                    margin: [0, 0, 0, 4]
                },
                tableHeader: {
                    bold: true,
                    fillColor: '#eef4ef'
                }
            },
            defaultStyle: {
                fontSize: 10
            }
        };

        pdfMake.createPdf(docDefinition).print();
    }

    // Order Detail Form Submit Confirmation
    const detailForms = document.querySelectorAll('form[action*="update-status"]');
    detailForms.forEach(function(form) {
        if (form.querySelector('input[name="_method"][value="PUT"]')) {
            form.addEventListener('submit', function (event) {
                const select = form.querySelector('select[name="status"]');
                const selectedLabel = select ? select.options[select.selectedIndex].text : 'trạng thái mới';
                if (!confirm('Bạn có chắc muốn cập nhật trạng thái sang: ' + selectedLabel + '?')) {
                    event.preventDefault();
                }
            });
        }
    });

    // Order List Status Update with SweetAlert
    document.querySelectorAll('.update-order-status-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const select = form.querySelector('select[name="status"]');
            const selectedLabel = select ? select.options[select.selectedIndex].text : 'trạng thái mới';

            Swal.fire({
                title: 'Cập nhật trạng thái đơn?',
                text: 'Đơn hàng sẽ được đổi sang: ' + selectedLabel,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Cập nhật',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#2a6edb'
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

});

            const select = form.querySelector('select[name="status"]');
            const selectedLabel = select ? select.options[select.selectedIndex].text : 'trạng thái mới';

            Swal.fire({
                title: 'Cập nhật trạng thái đơn?',
                text: 'Đơn hàng sẽ được đổi sang: ' + selectedLabel,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Cập nhật',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#2a6edb'
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });



    // -----------------Manager:contact--------------
    // *************************************************

});