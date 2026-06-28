@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}
$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? "(\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()"
    : '';

$btn     = 'width:30px; height:30px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; font-size:12px; font-weight:600; cursor:pointer; border:1px solid #EDE9FE; background:#fff; color:#7B6FE8; transition:background .15s;';
$btnOff  = 'width:30px; height:30px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; font-size:12px; border:1px solid #E5E7EB; background:#F9FAFB; color:#D1D5DB; cursor:not-allowed;';
$btnOn   = 'width:30px; height:30px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; font-size:12px; font-weight:700; background:#7B6FE8; color:#fff; border:1px solid #7B6FE8;';
@endphp

<div>
@if ($paginator->hasPages())
<nav style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; background:#F9FAFB; border-radius:10px; padding:8px 14px;">

    {{-- Info --}}
    <span style="font-size:12px; color:#9CA3AF;">
        @if ($paginator->firstItem())
            Mostrando <strong style="color:#374151;">{{ $paginator->firstItem() }}</strong>
            – <strong style="color:#374151;">{{ $paginator->lastItem() }}</strong>
            de <strong style="color:#374151;">{{ $paginator->total() }}</strong>
        @else
            {{ $paginator->count() }} registros
        @endif
    </span>

    {{-- Botones --}}
    <div style="display:inline-flex; align-items:center; gap:4px;">

        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <span style="{{ $btnOff }}">
                <svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            </span>
        @else
            <button type="button"
                    wire:click="previousPage('{{ $paginator->getPageName() }}')"
                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                    wire:loading.attr="disabled"
                    style="{{ $btn }}"
                    onmouseover="this.style.background='#F5F3FF'" onmouseout="this.style.background='#fff'">
                <svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            </button>
        @endif

        {{-- Páginas --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="width:30px; height:30px; display:inline-flex; align-items:center; justify-content:center; font-size:12px; color:#9CA3AF;">…</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                    @if ($page == $paginator->currentPage())
                        <span style="{{ $btnOn }}">{{ $page }}</span>
                    @else
                        <button type="button"
                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                style="{{ $btn }}"
                                onmouseover="this.style.background='#F5F3FF'" onmouseout="this.style.background='#fff'">{{ $page }}</button>
                    @endif
                </span>
                @endforeach
            @endif
        @endforeach

        {{-- Siguiente --}}
        @if ($paginator->hasMorePages())
            <button type="button"
                    wire:click="nextPage('{{ $paginator->getPageName() }}')"
                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                    wire:loading.attr="disabled"
                    style="{{ $btn }}"
                    onmouseover="this.style.background='#F5F3FF'" onmouseout="this.style.background='#fff'">
                <svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
            </button>
        @else
            <span style="{{ $btnOff }}">
                <svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
            </span>
        @endif

    </div>
</nav>
@endif
</div>
