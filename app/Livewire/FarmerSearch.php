<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class FarmerSearch extends Component
{
    use WithPagination;

    public string $search = '';

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::where('role', 'user')
            ->withCount(['lands', 'schedules', 'transactions']);

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        $farmers = $query->orderBy($this->sortBy, $this->sortDirection)->paginate(10);

        return view('livewire.farmer-search', [
            'farmers' => $farmers,
        ]);
    }
}
