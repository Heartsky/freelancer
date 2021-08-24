<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardTraffic extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    private $total;
    private $balance;
    public function __construct($total, $balance)
    {
        $this->total = $total;
        $this->balance = $balance;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('backend.components.cards.traffic', ['total' => $this->total, 'balance' => $this->balance]);
    }
}
