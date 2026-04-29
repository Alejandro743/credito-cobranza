<?php

namespace App\Livewire\Concerns;

trait HasModuleColor
{
    public string $moduleColor = '';

    public function initModuleColor(): void
    {
        $route = request()->route()?->getName();
        $modulo = \App\Models\Modulo::select('color')
            ->whereHas('submodulosActivos', fn($q) =>
                $q->where('route_name', $route)
                  ->orWhereHas('children', fn($q2) => $q2->where('route_name', $route)))
            ->first();
        $this->moduleColor = $modulo?->color ?? '';
    }
}
