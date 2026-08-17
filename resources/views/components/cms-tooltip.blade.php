@props([
    'text' => '',
    'position' => 'top',
])

<div class="relative inline-flex cms-tooltip-trigger"
     x-data="{
        show: false,
        tooltip: null,
        showTip($event) {
            this.show = true;
            this.$nextTick(() => {
                const rect = this.$el.getBoundingClientRect();
                const tip = this.$refs.tip;
                tip.style.visibility = 'hidden';
                tip.style.display = 'block';
                const tipRect = tip.getBoundingClientRect();
                let top = rect.top - tipRect.height - 8;
                let left = rect.left + rect.width/2 - tipRect.width/2;
                if (top < 8) top = rect.bottom + 8;
                if (left < 8) left = 8;
                if (left + tipRect.width > window.innerWidth - 8) left = window.innerWidth - tipRect.width - 8;
                tip.style.top = top + 'px';
                tip.style.left = left + 'px';
                tip.style.visibility = 'visible';
            });
        },
        hideTip() {
            this.show = false;
        }
     }"
     @mouseenter="showTip($event)"
     @mouseleave="hideTip()">
    {{ $slot }}
    <div x-ref="tip"
         x-show="show"
         x-cloak
         class="fixed px-2.5 py-1.5 bg-slate-800 text-white text-[10px] font-semibold rounded-lg pointer-events-none whitespace-nowrap shadow-lg z-[99999]"
         style="display:none;">
        {{ $text }}
    </div>
</div>
