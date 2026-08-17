<!-- Sección de Opinión -->
@php
    $opinionSettings = \App\Models\Settings::getSettings();
    $opinionFeedback = \App\Models\Sections::find(\App\Models\Sections::FEEDBACK_BANNER);
    $opinionUrl = $opinionSettings && !empty($opinionSettings->opinion_url) ? $opinionSettings->opinion_url : null;
@endphp
@if($opinionUrl)
<section class="opinion">
   <h3>{{ $opinionFeedback && $opinionFeedback->status == 1 && $opinionFeedback->status_content == 1 ? $opinionFeedback->title : '¡Nos encantaría conocer tu opinión!' }}</h3>
   <a href="{{ $opinionUrl }}" target="_blank">Compartir comentario</a>
</section>
@endif