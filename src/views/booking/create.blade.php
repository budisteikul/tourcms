@inject('ImageHelper', 'budisteikul\toursdk\Helpers\ImageHelper')
<div class="h-100" style="width:99%">		
 
    <div class="row justify-content-center">
        <div class="col-md-12 pr-0 pl-0 pt-0 pb-0">
            <div class="row">
            @foreach($products as $product)
             <div class="col-lg-2 col-md-4 mb-2 d-flex">
                <div class="card shadow card-block rounded flex-fill">
                    <div class="container-book">
                        @php
                            $cover = $ImageHelper->cover($product);
                        @endphp
                        <img src="{{ $cover }}" alt="{{ $product->name }}" class="card-img-top image-book">
                    </div>
                    <div class="card-body mb-0 pb-0">
                        <h5 class="card-title">{{ $product->name }}</h5>
                    </div>
                    <a href="{{route('route_tourcms_booking.index')}}/{{ $product->bokun_id }}/edit" class="btn btn-primary">BOOK NOW</a>
                </div>
            </div>
		    @endforeach
            </div>
        		
        </div>
    </div>

</div>