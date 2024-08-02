<?php

namespace App\Models;

use App\Libraries\WhatsappLib;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class TextMessage extends ObjectiveModel
{
    use SoftDeletes;

    protected $table = 'text_messages';
    const TABLE_NAME = 'text_messages';

    protected $fillable = [
        'message_title',
        'message_notes',
        'message_content',
        'message_section_used_in',
    ];

    protected array $defaultValues = [];

    // Refer to constructor
    protected array $rulesCreate = [];

    // Refer to constructor
    protected array $rulesEdit = [];

    protected array $messages = [];

    public function __construct(array $attributes = [])
    {
        $this->defaultValues = [
            'message_title'           => '',
            'message_notes'           => '',
            'message_content'         => '',
        ];

        $this->rulesCreate = [
            'message_title'           => 'required|string|max:50',
            'message_notes'           => 'string|min:0|max:150',
            'message_content'         => 'string|max:2000',
        ];
        $this->rulesEdit = $this->rulesCreate;

        $this->messages = [];

        parent::__construct($attributes);
    }

    /**
     * Mutator: transform field on get/set
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function messageContent(): Attribute
    {
        return Attribute::make(
            get: fn($value) => \str_replace("\r\n", "\n", $value),
            set: function($value) {
                $value = \str_replace("\r\n", "\n", $value);
                return $value;
            }
        );
    }

    function allowDelete(): bool
    {
        return true;
    }

}
