<article class="policy-section" id="{{ $policyId ?? '' }}">
    <div class="policy-intro">
        <div class="policy-heading">
            <span class="policy-number">{{ $policyNumber ?? '' }}</span>
            <h2>{{ $policyTitle ?? '' }}</h2>
        </div>

        <p class="policy-description">{{ $policyDescription ?? '' }}</p>
    </div>

    <div class="policy-points">
        @foreach($policyPoints ?? [] as $point)
        <div class="point">
            <h3>{{ $point['title'] ?? '' }}</h3>
            <p>{{ $point['description'] ?? '' }}</p>
        </div>
        @endforeach
    </div>
</article>
