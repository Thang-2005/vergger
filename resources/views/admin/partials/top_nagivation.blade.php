 <div class="top_nav">
     <div class="nav_menu">
         <div class="nav toggle">
             <a id="menu_toggle"><i class="fa fa-bars"></i></a>
         </div>
         <nav class="nav navbar-nav">
             <ul class=" navbar-right">
                 <li class="nav-item" style="padding-left: 10px; padding-right: 10px;">
                     @include('components.language-switcher')
                 </li>

                 <li class="nav-item dropdown open" style="padding-left: 15px;">
                     <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                         <img src="{{ Auth::guard('admin')->user()->avatar ?? asset('asset/admin/build/images/user.png') }}" alt="">
                         @auth('admin')
                             {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
                         @endauth
                     </a>
                     <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                         <a class="dropdown-item" href="{{ route('admin.profile') }}">{{ __('messages.profile') }}</a>
                         <a class="dropdown-item" href="javascript:;">
                             <span class="badge bg-red pull-right">50%</span>
                             <span>{{ __('messages.settings') }}</span>
                         </a>
                         <a class="dropdown-item" href="javascript:;">{{ __('messages.help') }}</a> 
                         @php $logoutConfirm = __('messages.confirm_logout'); @endphp
                         <a class="dropdown-item" href="{{ route('admin.logout')}}" onclick="return confirm('{{ $logoutConfirm }}');">
                             <i class="fa fa-sign-out pull-right"></i> {{ __('messages.logout') }}    
                         </a>
                         
                     </div>
                 </li>

                 <li role="presentation" class="nav-item dropdown open">
                     <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1" data-toggle="dropdown" aria-expanded="false" title="{{ __('messages.contact_inbox') }}">
                         <i class="fa fa-envelope-o"></i>
                         @php
                             $unrepliedCount = \App\Models\Contact::where('is_Reply', 0)->count();
                         @endphp
                         <span class="badge bg-{{ $unrepliedCount > 0 ? 'red' : 'green' }}">{{ $unrepliedCount }}</span>
                     </a>
                     <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1">
                         @php
                             $unrepliedContacts = \App\Models\Contact::where('is_Reply', 0)
                                 ->orderBy('created_at', 'desc')
                                 ->limit(5)
                                 ->get();
                         @endphp
                         @forelse($unrepliedContacts as $contact)
                             <li>
                                 <a href="{{ route('admin.contacts.show', $contact->id) }}" class="msg_item">
                                     <span class="user-img" style="background-color: #3498db; color: white; width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 10px;">
                                         {{ strtoupper(substr($contact->full_name, 0, 1)) }}
                                     </span>
                                     <span class="msg_body">
                                         <strong>{{ $contact->full_name }}</strong>
                                         <br>
                                         <span class="time" style="font-size: 12px; color: #999;">{{ $contact->created_at->diffForHumans() }}</span>
                                         <br>
                                         <span style="font-size: 12px; color: #666;">{{ Str::limit($contact->message, 50) }}</span>
                                     </span>
                                 </a>
                             </li>
                         @empty
                             <li>
                                 <span class="msg_item p-3" style="text-align: center; color: #999;">{{ __('messages.no_messages') }}</span>
                             </li>
                         @endforelse
                         @if($unrepliedCount > 0)
                             <li style="border-top: 1px solid #eee; padding-top: 10px;">
                                 <a href="{{ route('admin.contacts.index') }}" class="msg_item" style="text-align: center; padding: 10px;">
                                     <strong>{{ __('messages.all_contacts') }}</strong>
                                 </a>
                             </li>
                         @endif
                     </ul>
                 </li>

             </ul>
         </nav>
     </div>
 </div>
