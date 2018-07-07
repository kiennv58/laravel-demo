<ul class="list-nullstyled">
    @foreach($childs as $child)
        <li>
            <span class="bbc_tree__title">
                {{ $child->name }}
            </span>
            <button type="button" class="btn btn-box-tool pull-right delete-btn" data-href='{{ route('categories.destroy', $child->id) }}'" data-toggle="tooltip" title="" data-original-title="Xóa">
                <i class="fa fa-times"></i>
            </button>
            <button type="button" class="btn btn-box-tool pull-right" onclick="location.href='{{ route('categories.edit', $child->id) }}'" data-toggle="tooltip" title="" data-original-title="Sửa">
                <i class="fa fa-pencil"></i>
            </button>
            @if(count($child->childs))
                @include($viewPath . 'tree', ['childs' => $child->childs])
            @endif
        </li>
    @endforeach
</ul>
