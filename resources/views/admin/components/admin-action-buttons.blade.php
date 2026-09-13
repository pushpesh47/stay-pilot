

    {{-- EDIT --}}
    @isset($permissions['edit'])
        @can($permissions['edit'])
            <a href="{{ isset($routes['edit'])
                    ? route($routes['edit'], encrypt($model->id))
                    : 'javascript:void(0)' }}"
               class="edit-record"
               data-id="{{ encrypt($model->id) }}"
               title="Edit">
                <i class="fa fa-edit"></i>
            </a>
        @endcan
    @endisset
    

    {{-- STATUS --}}
    @isset($permissions['status'])
        @can($permissions['status'])
            <a href="javascript:void(0)"
               class="toggle-status"
               data-id="{{ encrypt($model->id) }}"
               data-status="{{ $model->status }}"
               data-url="{{ route($routes['status']) }}"
               data-tableid="{{ $tableId }}">
                <i class="fa {{ $model->status == 'active'
                    ? 'fa-user-slash text-danger'
                    : 'fa-user-check text-success' }}"></i>
            </a>
        @endcan
    @endisset

    {{-- DELETE --}}
    @isset($permissions['delete'])
        @can($permissions['delete'])
            <button class="btn btn-danger btn-xs delete-record"
                data-id="{{ encrypt($model->id) }}"
                data-url="{{ route($routes['delete'], encrypt($model->id)) }}"
                data-tableid="{{ $tableId }}" data-title="{{ $title }}">
                <i class="fa fa-trash"></i>
            </button>
        @endcan
    @endisset


   @if(!empty($extras))
    @foreach($extras as $btn)

        @php
            $permissionCheck = isset($btn['permission']) ? auth()->user()->can($btn['permission']) : true;
        @endphp

        @if($permissionCheck)
            <a href="{{ $btn['url'] }}" title="{{ $btn['title'] }}" class="btn btn-sm btn-light">
                @if(isset($btn['icon']))
                    <i class="{{ $btn['icon'] }}"></i>
                @endif
                @if(isset($btn['text']))
                    <span>{{ $btn['text'] }}</span>
                @endif
            </a>
        @endif

    @endforeach
@endif



