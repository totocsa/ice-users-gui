<?php

namespace Totocsa\UsersGUI\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Totocsa\Icseusd\Http\Controllers\IcseusdController;
use App\Models\User;

class UserController extends IcseusdController
{
    public $vuePageDir = 'vendor/totocsa/ice-users-gui/resources/js/Pages';
    public $modelClassName = User::class;

    public $sort = [
        'field' => 'users-name',
        'direction' => 'asc',
    ];

    public $orders = [
        'index' => [
            'filters' => ['users-name', 'users-email'],
            'sorts' => [
                'users-name' => ['users-name', 'users-email'],
                'users-email' => ['users-email', 'users-name'],
            ],
            'item' => [
                'fields' => ['users-name', 'users-email'],
            ],
            'itemButtons' => ['show', 'edit', 'destroy'],
        ],
        'form' => [
            'item' => [
                'fields' => ['users-name', 'users-email'],
            ],
        ],
        'show' => [
            'item' => [
                'fields' => ['users-name', 'users-email'],
            ],
        ],
    ];

    public $filters = [
        'users-name' => '',
        'users-email' => '',
    ];

    public function conditions()
    {
        return [
            'users-name' => [
                'operator' => $this->ilikeORLike,
                'value' => "%{{users-name}}%",
                'boolean' => 'and',
            ],
            'users-email' => [
                'operator' => $this->ilikeORLike,
                'value' => "%{{users-email}}%",
                'boolean' => 'and',
            ],
        ];
    }

    public $doNotSaveOnUpdate = [
        'users-password',
    ];

    public function fields()
    {
        return [
            'filter' => [
                'users-name' => [
                    'tagName' => 'input',
                    'attributes' => [
                        'type' => 'text',
                    ],
                ],
                'users-email' => [
                    'tagName' => 'input',
                    'attributes' => [
                        'type' => 'text',
                    ],
                ],
                'current_team_id' => [
                    'tagName' => 'select',
                    'options' => ['additionalData', 'current_team_idValueTexts'],
                    'attributes' => [],
                ],
            ],
            'item' => [
                'users-name' => [
                    'tagName' => 'EDITABLE_TEXT_STARTER',
                    'attributes' => [
                        'type' => 'text',
                    ],
                ],
            ],
            'form' => [
                'users-name' => [
                    'tagName' => 'input',
                    'attributes' => [
                        'type' => 'text',
                    ],
                ],
                'users-email' => [
                    'tagName' => 'input',
                    'attributes' => [
                        'type' => 'email',
                    ],
                ],
                'current_team_id' => [
                    'tagName' => 'select',
                    'options' => ['additionalData', 'current_team_idValueTexts'],
                    'attributes' => [],
                ],
            ],
            'show' => [],
        ];
    }

    public function fixValues(): array
    {
        return [
            'users-password' => bin2hex(random_bytes(16)),
        ];
    }

    public function indexQuery(): LengthAwarePaginator
    {
        $t0 = 'users';

        $query = $this->modelClassName::query()
            ->select([
                "$t0.id",
                "$t0.name as $t0-name",
                "$t0.email as $t0-email",
            ]);

        foreach ($this->conditions() as $k => $v) {
            if ($this->filters[$k] > 0) {
                $cond = $this->conditions()[$k];
                $value = strtr($cond['value'], $this->replaceFieldToValue());
                $query->where(str_replace('-', '.', $k), $cond['operator'], $value, $cond['boolean']);
            }
        }

        foreach ($this->orders['index']['sorts'][$this->sort['field']] as $v) {
            $query->orderBy($v, $this->sort['direction']);
        }

        $results = $query->paginate($this->paging['per_page'], ['*'], null, $this->paging['page']);

        return $results;
    }

    public function additionalIndexData()
    {
        $empty = [['value' => '', 'text' => '']];
        $current_team_idValueTexts = array_merge($empty, $this->getCurrent_team_idValueTexts());

        return [
            'current_team_idValueTexts' => $current_team_idValueTexts,
        ];
    }

    public function additionalCreateData()
    {
        $empty = [['value' => '', 'text' => '']];
        $current_team_idValueTexts = array_merge($empty, $this->getCurrent_team_idValueTexts());

        return [
            'current_team_idValueTexts' => $current_team_idValueTexts,
        ];
    }

    public function additionalEditData()
    {
        $empty = [['value' => '', 'text' => '']];
        $current_team_idValueTexts = array_merge($empty, $this->getCurrent_team_idValueTexts());

        return [
            'current_team_idValueTexts' => $current_team_idValueTexts,
        ];
    }

    public function getCurrent_team_idValueTexts()
    {
        return  [
            ['value' => '', 'text' => 'Mind'],
            ['value' => '1', 'text' => 'egy'],
            ['value' => '2', 'text' => 'kettő'],
            ['value' => '3', 'text' => 'három'],
            ['value' => '4', 'text' => 'négy'],
            ['value' => '5', 'text' => 'öt'],
            ['value' => '6', 'text' => 'hat'],
            ['value' => '7', 'text' => 'hét'],
            ['value' => '8', 'text' => 'nyolc'],
            ['value' => '9', 'text' => 'kilenc'],
            ['value' => '10', 'text' => 'tíz'],
        ];
    }
}
