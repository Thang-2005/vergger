 <div class="ltn__modal-area ltn__quick-view-modal-area">
     <div class="modal fade" id="quick_view_modal-{{ $product->id }}" tabindex="-1">
         <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                         <!-- <i class="fas fa-times"></i> -->
                     </button>
                 </div>
                 <div class="modal-body">
                     <div class="ltn__quick-view-modal-inner">
                         <div class="modal-product-item">
                             <div class="row">
                                 <div class="col-lg-6 col-12">
                                     <div class="modal-product-img">
                                         <img src="{{ $product->image_url }}">
                                     </div>
                                 </div>
                                 <div class="col-lg-6 col-12">
                                     <div class="modal-product-info">
                                         <div class="product-ratting">
                                             <ul>
                                                 <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                 <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                 <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                 <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                 <li><a href="#"><i class="far fa-star"></i></a></li>
                                                 <li class="review-total"> <a href="#"> ( 95 Reviews )</a></li>
                                             </ul>
                                         </div>
                                         <h3>{{ $product->name }}</h3>
                                         <div class="product-price">
                                             <span>${{ number_format($product->price, 2, '.', ',') }}Đ</span>
                                             <del>${{ number_format($product->original_price, 2, '.', ',') }}Đ</del>
                                         </div>
                                         <div class="modal-product-meta ltn__product-details-menu-1">
                                             <ul>
                                                 <li>
                                                     <strong>{{ __('messages.category') }}:</strong>
                                                     <span>
                                                         <a href="javascript:void(0)">{{ $product->category->name }}</a>
                                                     </span>
                                                 </li>

                                             </ul>
                                         </div>
                                         <div class="ltn__product-details-menu-2">
                                             <ul>
                                                 <li>
                                                     <strong>Số lượng:</strong>
                                                 </li>
                                                 <li>
                                                     <div class="cart-plus-minus">
                                                         <input type="number" value="1" min="1" name="qtybutton"
                                                             class="cart-plus-minus-box">
                                                     </div>
                                                 </li>

                                                 <li>
                                                     <a href="#" class="theme-btn-1 btn btn-effect-1"
                                                         title="{{ __('messages.add_product') }} vào giỏ hàng" data-bs-toggle="modal"
                                                         data-bs-target="#add_to_cart_modal-{{ $product->id }}">
                                                         <i class="fas fa-shopping-cart"></i>
                                                         <span>{{ __('messages.add_product') }} vào giỏ hàng</span>
                                                     </a>
                                                 </li>
                                             </ul>
                                         </div>
                                         <div class="ltn__product-details-menu-3">
                                             <ul>
                                                 <li>
                                                     <a href="#" class="btn-add-wishlist" title="Wishlist" data-bs-toggle="modal"
                                                         data-bs-target="#liton_wishlist_modal-{{ $product->id }}" data-product-id="{{ $product->id }}">
                                                         <i class="far fa-heart"></i>
                                                         <span>{{ __('messages.add_product') }} vào danh sách yêu thích</span>
                                                     </a>
                                                 </li>
                                             </ul>
                                         </div>
                                         <hr>
                                         <div class="ltn__social-media">
                                             <ul>
                                                 <li>Chia sẻ:</li>
                                                 <li><a href="#" title="Facebook"><i
                                                             class="fab fa-facebook-f"></i></a></li>
                                                 <li><a href="#" title="Twitter"><i
                                                             class="fab fa-twitter"></i></a></li>
                                                 <li><a href="#" title="Linkedin"><i
                                                             class="fab fa-linkedin"></i></a></li>
                                                 <li><a href="#" title="Instagram"><i
                                                             class="fab fa-instagram"></i></a></li>

                                             </ul>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>