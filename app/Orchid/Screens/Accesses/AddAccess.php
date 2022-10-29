<?php

namespace App\Orchid\Screens\Accesses;

use Orchid\Screen\Screen;

class AddAccess extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Доступы';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Добавление и использование доступов';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [];
    }
}
