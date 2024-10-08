@use('PowerComponents\LivewirePowerGrid\Components\Rules\RuleManager')

@if(data_get($setUp, 'detail.state.' . $rowId))
    @php
        $rulesValues = $actionRulesClass->recoverFromAction($row, RuleManager::TYPE_ROWS);
    @endphp

    <td colspan="999">
        @if (filled($rulesValues['detailView']))
            @includeWhen(data_get($setUp, 'detail.state.' . $row->{$this->realPrimaryKey}),
                $rulesValues['detailView'][0]['detailView'],
                [
                    'id' => data_get($row, $this->realPrimaryKey),
                    'options' => array_merge(
                        data_get($setUp, 'detail.options'),
                        $rulesValues['detailView']['0']['options']),
                ]
            )
        @else
            @includeWhen(data_get($setUp, 'detail.state.' . $row->{$this->realPrimaryKey}),
                data_get($setUp, 'detail.view'),
                [
                    'id' => data_get($row, $this->realPrimaryKey),
                    'options' => data_get($setUp, 'detail.options'),
                ]
            )
        @endif
    </td>
@endif
