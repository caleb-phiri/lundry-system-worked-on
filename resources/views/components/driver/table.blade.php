{{-- Driver Table Component --}}
@props([
    'columns' => [],
    'data' => [],
    'emptyMessage' => 'No data available',
    'emptyIcon' => 'fas fa-inbox',
    'striped' => true,
    'hover' => true,
    'bordered' => false,
    'responsive' => true,
    'key' => 'id',
    'actions' => null,
])

<?php
$tableClasses = 'table';
if ($striped) $tableClasses .= ' table-striped';
if ($hover) $tableClasses .= ' table-hover';
if ($bordered) $tableClasses .= ' table-bordered';
?>

@if($responsive)
<div class="table-responsive">
@endif

<table class="{{ $tableClasses }}" {{ $attributes }}>
    @if(!empty($columns))
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th {{ isset($column['width']) ? 'style="width: '.$column['width'].'"' : '' }} 
                        {{ isset($column['class']) ? 'class="'.$column['class'].'"' : '' }}>
                        {{ $column['label'] ?? ucfirst($column['key'] ?? '') }}
                    </th>
                @endforeach
                @if($actions)
                    <th class="text-end" style="width: 120px;">Actions</th>
                @endif
            </tr>
        </thead>
    @endif
    
    <tbody>
        @forelse($data as $item)
            <tr {{ isset($key) ? 'data-key="'.$item->{$key}.'"' : '' }}>
                @foreach($columns as $column)
                    <td {{ isset($column['class']) ? 'class="'.$column['class'].'"' : '' }}>
                        @if(isset($column['render']))
                            {{ $column['render']($item) }}
                        @elseif(isset($column['key']))
                            {{ $item->{$column['key']} ?? '' }}
                        @else
                            {{ $item }}
                        @endif
                    </td>
                @endforeach
                
                @if($actions)
                    <td class="text-end">
                        <div class="btn-group btn-group-sm" role="group">
                            @foreach($actions as $action)
                                @if(!isset($action['visible']) || $action['visible']($item))
                                    @if(isset($action['url']))
                                        <a href="{{ $action['url']($item) }}" 
                                           class="btn btn-{{ $action['variant'] ?? 'ghost' }} btn-sm"
                                           {{ isset($action['target']) ? 'target="'.$action['target'].'"' : '' }}
                                           {{ isset($action['title']) ? 'title="'.$action['title'].'"' : '' }}>
                                            <i class="{{ $action['icon'] ?? 'fas fa-eye' }}"></i>
                                            @if(isset($action['label'])) {{ $action['label'] }} @endif
                                        </a>
                                    @elseif(isset($action['form']))
                                        <form action="{{ $action['form']($item) }}" method="POST" class="d-inline">
                                            @csrf
                                            @if(isset($action['method'])) @method($action['method']) @endif
                                            <button type="submit" 
                                                    class="btn btn-{{ $action['variant'] ?? 'danger' }} btn-sm"
                                                    {{ isset($action['confirm']) ? 'onclick="return confirm(\''.$action['confirm'].'\')"' : '' }}>
                                                <i class="{{ $action['icon'] ?? 'fas fa-trash' }}"></i>
                                                @if(isset($action['label'])) {{ $action['label'] }} @endif
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columns) + ($actions ? 1 : 0) }}" class="text-center py-8">
                    <x-driver.empty-state 
                        :icon="$emptyIcon" 
                        :title="$emptyMessage"
                        :message="''"
                    />
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if($responsive)
</div>
@endif