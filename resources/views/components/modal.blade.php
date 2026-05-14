@props(['id', 'title', 'message', 'confirmText' => 'Confirmar', 'cancelText' => 'Cancelar', 'type' => 'danger'])

@once
    @push('styles')
    <style>
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        .modal-content {
            animation: modalSlideIn 0.3s ease-out;
        }
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }
    </style>
    @endpush
@endonce

<!-- Modal Backdrop -->
<div id="{{ $id }}" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop fixed inset-0" onclick="closeModal('{{ $id }}')"></div>
    
    <!-- Modal Content -->
    <div class="modal-content fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 z-50">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ $title }}
            </h3>
        </div>
        
        <!-- Modal Body -->
        <div class="px-6 py-4">
            <p class="text-gray-600 dark:text-gray-300">
                {{ $message }}
            </p>
        </div>
        
        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
            <button
                type="button"
                onclick="closeModal('{{ $id }}')"
                class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md transition-colors"
            >
                {{ $cancelText }}
            </button>
            
            <button
                type="button"
                onclick="confirmModal('{{ $id }}')"
                class="px-4 py-2 text-white rounded-md transition-colors
                    {{ $type === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700' }}"
            >
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>

@once
    @push('scripts')
    <script>
        // Modal functions
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }
        
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }
        
        function confirmModal(modalId) {
            // Dispatch custom event that can be listened to by parent components
            const event = new CustomEvent('modalConfirmed', {
                detail: { modalId: modalId }
            });
            document.dispatchEvent(event);
            
            closeModal(modalId);
        }
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modals = document.querySelectorAll('.fixed.inset-0.z-50:not(.hidden)');
                modals.forEach(modal => {
                    if (modal.id) {
                        closeModal(modal.id);
                    }
                });
            }
        });
    </script>
    @endpush
@endonce
