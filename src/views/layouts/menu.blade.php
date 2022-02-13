<!-- ##################################################################### -->
      <hr class="sidebar-divider my-0">
      <li class="nav-item 
      
        {{ (request()->is('cms/product*')) ? 'active' : '' }}
        {{ (request()->is('cms/category*')) ? 'active' : '' }}
        {{ (request()->is('cms/channel*')) ? 'active' : '' }}
      
      ">
      @php
        $collapsed = 'collapsed';
        $show = '';        
        if(request()->is('cms/product*') || request()->is('cms/category*') || request()->is('cms/channel*') || request()->is('cms/vendor*'))
        {
          $collapsed = '';
          $show = 'show';
        }
      @endphp
        <a class="nav-link {{$collapsed}}" href="#" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
          <i class="fas fa-tag"></i>
          <span>LIBRARY</span>
        </a>
        <div id="collapse1" class="collapse {{$show}}" aria-labelledby="heading1" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            <a class="collapse-item {{ (request()->is('cms/product*')) ? 'active' : '' }}" href="{{ route('route_tourcms_product.index') }}"><i class="far fa-circle"></i> {{ __('Product') }}</a>
            
            <a class="collapse-item {{ (request()->is('cms/category*')) ? 'active' : '' }}" href="{{ route('route_tourcms_category.index') }}"><i class="far fa-circle"></i> {{ __('Category') }}</a>
            
            <a class="collapse-item {{ (request()->is('cms/channel*')) ? 'active' : '' }}" href="{{ route('route_tourcms_channel.index') }}"><i class="far fa-circle"></i> {{ __('Channel') }}</a>

            <a class="collapse-item {{ (request()->is('cms/vendor*')) ? 'active' : '' }}" href="{{ route('route_tourcms_vendor.index') }}"><i class="far fa-circle"></i> {{ __('Vendor') }}</a>
            
           
          </div>
        </div>
      </li>
      <!-- ##################################################################### -->
      <hr class="sidebar-divider my-0">
      <li class="nav-item 
      
        {{ (request()->is('cms/booking*')) ? 'active' : '' }}
      
      ">
      @php
        $collapsed = 'collapsed';
        $show = '';        
        if(request()->is('cms/booking*'))
        {
          $collapsed = '';
          $show = 'show';
        }
      @endphp
        <a class="nav-link {{$collapsed}}" href="#" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
          <i class="fas fa-shopping-cart"></i>
          <span>ORDER</span>
        </a>
        <div id="collapse2" class="collapse {{$show}}" aria-labelledby="heading1" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            <a class="collapse-item {{ (request()->is('cms/booking*')) ? 'active' : '' }}" href="{{ route('route_tourcms_booking.index') }}"><i class="far fa-circle"></i> {{ __('Booking') }}</a>
            
           
          </div>
        </div>
      </li>
      <!-- ##################################################################### -->
      <hr class="sidebar-divider my-0">
      <li class="nav-item 
      
        {{ (request()->is('cms/review*')) ? 'active' : '' }}
      
      ">
      @php
        $collapsed = 'collapsed';
        $show = '';        
        if(request()->is('cms/review*'))
        {
          $collapsed = '';
          $show = 'show';
        }
      @endphp
        <a class="nav-link {{$collapsed}}" href="#" data-toggle="collapse" data-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
          <i class="fas fa-comment"></i>
          <span>FEEDBACK</span>
        </a>
        <div id="collapse3" class="collapse {{$show}}" aria-labelledby="heading1" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            <a class="collapse-item {{ (request()->is('cms/review*')) ? 'active' : '' }}" href="{{ route('route_tourcms_review.index') }}"><i class="far fa-circle"></i> {{ __('Review') }}</a>
            
           
          </div>
        </div>
      </li>

 <!-- ##################################################################### -->

 <hr class="sidebar-divider my-0">
      <li class="nav-item 
      
        {{ (request()->is('cms/page*')) ? 'active' : '' }}
      
      ">
      @php
        $collapsed = 'collapsed';
        $show = '';        
        if(request()->is('cms/page*'))
        {
          $collapsed = '';
          $show = 'show';
        }
      @endphp
        <a class="nav-link {{$collapsed}}" href="#" data-toggle="collapse" data-target="#collapse4" aria-expanded="true" aria-controls="collapse4">
          <i class="fas fa-globe-asia"></i>
          <span>WEBSITE</span>
        </a>
        <div id="collapse4" class="collapse {{$show}}" aria-labelledby="heading1" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            <a class="collapse-item {{ (request()->is('cms/page*')) ? 'active' : '' }}" href="{{ route('route_tourcms_page.index') }}"><i class="far fa-circle"></i> {{ __('Page') }}</a>
            
           
          </div>
        </div>
      </li>

      <!-- ##################################################################### -->