@extends('coresdk::layouts.app')
@section('content')
<script language="javascript">
function CREATE()
    {
        $.fancybox.open({
            type: 'ajax',
            src: '{{ route('route_tourcms_booking.create') }}',
            touch: false,
            modal: false,
        }); 
    }
</script>
<script src="{{ asset('assets/javascripts/widgets/687035c46b475965b2131d0e804b858e-widget-utils.js') }}"></script>
<div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">Create Booking for {{$contents->title}}</div>
                <div class="card-body">

<div class="widget-body" id="WidgetContent">
<div class="widget">
	<div id="ActivityBookingWidget"></div>
    <script>
    window.priceFormatter = new WidgetUtils.PriceFormatter({
        currency: '{{ $currency }}',
        language: '{{ $lang }}',
        decimalSeparator: '.',
        groupingSeparator: ',',
        symbol: '{{ $currency }}'
    });

	window.i18nLang = '{{ $lang }}';
    window.ActivityBookingWidgetConfig = {
        currency: '{{ $currency }}',
        language: '{{ $lang }}',
        embedded: {!! $embedded !!},
        priceFormatter: window.priceFormatter,
        invoicePreviewUrl: '/api/activity/invoice-preview',
        addToCartUrl: '/api/widget/cart/session/{{$sessionId}}/activity',
        calendarUrl: '/api/activity/{id}/calendar/json/{year}/{month}',
        activities: [],
        pickupPlaces: [],
        dropoffPlaces: [],
        showOnRequestMessage: false,
        showCalendar: true,
        showUpcoming: false,
        displayOrder: 'Calendar',
        selectedTab: 'all',
        hideExtras: false,
        showActivityList: false,
        showFewLeftWarning: false,
        warningThreshold: 10,
        displayStartTimeSelectBox: false,
        displayMessageAfterAddingToCart: false,
        defaultCategoryMandatory: true,
        defaultCategorySelected: true,
        affiliateCodeFromQueryString: true,
        affiliateParamName: 'trackingCode',
        affiliateCode: '',
        onAfterRender: function() {
            
        },
        onAvailabilitySelected: function(selectedRate, selectedDate, selectedAvailability) {
        },
        onAddedToCart: function(cart) {
                $.alert({
                    title: 'Info',
                    type: 'green',
                    content: 'The items have been added to your cart!',
                });
        },
        
        calendarMonth: {!!$month!!},
        calendarYear: {!!$year!!},
        loadingCalendar: true,
        
        activity: {!! json_encode($contents) !!},
        
        upcomingAvailabilities: [],
        
        firstDayAvailabilities: {!! json_encode($firstavailability) !!}
    }; 
    </script>
</div>
<div id="generic-loading-template" style="display:none">
	<div class="well well-large well-transparent lead">
		<i class="fa fa-spinner icon-spin icon-2x pull-left"></i> processing...
	</div>
</div>
</div>

                </div>

               
   <div class="row">
    <div class="col-sm">
      <a style="height:47px;" class="btn btn-lg btn-block btn-secondary text-white" onclick="CREATE(); return false;"><b class="fas fa-tag"></b> <b>CHANGE PRODUCT</b></a>
    </div>
    <div class="col-sm">
       <a style="height:47px;" class="btn btn-lg btn-block btn-success" href="{{route('route_tourcms_booking.index')}}/checkout"><i class="fas fa-shopping-cart"></i> <b>VIEW CART</b></a>
    </div>
  </div>
               
            </div>
        </div>
 </div>
@endsection
