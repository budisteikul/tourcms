<!-- ##################################################################### -->
      <hr class="sidebar-divider my-0">
      <li class="nav-item 
      
        {{ (request()->is('cms/partner*')) ? 'active' : '' }}
      
      ">
      @php
        $collapsed = 'collapsed';
        $show = '';        
        if(request()->is('cms/partner*'))
        {
          $collapsed = '';
          $show = 'show';
        }
      @endphp
        <a class="nav-link {{$collapsed}}" href="#" data-toggle="collapse" data-target="#menu-1" aria-expanded="true" aria-controls="menu-1">
          <i class="fas fa-handshake"></i>
          <span>PARTNER</span>
        </a>
        <div id="menu-1" class="collapse {{$show}}" aria-labelledby="heading1" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            
            <a class="collapse-item {{ (request()->is('cms/partner/report*')) ? 'active' : '' }}" href="{{ route('route_tourcms_partner.index') }}/report"><i class="far fa-circle"></i> {{ __('Report') }}</a>

            

           
          </div>
        </div>
      </li>
<!-- ##################################################################### -->
<!-- ##################################################################### -->
      <hr class="sidebar-divider my-0">
      <li class="nav-item 
      
       
        {{ (request()->is('cms/schedule*')) ? 'active' : '' }}
        {{ (request()->is('cms/completed*')) ? 'active' : '' }}
        {{ (request()->is('cms/closeout*')) ? 'active' : '' }}
      
      ">
      @php
        $collapsed = 'collapsed';
        $show = '';        
        if(request()->is('cms/closeout*') || request()->is('cms/schedule*') || request()->is('cms/completed*'))
        {
          $collapsed = '';
          $show = 'show';
        }
      @endphp
        <a class="nav-link {{$collapsed}}" href="#" data-toggle="collapse" data-target="#menu-2" aria-expanded="true" aria-controls="menu-2">
          <i class="far fa-calendar-alt"></i>
          <span>SCHEDULE</span>
        </a>
        <div id="menu-2" class="collapse {{$show}}" aria-labelledby="heading1" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            

            <a class="collapse-item {{ (request()->is('cms/schedule*')) ? 'active' : '' }}" href="{{ route('route_tourcms_schedule.index') }}"><i class="far fa-circle"></i> {{ __('Upcoming') }}</a>

            @if(Auth::user()->id==1)
            <a class="collapse-item {{ (request()->is('cms/completed*')) ? 'active' : '' }}" href="{{ route('route_tourcms_completed.index') }}"><i class="far fa-circle"></i> {{ __('Finished') }}</a>
            
            <a class="collapse-item {{ (request()->is('cms/closeout*')) ? 'active' : '' }}" href="{{ route('route_tourcms_closeout.index') }}"><i class="far fa-circle"></i> {{ __('Close Out') }}</a>
            @endif
            
            
           
          </div>
        </div>
      </li>
<!-- ##################################################################### -->
@if(Auth::user()->id==1)
<!-- ##################################################################### -->
      <hr class="sidebar-divider my-0">
      <li class="nav-item 
      
        {{ (request()->is('cms/booking*')) ? 'active' : '' }}
        {{ (request()->is('cms/cancel*')) ? 'active' : '' }}

      ">
      @php
        $collapsed = 'collapsed';
        $show = '';        
        if(request()->is('cms/booking*') ||  request()->is('cms/cancel*'))
        {
          $collapsed = '';
          $show = 'show';
        }
      @endphp
        <a class="nav-link {{$collapsed}}" href="#" data-toggle="collapse" data-target="#menu-3" aria-expanded="false" aria-controls="menu-3">
          <i class="fas fa-shopping-cart"></i>
          <span>ORDER</span>
        </a>
        <div id="menu-3" class="collapse {{$show}}" aria-labelledby="heading1" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            <a class="collapse-item {{ (request()->is('cms/booking*')) ? 'active' : '' }}" href="{{ route('route_tourcms_booking.index') }}"><i class="far fa-circle"></i> {{ __('Booking') }}</a>

            <a class="collapse-item {{ (request()->is('cms/cancel*')) ? 'active' : '' }}" href="{{ route('route_tourcms_cancel.index') }}"><i class="far fa-circle"></i> {{ __('Refund') }}</a>

          </div>
        </div>
      </li>
      
<!-- ##################################################################### -->
@endif
@if(Auth::user()->id==1)
<!-- ##################################################################### -->
      <hr class="sidebar-divider my-0">
      <li class="nav-item 
      
        
        {{ (request()->is('cms/product*')) ? 'active' : '' }}
        {{ (request()->is('cms/category*')) ? 'active' : '' }}
        {{ (request()->is('cms/channel*')) ? 'active' : '' }}
        {{ (request()->is('cms/voucher*')) ? 'active' : '' }}
        {{ (request()->is('cms/page*')) ? 'active' : '' }}
        {{ (request()->is('cms/review*')) ? 'active' : '' }}
        {{ (request()->is('cms/setting*')) ? 'active' : '' }}
        

      ">
      @php
        $collapsed = 'collapsed';
        $show = '';        
        if(request()->is('cms/setting*') || request()->is('cms/product*') || request()->is('cms/category*') || request()->is('cms/channel*') || request()->is('cms/voucher*') || request()->is('cms/page*') || request()->is('cms/review*'))
        {
          $collapsed = '';
          $show = 'show';
        }
      @endphp
        <a class="nav-link {{$collapsed}}" href="#" data-toggle="collapse" data-target="#menu-4" aria-expanded="false" aria-controls="menu-4">
          <i class="fas fa-globe-asia"></i>
          <span>WEBSITE</span>
        </a>
        <div id="menu-4" class="collapse {{$show}}" aria-labelledby="heading1" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
           

           
            <a class="collapse-item {{ (request()->is('cms/product*')) ? 'active' : '' }}" href="{{ route('route_tourcms_product.index') }}"><i class="far fa-circle"></i> {{ __('Product') }}</a>
            
            <a class="collapse-item {{ (request()->is('cms/category*')) ? 'active' : '' }}" href="{{ route('route_tourcms_category.index') }}"><i class="far fa-circle"></i> {{ __('Category') }}</a>
            
            <a class="collapse-item {{ (request()->is('cms/channel*')) ? 'active' : '' }}" href="{{ route('route_tourcms_channel.index') }}"><i class="far fa-circle"></i> {{ __('Channel') }}</a>

            <a class="collapse-item {{ (request()->is('cms/voucher*')) ? 'active' : '' }}" href="{{ route('route_tourcms_voucher.index') }}"><i class="far fa-circle"></i> {{ __('Voucher') }}</a>

           <a class="collapse-item {{ (request()->is('cms/page*')) ? 'active' : '' }}" href="{{ route('route_tourcms_page.index') }}"><i class="far fa-circle"></i> {{ __('Page') }}</a>
            
            <a class="collapse-item {{ (request()->is('cms/review*')) ? 'active' : '' }}" href="{{ route('route_tourcms_review.index') }}"><i class="far fa-circle"></i> {{ __('Review') }}</a>

            <a class="collapse-item {{ (request()->is('cms/setting*')) ? 'active' : '' }}" href="{{ route('route_tourcms_setting.index') }}"><i class="far fa-circle"></i> {{ __('Setting') }}</a>

          </div>
        </div>
      </li>
      
<!-- ##################################################################### -->
@endif
<!-- ##################################################################### -->
 