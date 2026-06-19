<article class="policy-card" id="{{ $policyId ?? '' }}">
    <div class="policy-icon-wrap">
        <div class="policy-icon">{{ $policyIcon ?? '' }}</div>
    </div>
    <div class="policy-number">{{ $policyNumber ?? '' }}</div>
    <div class="policy-content">
        <h2>{{ $policyTitle ?? '' }}</h2>
        <p>{{ $policyDescription ?? '' }}</p>

        <div class="policy-points">
            @foreach($policyPoints ?? [] as $point)
            <div class="point">
                <div class="point-icon">{{ $point['icon'] ?? '' }}</div>
                <div>
                    <h3>{{ $point['title'] ?? '' }}</h3>
                    <p>{{ $point['description'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="policy-action">
        <button class="more-btn">Ver más ›</button>
    </div>
</article>
