<article class="testimonial">
    <div class="stars">{{ $testimonialRating ?? '★★★★★' }}</div>
    <p>{{ $testimonialText ?? '' }}</p>
    <div class="person">
        <div class="avatar"></div>
        <div>
            <strong>{{ $testimonialAuthor ?? '' }}</strong>
            <span>{{ $testimonialTitle ?? '' }}</span>
        </div>
    </div>
    <div class="quote"><i class="fa fa-commenting" aria-hidden="true"></i></div>
</article>
