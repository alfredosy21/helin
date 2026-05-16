<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cms;

use App\Models\User;
use App\Models\Role;
use App\Models\Activities;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

/**
 * Class UserController
 * * Handles the complete lifecycle of administrative users within the Helin CMS.
 * Provides reactive interface for user CRUD operations with secure credential handling.
 * * @version 1.0.0
 * @package App\Http\Controllers\Cms
 */
#[Title('User Management | Helin CMS')]
#[Layout('cms.layouts.dashboard')]
class UserController extends Component
{
    use WithPagination;

    /** @var string Full name of the administrative user */
    #[Validate('required|string|max:255', as: 'nombre')]
    public string $name = '';

    /** @var string Unique email address for authentication */
    #[Validate('required|email|max:255|unique:users,email', as: 'correo electrónico')]
    public string $email = '';

    /** @var int|null Associated security role ID */
    #[Validate('required|exists:roles,id', as: 'rol')]
    public ?int $rol_id = null;

    /** @var string|null Plain text password (hashed before persistence) */
    #[Validate('nullable|string|min:8', as: 'contraseña')]
    public ?string $password = '';

    /** @var int|null ID of the user being modified */
    public ?int $editingId = null;

    /** @var string Search query for real-time filtering */
    public string $search = '';

    /** @var int Pagination limit per page */
    public int $perPage = 20;

    /** @var string|null Buffer for UI-suggested passwords */
    public ?string $suggestedPassword = null;

    /** @var bool Modal visibility state */
    public bool $showForm = false;

    /** @var bool Global loading indicator */
    public bool $isLoading = false;

    /** @var string Custom pagination theme for Tailwind CSS */
    protected string $paginationTheme = 'tailwind';

    /**
     * Component Lifecycle: Security check and initial state.
     */
    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || ($user->rol_id !== 1 && $user->level !== 1)) {
            abort(403, 'Unauthorized access to Helin User Directory.');
        }
        
        $this->suggestedPassword = Str::password(12);
    }

    /**
     * Render the component with paginated and filtered administrative users.
     */
    public function render(): View
    {
        $users = User::with('role')
            ->where('level', 2)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                      ->orWhereHas('role', fn($rq) => $rq->where('name', 'like', "%{$this->search}%"));
                });
            })
            ->latest()
            ->paginate($this->perPage);

        $roles = Role::pluck('name', 'id');

        return view('cms.users.index', [
            'users' => $users,
            'roles' => $roles
        ]);
    }

    /**
     * Prepare the interface for a new user record.
     */
    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Persist or synchronize user data.
     */
    public function save(): void
    {
        $this->isLoading = true;

        // Dynamic validation logic for email uniqueness and password requirements
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email' . ($this->editingId ? ",{$this->editingId}" : ''),
            'rol_id' => 'required|exists:roles,id',
            'password' => $this->editingId ? 'nullable|string|min:8' : 'required|string|min:8',
        ];

        $this->validate($rules);

        try {
            $data = [
                'name'   => $this->name,
                'email'  => $this->email,
                'rol_id' => $this->rol_id,
                'level'  => 2,
                'is_active' => true,
                'image'  => 'default-avatar.png',
            ];

            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }

            if ($this->editingId) {
                $user = User::findOrFail($this->editingId);
                $user->update($data);
                
                Activities::saveActivity("Perfil de usuario sincronizado: Admin ID #{$user->id}");
                $this->dispatch('toast', message: 'Usuario actualizado correctamente', type: 'success');
            } else {
                $user = User::create($data);
                
                Activities::saveActivity("Usuario provisionado: Admin ID #{$user->id}");
                $this->dispatch('toast', message: 'Usuario creado correctamente', type: 'success');
            }

            $this->cancel();

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'Error al procesar la solicitud del usuario', type: 'error');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Hydrate form with existing user data.
     */
    public function edit(int $id): void
    {
        $user = User::findOrFail($id);

        $this->editingId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->rol_id = $user->rol_id;
        $this->password = ''; // Clear password field for security

        $this->showForm = true;
        $this->dispatch('open-form');
    }

    /**
     * Execute user termination after UI confirmation.
     */
    public function confirmDelete(int $id): void
    {
        try {
            $user = User::findOrFail($id);
            $userName = $user->name;
            $user->delete();

            Activities::saveActivity("Terminación de usuario: {$userName}");
            $this->dispatch('toast', message: 'Usuario eliminado correctamente', type: 'success');

        } catch (\Exception $ex) {
            report($ex);
            $this->dispatch('toast', message: 'No se puede eliminar el usuario. Datos relacionados activos.', type: 'error');
        }
    }

    /**
     * Securely generate a new random password.
     */
    public function generatePassword(): void
    {
        $newPass = Str::password(12, symbols: false);
        $this->password = $newPass;
        $this->suggestedPassword = $newPass;
    }

    /**
     * Close form and reset internal state.
     */
    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
        $this->dispatch('close-form');
    }

    /**
     * Clean all form-related properties.
     */
    private function resetForm(): void
    {
        $this->reset(['name', 'email', 'rol_id', 'password', 'editingId']);
        $this->resetValidation();
        $this->suggestedPassword = Str::password(12, symbols: false);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Compatibility bridge for legacy API/Frontend calls.
     */
    public function getUserLists(): array
    {
        return User::with('role')
            ->where('level', 2)
            ->latest()
            ->get()
            ->toArray();
    }
}