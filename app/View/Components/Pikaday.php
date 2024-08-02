<?php

namespace App\View\Components;

/**
 * https://blade-ui-kit.com/docs/0.x/pikaday
 * https://github.com/Pikaday/Pikaday
 */
class Pikaday extends \BladeUIKit\Components\Forms\Inputs\Pikaday
{
    public function options(): array
    {
        return array_merge(
            [
                'disableWeekends' => false,
                // Set default start to Monday.
                'firstDay'        => 1,
                // format requires moment.js
                'format'          => 'd/m/Y',
                // locale requires moment.js
                'locale'          => 'es',
                // Vamos a personalizarlo al español
                'i18n'            => [
                    'previousMonth' => 'Anterior',
                    'nextMonth'     => 'Siguiente',
                    'months'        => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', ' Diciembre'],
                    'weekdays'      => ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    'weekdaysShort' => ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sáb']
                ]
            ], parent::options());
    }
}
