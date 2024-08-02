<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ObjectiveModel
 *
 * @method static Builder|ObjectiveModel newModelQuery()
 * @method static Builder|ObjectiveModel newQuery()
 * @method static Builder|ObjectiveModel query()
 * @property mixed $notes
 * @mixin \Eloquent
 */
class ObjectiveModel extends Model
{
    /**
     * List of key value pair options.
     * Usually used for select lists.
     * Usually an array of [id => title, id => title, ...]
     *
     * @var array
     */
    protected array $options = [];

    /**
     * Functions store message here if required. e.g. allowDelete()
     *
     * @var string
     */
    public string $message = '';

    protected array $rules = [];
    protected array $messages = [];

    /**
     * Rules used when editing the resource.
     *
     * @var array
     */
    protected array $rulesEdit = [];

    /**
     * Rules used when creating the resource.
     *
     * @var array
     */
    protected array $rulesCreate = [];

    /**
     * @var array
     */
    protected array $defaultValues = [];

    public function __construct(array $attributes = [])
    {
        // Load Default Values
        $this->fillWithDefaultValues();

        parent::__construct($attributes);
    }

    /**
     * Mutator: transform field on get/set
     */
    protected function notes(): Attribute
    {
        return Attribute::make(
            get: fn($value) => \str_replace("\r\n", "\n", $value),
            set: fn($value) => \str_replace("\r\n", "\n", $value)
        );
    }

    /**
     * @return string[]
     */
    public function getRulesCreate($specificField = ''): array
    {
        if($specificField) {
            if(!isset($this->rulesCreate[$specificField])) {
                return [$specificField => ''];
            }
            return [$specificField => $this->rulesCreate[$specificField]];
        }
        return $this->rulesCreate;
    }

    /**
     * @return string[]
     */
    public function getRulesEdit($specificField = ''): array
    {
        if($specificField) {
            if(!isset($this->rulesEdit[$specificField])) {
                return [$specificField => ''];
            }
            return [$specificField => $this->rulesEdit[$specificField]];
        }
        return $this->rulesEdit;
    }

    public function getRulesCreatePrefixed(string $prefix): array
    {
        $rules = $this->getRulesCreate();
        $newRules = [];
        foreach($rules as $key => $val) {
            $newRules[$prefix.$key] = $val;
        }
        return $newRules;
    }

    public function getRulesEditPrefixed(string $prefix): array
    {
        $rules = $this->getRulesEdit();
        $newRules = [];
        foreach($rules as $key => $val) {
            $newRules[$prefix.$key] = $val;
        }
        return $newRules;
    }

    function getOptionLabel($optionId): string
    {
        return $this->options[$optionId];
    }

    function getOptions(): array
    {
        return $this->options;
    }

    function fillWithDefaultValues()
    {
        if(empty($this->getDefaultValues())) {
            return;
        }
        $this->fill($this->getDefaultValues());
    }

    public function getDefaultValues(): array
    {
        return $this->defaultValues;
    }

    public function getDefaultValue(string $field): mixed
    {
        return $this->defaultValues[$field];
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Checks if record exists. Throws exception if not found.
     * !NOTE! This ignores deleted records.
     *
     * @param int $id
     * @return void
     * @throws \Exception Exception thrown when the record is not found in the database.
     */
    public static function checkExists(int $id): void
    {
        if(!self::whereId($id)->exists()) {
            throw new \Exception(\get_called_class().' with ID: '.$id.' not found in the database. Is it deleted?');
        }
    }

    function delete(): void
    {
        if(!userCan('delete records')) {
            throw new \Exception('Unauthorized');
        }

        if($this->allowDelete()) {
            parent::delete();
        }
        else {
            throw new \Exception(__('Not allowed to delete this record.').' '.$this->message);
        }
    }

    function allowDelete(): bool
    {
        // Auth
        if(!\userCan('delete records')) {
            $this->message = __('You are not authorized to delete');
            return false;
        }

        $this->message = __('Not allowed to delete this record.').' #XAD';
        return false;
    }

    /**
     * Get a label from labels
     *
     * @param string $fieldname
     * @return string
     */
    static function label(string $fieldname): string
    {
        if(!isset(static::$labels[$fieldname])) {
            return '#NoLabel:'.$fieldname.'#';
        }
        return static::$labels[$fieldname];
    }
}
