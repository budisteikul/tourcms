<!-- ##################################################################### -->
      <hr class="sidebar-divider my-0">
      <li class="nav-item 
      
        {{ (request()->is('cms/booking*')) ? 'active' : '' }}
        {{ (request()->is('cms/schedule*')) ? 'active' : '' }}
        {{ (request()->is('cms/completed*')) ? 'active' : '' }}
        
      
      ">
      @php
        $collapsed = 'collapsed';
        $show = '';        
        if(request()->is('cms/booking*') || request()->is('cms/schedule*') || request()->is('cms/completed*'))
        {
          $collapsed = '';
          $show = 'show';
        }
      @endphp
        <a class="nav-link {{$collapsed}}" href="#" data-toggle="collapse" data-target="#menu-order" aria-expanded="true" aria-controls="menu-order">
          <i class="fas fa-shopping-cart"></i>
          <span>ORDER</span>
        </a>
        <div id="menu-order" class="collapse {{$show}}" aria-labelledby="heading1" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            <a class="collapse-item {{ (request()->is('cms/booking*')) ? 'active' : '' }}" href="{{ route('route_tourcms_booking.index') }}"><i class="far fa-circle"></i> {{ __('Booking') }}</a>

            <a class="collapse-item {{ (request()->is('cms/schedule*')) ? 'active' : '' }}" href="{{ route('route_tourcms_schedule.index') }}"><i class="far fa-circle"></i> {{ __('Tour Schedule') }}</a>

            <a class="collapse-item {{ (request()->is('cms/completed*')) ? 'active' : '' }}" href="{{ route('route_tourcms_completed.index') }}"><i class="far fa-circle"></i> {{ __('Tour Completed') }}</a>

            

            
            
            
           
          </div>
        </div>
      </li>
<!-- ##################################################################### -->
@if(Auth::user()->id==1)
<!-- ##################################################################### -->
      <hr class="sidebar-divider my-0">
      <li class="nav-item 
      
        {{ (request()->is('cms/product*')) ? 'active' : '' }}
        {{ (request()->is('cms/category*')) ? 'active' : '' }}
        {{ (request()->is('cms/channel*')) ? 'active' : '' }}
        {{ (request()->is('cms/voucher*')) ? 'active' : '' }}
        {{ (request()->is('cms/closeout*')) ? 'active' : '' }}
        {{ (request()->is('cms/page*')) ? 'active' : '' }}
        {{ (request()->is('cms/review*')) ? 'active' : '' }}

      ">
      @php
        $collapsed = 'collapsed';
        $show = '';        
        if(request()->is('cms/product*') || request()->is('cms/category*') || request()->is('cms/channel*') || request()->is('cms/voucher*') || request()->is('cms/closeout*') || request()->is('cms/page*') || request()->is('cms/review*'))
        {
          $collapsed = '';
          $show = 'show';
        }
      @endphp
        <a class="nav-link {{$collapsed}}" href="#" data-toggle="collapse" data-target="#menu-library" aria-expanded="false" aria-controls="menu-library">
          <i class="fas fa-globe-asia"></i>
          <span>WEBSITE</span>
        </a>
        <div id="menu-library" class="collapse {{$show}}" aria-labelledby="heading1" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            <a class="collapse-item {{ (request()->is('cms/product*')) ? 'active' : '' }}" href="{{ route('route_tourcms_product.index') }}"><i class="far fa-circle"></i> {{ __('Product') }}</a>
            
            <a class="collapse-item {{ (request()->is('cms/category*')) ? 'active' : '' }}" href="{{ route('route_tourcms_category.index') }}"><i class="far fa-circle"></i> {{ __('Category') }}</a>
            
            <a class="collapse-item {{ (request()->is('cms/channel*')) ? 'active' : '' }}" href="{{ route('route_tourcms_channel.index') }}"><i class="far fa-circle"></i> {{ __('Channel') }}</a>

            <a class="collapse-item {{ (request()->is('cms/voucher*')) ? 'active' : '' }}" href="{{ route('route_tourcms_voucher.index') }}"><i class="far fa-circle"></i> {{ __('Voucher') }}</a>
            
           <a class="collapse-item {{ (request()->is('cms/closeout*')) ? 'active' : '' }}" href="{{ route('route_tourcms_closeout.index') }}"><i class="far fa-circle"></i> {{ __('Close Out') }}</a>

           <a class="collapse-item {{ (request()->is('cms/page*')) ? 'active' : '' }}" href="{{ route('route_tourcms_page.index') }}"><i class="far fa-circle"></i> {{ __('Page') }}</a>
            
            <a class="collapse-item {{ (request()->is('cms/review*')) ? 'active' : '' }}" href="{{ route('route_tourcms_review.index') }}"><i class="far fa-circle"></i> {{ __('Review') }}</a>

          </div>
        </div>
      </li>
      
<!-- ##################################################################### -->
@endif
<!-- ##################################################################### -->
 