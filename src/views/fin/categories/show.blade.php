@inject('AccHelper', 'budisteikul\tourcms\Helpers\AccHelper')
<div class="h-100" style="width:99%">     

    <div class="row justify-content-center">
        <div class="col-md-12 pr-0 pl-0 pt-0 pb-0">
             <div class="card">
                <div class="card-header pr-0">
                <div class="row align-items-center w-100">
                    <div class="col text-left">
                        <div class="d-flex align-self-center">
                        Categories Structure
                        </div>
                    </div>
                    <div class="col-auto text-right mr-0 pr-0">
                        <div class="btn-toolbar justify-content-end">
                            <button class="btn btn-sm btn-danger mr-0" type="button" onClick="$.fancybox.close();"><i class="fa fa-window-close"></i> Close</button>
                        </div>
                    </div>
                </div>
                </div>
                <div class="card-body">
        


<div class="tree">
<ul>
@foreach($root_categories as $root_category)
  <li class="parent_li">
    <span><b>{{ $root_category->name }}</b></span>
    @if(@count($root_category->child))
      {{ $AccHelper->structure($root_category->id) }}
    @endif
  </li>
@endforeach
</ul>     
</div>



</div>
</div>       




        
        </div>

    </div>

</div>