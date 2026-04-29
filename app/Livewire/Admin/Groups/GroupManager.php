<?php

namespace App\Livewire\Admin\Groups;

use App\Models\Group;
use App\Models\GrupoMiembroManual;
use App\Models\ListaMaestra;
use App\Models\User;
use App\Livewire\Concerns\HasModuleColor;
use Livewire\Component;
use Livewire\WithPagination;

class GroupManager extends Component
{
    use WithPagination, HasModuleColor;

    public string $mode        = 'list'; // list | detail
    public string $search      = '';
    public string $filterType  = '';
    public string $filterStatus = '';

    // Inline add form (en list mode)
    public bool   $showAddForm    = false;
    public string $newName        = '';
    public string $newType        = 'clientes';
    public string $newDescription = '';
    public bool   $newActive      = true;

    // Inline row edit (en list mode)
    public ?int   $editingId      = null;
    public string $editName        = '';
    public string $editType        = 'clientes';
    public string $editDescription = '';
    public bool   $editActive      = true;

    // Detail mode
    public ?int   $viewingId = null;

    // Sección Miembros (detail)
    public string $searchMiembro       = '';
    public string $filterOrigenMiembro = ''; // '' | 'auto' | 'manual'
    public string $filterStatusMiembro = ''; // '' | '1' | '0'
    public bool   $showAddMemberForm   = false;
    public ?int   $addMemberUserId     = null;

    // Sección Listas (detail)
    public bool  $showAddListaForm = false;
    public ?int  $addListaId       = null;

    public function mount(): void
    {
        $this->initModuleColor();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    // ── List: inline add ──────────────────────────────────────────────────────

    public function showAdd(): void
    {
        $this->showAddForm    = true;
        $this->newName        = '';
        $this->newType        = 'clientes';
        $this->newDescription = '';
        $this->newActive      = true;
        $this->editingId      = null;
        $this->resetValidation();
    }

    public function cancelAdd(): void
    {
        $this->showAddForm = false;
        $this->resetValidation();
    }

    public function saveNew(): void
    {
        $this->validate([
            'newName' => 'required|string|min:2',
            'newType' => 'required|in:clientes,vendedores',
        ], [], ['newName' => 'nombre', 'newType' => 'tipo']);

        Group::create([
            'name'        => $this->newName,
            'type'        => $this->newType,
            'description' => $this->newDescription,
            'active'      => $this->newActive,
        ]);

        $this->showAddForm = false;
        session()->flash('success', 'Grupo creado.');
    }

    // ── List: inline row edit ─────────────────────────────────────────────────

    public function startEdit(int $id): void
    {
        $g = Group::findOrFail($id);
        $this->editingId      = $id;
        $this->editName        = $g->name;
        $this->editType        = $g->type;
        $this->editDescription = $g->description ?? '';
        $this->editActive      = $g->active;
        $this->showAddForm     = false;
        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->resetValidation();
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editName' => 'required|string|min:2',
            'editType' => 'required|in:clientes,vendedores',
        ], [], ['editName' => 'nombre', 'editType' => 'tipo']);

        Group::findOrFail($this->editingId)->update([
            'name'        => $this->editName,
            'type'        => $this->editType,
            'description' => $this->editDescription,
            'active'      => $this->editActive,
        ]);

        $this->editingId = null;
        session()->flash('success', 'Grupo actualizado.');
    }

    public function toggleActive(int $id): void
    {
        $g = Group::findOrFail($id);
        $g->update(['active' => !$g->active]);
    }

    // ── Detail mode ───────────────────────────────────────────────────────────

    public function viewDetail(int $id): void
    {
        $this->viewingId           = $id;
        $this->searchMiembro       = '';
        $this->filterOrigenMiembro = '';
        $this->filterStatusMiembro = '';
        $this->showAddMemberForm   = false;
        $this->addMemberUserId     = null;
        $this->showAddListaForm    = false;
        $this->addListaId          = null;
        $this->mode                = 'detail';
    }

    public function backToList(): void
    {
        $this->reset(['viewingId', 'searchMiembro', 'filterOrigenMiembro', 'filterStatusMiembro',
                      'showAddMemberForm', 'addMemberUserId', 'showAddListaForm', 'addListaId']);
        $this->mode = 'list';
    }

    // ── Miembros manuales ─────────────────────────────────────────────────────

    public function toggleAddMember(): void
    {
        $this->showAddMemberForm = !$this->showAddMemberForm;
        $this->addMemberUserId   = null;
        $this->resetValidation();
    }

    public function saveAddMember(): void
    {
        $this->validate(
            ['addMemberUserId' => 'required|integer|exists:users,id'],
            [],
            ['addMemberUserId' => 'usuario']
        );

        GrupoMiembroManual::firstOrCreate([
            'group_id' => $this->viewingId,
            'user_id'  => $this->addMemberUserId,
        ]);

        $this->showAddMemberForm = false;
        $this->addMemberUserId   = null;
        session()->flash('success', 'Miembro agregado.');
    }

    public function removeMember(int $userId): void
    {
        GrupoMiembroManual::where('group_id', $this->viewingId)
            ->where('user_id', $userId)
            ->delete();
    }

    // ── Listas de Precios ──────────────────────────────────────────────────────

    public function toggleAddLista(): void
    {
        $this->showAddListaForm = !$this->showAddListaForm;
        $this->addListaId       = null;
        $this->resetValidation();
    }

    public function saveAddLista(): void
    {
        $this->validate(
            ['addListaId' => 'required|integer|exists:lista_maestra,id'],
            [],
            ['addListaId' => 'lista de precios']
        );

        Group::findOrFail($this->viewingId)
            ->listas()
            ->syncWithoutDetaching([$this->addListaId]);

        $this->showAddListaForm = false;
        $this->addListaId       = null;
        session()->flash('success', 'Lista asignada al grupo.');
    }

    public function removeLista(int $listaId): void
    {
        Group::findOrFail($this->viewingId)->listas()->detach($listaId);
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function render()
    {
        // ── List mode ────────────────────────────────────────────────────────
        $groups = Group::withCount(['users', 'miembrosManual', 'listas'])
            ->when($this->search,       fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->filterType,   fn($q) => $q->where('type', $this->filterType))
            ->when($this->filterStatus !== '', fn($q) => $q->where('active', (bool) $this->filterStatus))
            ->orderBy('name')
            ->paginate(15);

        // ── Detail mode ──────────────────────────────────────────────────────
        $viewingGroup    = null;
        $allMembers      = collect();
        $availableUsers  = collect();
        $assignedListas  = collect();
        $availableListas = collect();

        if ($this->viewingId && $this->mode === 'detail') {
            $viewingGroup = Group::find($this->viewingId);

            if ($viewingGroup) {
                // Miembros automáticos (vía group_user)
                $autoMembers = $viewingGroup->users()
                    ->select('users.id', 'users.name', 'users.email', 'users.tipo')
                    ->get()
                    ->map(fn($u) => [
                        'id'     => $u->id,
                        'name'   => $u->name,
                        'email'  => $u->email,
                        'tipo'   => $u->tipo,
                        'origen' => 'auto',
                    ]);

                // Miembros manuales
                $manualUserIds = $viewingGroup->miembrosManual()->pluck('user_id');
                $manualMembers = User::whereIn('id', $manualUserIds)
                    ->select('id', 'name', 'email', 'tipo')
                    ->get()
                    ->map(fn($u) => [
                        'id'     => $u->id,
                        'name'   => $u->name,
                        'email'  => $u->email,
                        'tipo'   => $u->tipo,
                        'origen' => 'manual',
                    ]);

                // Consolidado (manuales que también son auto se marcan 'auto' con prioridad)
                $autoIds    = $autoMembers->pluck('id');
                $allMembers = $autoMembers
                    ->concat($manualMembers->filter(fn($m) => !$autoIds->contains($m['id'])))
                    ->sortBy('name')
                    ->values();

                // Aplicar filtros de miembros
                if ($this->searchMiembro) {
                    $s = mb_strtolower($this->searchMiembro);
                    $allMembers = $allMembers->filter(
                        fn($m) => str_contains(mb_strtolower($m['name']), $s)
                               || str_contains(mb_strtolower($m['email']), $s)
                    )->values();
                }
                if ($this->filterOrigenMiembro) {
                    $allMembers = $allMembers->where('origen', $this->filterOrigenMiembro)->values();
                }
                // filterStatusMiembro no aplica (users no tiene columna active)

                // Usuarios disponibles para agregar (mismo tipo que el grupo, no ya manuales)
                $tipoUsuario    = $viewingGroup->type === 'clientes' ? 'cliente' : 'vendedor';
                $availableUsers = User::where('tipo', $tipoUsuario)
                    ->whereNotIn('id', $manualUserIds)
                    ->select('id', 'name', 'email')
                    ->orderBy('name')
                    ->get();

                // Listas
                $assignedListas  = $viewingGroup->listas()->with('cycle')->get();
                $assignedIds     = $assignedListas->pluck('id');
                $availableListas = ListaMaestra::whereNotIn('id', $assignedIds)
                    ->with('cycle')
                    ->orderBy('name')
                    ->get();
            }
        }

        return view('livewire.admin.groups.group-manager',
            compact('groups', 'viewingGroup', 'allMembers', 'availableUsers',
                    'assignedListas', 'availableListas'));
    }
}
