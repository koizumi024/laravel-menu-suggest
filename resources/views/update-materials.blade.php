@extends('layouts.app')

@section('javascript')
{{-- <script src="{{ asset('js/border.js') }}" defer></script> --}}
<script>
    @foreach ($materials as $m)
    function selectMaterial{{ $m['id']}}(){
        let element = document.getElementById({{ $m['id']}});
            if (checkbox{{ $m['id']}}.checked){
                element.className = 'materials__border';
            } else {
                element.className = 'materials__border-active';
        }
    }
    @endforeach
</script>
@endsection

@section('content')
<form class="materials" action="{{ route('store') }}" method="POST">
    @csrf
    <div id="app" class="container-md">
        <h1 class="py-4 text-center">食材の更新</h1>
        
        <h3 class="head-text mb-3"><i class="fas fa-search mr-1"></i>カテゴリ選択</h3>
        <div class="categories mb-4">
            @foreach ($categories as $c)
            <input type="radio" class="d-none" id="tab{{ $c['id'] }}"
            name="tab" value="{{ $c['id'] }}" v-model="isActive">
            <label for="tab{{ $c['id'] }}">{{ $c['category'] }}</label>
            @endforeach 
        </div>

        <h3 class="head-text mb-3"><i class="fas fa-hand-pointer mr-2"></i>持っている食材を選択してください</h3>
        
            
            @foreach ($categories as $c)
            <div class="materials__wrap" v-bind:class="isActive == {{ $c['id'] }} ? '' : 'd-none' ">
                @foreach ($materials as $m)
                    @if($m['category_id'] == $c['id'])
                        <input type="checkbox" name="materials_id[]" class="d-none" id="checkbox{{ $m['id'] }}" value={{ $m['id'] }} 
                        {{ in_array($m['id'], $includeMaterialsId) ? 'checked' : '' }}>
                        <div class="materials__content {{ in_array($m['id'], $exclude_materials) ? 'd-none' : '' }}">
                            <div class="{{ in_array($m['id'], $includeMaterialsId) ? 'materials__border-active' : 'materials__border' }}"
                            id="{{ $m['id'] }}">
                                <label for="checkbox{{ $m['id'] }}" onClick="selectMaterial{{ $m['id'] }}()">
                                    <img loading="lazy" src="/img/materials/{{ $m['image'] }}" alt="食材画像" class="material__img">
                                </label>
                            </div>
                            <li class="material__name">{{ $m['material'] }}</li>
                        </div>
                    @endif
                @endforeach
                
            </div> 
            @endforeach

        
    </div>
    <div class="button_bar d-flex justify-content-center">
        <button type="submit" class="material__submitBtn"><i class="fas fa-redo-alt mr-2"></i>更新する</button>
    </div>
</form>
@endsection
