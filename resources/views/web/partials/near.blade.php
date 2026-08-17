<!-- Sección "Estamos cerca de ti" -->
<section class="near rounded-2xl border border-helin-border bg-helin-soft flex items-center gap-7 p-6" style="
   margin: 12px 0;
   border-radius: 28px;
   border: 1px solid #D8E3E5;
   background: #f4f7f8;
   display: flex;
   align-items: center;
   gap: 26px;
   padding: 24px 30px;
   box-shadow: 0 10px 25px rgba(15,47,67,.06);
   ">
   <div class="circle-icon w-12 h-12 rounded-xl bg-turquesa/10 border border-turquesa/30 flex items-center justify-center text-turquesa font-black text-xl">
      <i class="fa fa-location-arrow" aria-hidden="true"></i>
   </div>
   <div>
      @php
          $nearSection = \App\Models\Sections::find(\App\Models\Sections::NEAR_YOU);
      @endphp
      <h2 class="text-2xl lg:text-3xl leading-none" style="letter-spacing: 0;">
         @if($nearSection && $nearSection->status == 1 && $nearSection->status_content == 1)
            @php
                $nearTitle = $nearSection->title ?? '';
                $nearComma = strpos($nearTitle, ',');
                if ($nearComma !== false) {
                    $nearHead = trim(substr($nearTitle, 0, $nearComma));
                    $nearTail = trim(substr($nearTitle, $nearComma + 1));
                } else {
                    $nearHead = $nearTitle;
                    $nearTail = '';
                }
            @endphp
            <span class="text-turquesa">{{ $nearHead }}</span>@if($nearTail), {{ $nearTail }}@endif
         @else
            <span class="text-turquesa">Estamos cerca de ti,</span> donde construyes salud oral
         @endif
      </h2>
      <p class="text-helin-text font-bold mt-1">
         @php
             $nearSettings = \App\Models\Settings::getSettings();
             $nearOffices = $nearSettings && $nearSettings->offices ? $nearSettings->offices : [];
             $nearMessage = 'Hola, estoy interesado en productos Helin y me gustaría recibir asesoría de un ejecutivo comercial.';
             $nearLinks = [];
             foreach ($nearOffices as $nearOffice) {
                 $nearActive = isset($nearOffice['active']) ? (bool) $nearOffice['active'] : true;
                 if (!$nearActive) continue;
                 $nearWhatsapp = $nearOffice['whatsapp'] ?? null;
                 if (!$nearWhatsapp) continue;
                 $nearPhone = preg_replace('/[^0-9]/', '', $nearWhatsapp);
                 if (!$nearPhone) continue;
                 $nearCityName = ucfirst($nearOffice['city'] ?? $nearOffice['name'] ?? '');
                 if (!$nearCityName) continue;
                 $nearLinks[$nearCityName] = 'https://wa.me/' . $nearPhone . '?text=' . urlencode($nearMessage);
             }
         @endphp
         @foreach($nearLinks as $nearCityName => $nearUrl)
            <a href="{{ $nearUrl }}" target="_blank" class="hover:text-turquesa transition-colors">{{ $nearCityName }}</a>@if(!$loop->last)<span> · </span>@endif
         @endforeach
      </p>
   </div>
</section>
