<?php

namespace App\Tables\Columns;

use Filament\Tables\Columns\Column;

class HexBadge extends Column
{
    protected string $view = 'tables.columns.hex-badge';

    protected string $color;

    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

}
