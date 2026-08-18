<div>
<div style="max-width:900px; margin:0 auto;">

    <div class="pedido-body" style="padding:12px 0 16px;">

        @include('livewire.vendedor.partials.pedido-detalle-body', ['pedido' => $pedido])

    {{-- Botón Regresar --}}
    <div style="margin-top:20px;">
        <a href="{{ route('vendedor.pedidos') }}"
           style="display:flex; align-items:center; justify-content:center; gap:8px; width:100%; padding:14px; background:#F4F4F4; color:#6D8196; font-size:15px; font-weight:900; letter-spacing:0.08em; text-transform:uppercase; border-radius:12px; text-decoration:none; box-sizing:border-box; border:1.5px solid #CBCBCB;">
            <span style="font-size:17px; line-height:1; font-weight:900; letter-spacing:-2px;">«</span>
            Regresar
        </a>
    </div>

    </div>
</div>

</div>
