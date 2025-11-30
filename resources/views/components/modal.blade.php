@props([
    'id' => '',
    'title' => '',
    'size' => 'md', // sm, md, lg, xl
    'show' => false,
    'class' => ''
])

<div
    class="modal fade {{ $show ? 'show' : '' }}"
    id="{{ $id }}"
    tabindex="-1"
    aria-labelledby="{{ $id }}Label"
    aria-hidden="{{ $show ? 'false' : 'true' }}"
    {{ $show ? 'style="display: block;"' : '' }}
>
    <div class="modal-dialog modal-{{ $size }} {{ $class }}">
        <div class="modal-content">
            @if($title)
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif

            <div class="modal-body">
                {{ $slot }}
            </div>

            @if(isset($footer))
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

@if($show)
    <div class="modal-backdrop fade show"></div>
@endif

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1055;
    display: none;
    width: 100%;
    height: 100%;
    overflow: hidden;
    outline: 0;
}

.modal.show {
    display: block;
}

.modal-dialog {
    position: relative;
    width: auto;
    margin: 0.5rem;
    pointer-events: none;
}

.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
    transform: translate(0, -50px);
}

.modal.show .modal-dialog {
    transform: translate(0, 0);
}

.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 0.3rem;
    outline: 0;
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050;
    width: 100vw;
    height: 100vh;
    background-color: #000;
}

.modal-backdrop.fade {
    opacity: 0;
}

.modal-backdrop.show {
    opacity: 0.5;
}

.modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1rem 1rem;
    border-bottom: 1px solid #dee2e6;
    border-top-left-radius: calc(0.3rem - 1px);
    border-top-right-radius: calc(0.3rem - 1px);
}

.modal-title {
    margin-bottom: 0;
    line-height: 1.5;
    font-size: 1.25rem;
    font-weight: 500;
}

.btn-close {
    width: 1em;
    height: 1em;
    padding: 0.25em;
    margin: -0.25em -0.25em -0.25em auto;
    background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
    border: 0;
    border-radius: 0.25rem;
    opacity: 0.5;
    cursor: pointer;
    transition: opacity 0.15s ease;
}

.btn-close:hover {
    opacity: 0.75;
}

.modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 1rem;
}

.modal-footer {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    padding: 0.75rem;
    border-top: 1px solid #dee2e6;
    border-bottom-right-radius: calc(0.3rem - 1px);
    border-bottom-left-radius: calc(0.3rem - 1px);
}

/* Size variants */
.modal-sm {
    max-width: 300px;
}

.modal-lg {
    max-width: 800px;
}

.modal-xl {
    max-width: 1140px;
}

/* Mobile responsive */
@media (max-width: 576px) {
    .modal-dialog {
        margin: 0;
        max-width: none;
        width: 100%;
        max-height: 100vh;
    }

    .modal-content {
        border-radius: 0;
        max-height: 100vh;
        overflow-y: auto;
    }

    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 0.75rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle modal show/hide
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        const backdrop = modal.nextElementSibling;
        if (backdrop && backdrop.classList.contains('modal-backdrop')) {
            backdrop.addEventListener('click', () => {
                modal.classList.remove('show');
                modal.style.display = 'none';
                backdrop.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');
            });
        }

        // Handle close buttons
        const closeButtons = modal.querySelectorAll('[data-bs-dismiss="modal"]');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                modal.classList.remove('show');
                modal.style.display = 'none';
                const backdrop = modal.nextElementSibling;
                if (backdrop && backdrop.classList.contains('modal-backdrop')) {
                    backdrop.classList.remove('show');
                }
                modal.setAttribute('aria-hidden', 'true');
            });
        });
    });
});
</script>