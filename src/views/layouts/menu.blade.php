
<hr class="sidebar-divider my-0">
<li class="nav-item

        {{ (request()->is('cms/fin/report/monthly*')) ? 'active' : '' }}

">
<div>
<a class="nav-link text-white" href="/cms/fin/report/monthly" >
          <i class="fas fa-tachometer-alt"></i>
          <span>DASHBOARD</span>
        </a>
<div>
</li>
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
            
            <a class="collapse-item {{ (request()->is('cms/closeout*')) ? 'active' : '' }}" href="{{ route('route_tourcms_closeout_v2.index') }}"><i class="far fa-circle"></i> {{ __('Close Out') }}</a>
            @endif
            
            
           
          </div>
        </div>
      </li>
<!-- ##################################################################### -->
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

<!-- ##################################################################### -->
      <hr class="sidebar-divider my-0">
      <li class="nav-item 
      
        
        {{ (request()->is('cms/product*')) ? 'active' : '' }}
        {{ (request()->is('cms/category*')) ? 'active' : '' }}
        {{ (request()->is('cms/channel*')) ? 'active' : '' }}
        {{ (request()->is('cms/voucher*')) ? 'active' : '' }}
        {{ (request()->is('cms/page*')) ? 'active' : '' }}
        {{ (request()->is('cms/review*')) ? 'active' : '' }}
        

      ">
      @php
        $collapsed = 'collapsed';
        $show = '';        
        if(request()->is('cms/product*') || request()->is('cms/category*') || request()->is('cms/channel*') || request()->is('cms/voucher*') || request()->is('cms/page*') || request()->is('cms/review*'))
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

          </div>
        </div>
      </li>
      
<!-- ##################################################################### -->

<!-- ##################################################################### -->

<!-- ##################################################################### -->
      <!-- hr class="sidebar-divider my-0">
      <li class="nav-item 
      
        {{ (request()->is('cms/fin/report/monthly*')) ? 'active' : '' }}
        {{ (request()->is('cms/fin/report/payment*')) ? 'active' : '' }}
      
      ">
      @php
        $collapsed = 'collapsed';
        $show = '';        
        if(request()->is('cms/fin/report/monthly*') || request()->is('cms/fin/report/payment*'))
        {
          $collapsed = '';
          $show = 'show';
        }
      @endphp
        <a class="nav-link {{$collapsed}}" href="#" data-toggle="collapse" data-target="#menu-fin" aria-expanded="false" aria-controls="menu-fin">
          <i class="fas fa-chart-line"></i>
          <span>REPORT</span>
        </a>
        <div id="menu-fin" class="collapse {{$show}}" aria-labelledby="heading1" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            <a class="collapse-item {{ (request()->is('cms/fin/report/monthly*')) ? 'active' : '' }}" href="{{ route('route_fin_report_monthly.index') }}"><i class="far fa-circle"></i> {{ __('Monthly Report') }}</a>
            <a class="collapse-item {{ (request()->is('cms/fin/report/payment*')) ? 'active' : '' }}" href="{{ route('route_fin_report_payment.index') }}"><i class="far fa-circle"></i> {{ __('Payment Report') }}</a>

             
          </div>
        </div>
      </li -->
      
 <!-- ##################################################################### -->
 <!-- ##################################################################### -->
      <!-- hr class="sidebar-divider my-0">
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
      </li -->
<!-- ##################################################################### -->
 <!-- ##################################################################### -->
      <hr class="sidebar-divider my-0">
      <li class="nav-item 
      
        
        
        {{ (request()->is('cms/fin/profitloss*')) ? 'active' : '' }}
        {{ (request()->is('cms/fin/tax*')) ? 'active' : '' }}
        {{ (request()->is('cms/fin/transactions*')) ? 'active' : '' }}
        {{ (request()->is('cms/fin/neraca*')) ? 'active' : '' }}
        {{ (request()->is('cms/fin/orders*')) ? 'active' : '' }}
      
      ">
      @php
        $collapsed = 'collapsed';
        $show = '';        
        if(request()->is('cms/fin/profitloss*') || request()->is('cms/fin/tax*') || request()->is('cms/fin/transactions*') || request()->is('cms/fin/neraca*') || request()->is('cms/fin/orders*'))
        {
          $collapsed = '';
          $show = 'show';
        }
      @endphp
        <a class="nav-link {{$collapsed}}" href="#" data-toggle="collapse" data-target="#menu-fin1" aria-expanded="false" aria-controls="menu-fin1">
          <i class="fas fa-balance-scale"></i>
          <span>ACCOUNTING</span>
        </a>
        <div id="menu-fin1" class="collapse {{$show}}" aria-labelledby="heading1" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            

             <a class="collapse-item {{ (request()->is('cms/fin/transactions*')) ? 'active' : '' }}" href="{{ route('route_fin_transactions.index') }}"><i class="far fa-circle"></i> {{ __('Transaction') }}</a>
            
             <a class="collapse-item {{ (request()->is('cms/fin/tax*')) ? 'active' : '' }}" href="{{ route('route_fin_tax.index') }}"><i class="far fa-circle"></i> {{ __('Tax') }}</a>

             <a class="collapse-item {{ (request()->is('cms/fin/profitloss*')) ? 'active' : '' }}" href="{{ route('route_fin_profitloss.index') }}"><i class="far fa-circle"></i> {{ __('Profit Loss') }}</a>

             <a class="collapse-item {{ (request()->is('cms/fin/neraca*')) ? 'active' : '' }}" href="{{ route('route_fin_neraca.index') }}"><i class="far fa-circle"></i> {{ __('Balance Sheet') }}</a>

             <a class="collapse-item {{ (request()->is('cms/fin/orders*')) ? 'active' : '' }}" href="{{ route('route_tourcms_orders.index') }}"><i class="far fa-circle"></i> {{ __('Orders') }}</a>

            
          </div>
        </div>
      </li>
      
 <!-- ##################################################################### -->
 

 