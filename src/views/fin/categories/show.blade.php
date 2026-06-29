@inject('AccHelper', 'budisteikul\tourcms\Helpers\AccHelper')
@extends('coresdk::layouts.input-form',["mainTitle" => "Category Structure"])
@section('content')

        


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

@endsection
