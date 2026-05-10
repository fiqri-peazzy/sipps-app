<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';

    protected $updatesQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function changeRole($userId, $newRole)
    {
        $user = User::find($userId);
        if ($user && in_array($newRole, ['admin', 'customer', 'owner', 'keuangan'])) {
            $user->update(['role' => $newRole]);
            session()->flash('success', "Role user {$user->name} berhasil diperbarui menjadi {$newRole}.");
        }
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            if ($user->id === auth()->id()) {
                session()->flash('error', "Anda tidak dapat menghapus akun Anda sendiri.");
                return;
            }
            $user->delete();
            session()->flash('success', "User {$user->name} berhasil dihapus.");
        }
    }

    public function render()
    {
        $users = User::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
        })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users
        ])->layout('layouts.app');
    }
}
