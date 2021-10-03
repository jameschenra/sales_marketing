{{-- Nav --}}
<ul class="navi navi-hover py-4">
    {{-- Item --}}
    <li class="navi-item">
        <a href="{{ url('locale/' . $languageList[1]['locale']) }}" class="navi-link">
            <span class="symbol symbol-20 mr-3">
                <img src="{{ $languageList[1]['icon'] }}" alt=""/>
            </span>
            <span class="navi-text">{{ $languageList[1]['label'] }}</span>
        </a>
    </li>

    <li class="navi-item">
        <a href="{{ url('locale/' . $languageList[2]['locale']) }}" class="navi-link">
            <span class="symbol symbol-20 mr-3">
                <img src="{{ $languageList[2]['icon'] }}" alt=""/>
            </span>
            <span class="navi-text">{{ $languageList[2]['label'] }}</span>
        </a>
    </li>

</ul>