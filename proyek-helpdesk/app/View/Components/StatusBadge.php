<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    public string $status;
    public string $text;

    /**
     * Create a new component instance.
     */
    public function __construct(string $status)
    {
        $this->status = $status;
        // Ubah teks dasar di sini jika perlu
        $this->text = ucfirst(str_replace('_', ' ', $status));
         if ($status == 'completed') {
             $this->text = 'Completed';
         }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.status-badge');
    }
}
