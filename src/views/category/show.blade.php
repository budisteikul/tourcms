@inject('Category', 'budisteikul\tourcms\Helpers\CategoryHelper')
@extends('coresdk::layouts.input-form',["mainTitle" => "Category Structure"])
@section('content')

        


<div class="tree">
<ul>
@foreach($root_categories as $root_category)
  <li class="parent_li">
    <span><b>{{ $root_category->name }}</b></span>
    @if(@count($root_category->child))
      {{ $Category->structure($root_category->id) }}
    @endif
  </li>
@endforeach
</ul>     
</div>

@endsection