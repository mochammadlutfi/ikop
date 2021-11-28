<?php

namespace App\Widgets\Dashboard;

use Arrilot\Widgets\AbstractWidget;

class PembayaranWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //

        return view('widgets.dashboard.pembayaran_widget', [
            'config' => $this->config,
        ]);
    }
}
