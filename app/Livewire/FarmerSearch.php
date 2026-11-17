<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class FarmerSearch extends Component
{
    use WithPagination;

    /**
     * Real-time search query string.
     */
    public string $search = '';

    /**
     * Sort column.
     */
    public string $sortBy = 'created_at';

    /**
     * Sort direction.
     */
    public string $sortDirection = 'desc';

    /**
     * Reset pagination when searching.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Render the Livewire component.
     */
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
