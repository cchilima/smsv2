<?php

namespace app\PowerGridThemes;

use PowerComponents\LivewirePowerGrid\Themes\Components\{
    Actions,
    Checkbox,
    Cols,
    Editable,
    FilterBoolean,
    FilterDatePicker,
    FilterInputText,
    FilterMultiSelect,
    FilterNumber,
    FilterSelect,
    Footer,
    Radio,
    SearchBox,
    Table,
    Toggleable
};
use PowerComponents\LivewirePowerGrid\Themes\Theme;
use PowerComponents\LivewirePowerGrid\Themes\ThemeBase;

class MaterializeCustom extends ThemeBase
{
    public string $name = 'bootstrap5';

    public function table(): Table
    {
        return Theme::table('striped highlight responsive-table')
            ->div('col s12', 'margin: 10px 0 10px;')
            ->thead('')
            ->thAction('')
            ->tdAction('btn btn-small black white-text')
            ->tr('')
            ->th('', 'white-space: nowrap;min-width: 50px;font-size: 0.75rem;color: #6b6a6a;padding-top: 8px;padding-bottom: 8px;')
            ->tbody('')
            ->tdBodyEmpty('', 'vertical-align: middle; line-height: normal;')
            ->tdBodyTotalColumns('', 'font-size: 0.875rem; line-height: 1.25rem; --tw-text-opacity: 1; color: rgb(76 79 82 / var(--tw-text-opacity)); padding-left: 0.75rem; padding-right: 0.75rem; padding-top: 0.5rem; padding-bottom: 0.5rem;')
            ->tdBody('', 'vertical-align: middle; line-height: normal;white-space: nowrap;');
    }

    public function cols(): Cols
    {
        return Theme::cols()
            ->div('')
            ->clearFilter('', 'color: #c30707; cursor:pointer; float: right;');
    }

    public function footer(): Footer
    {
        return Theme::footer()
            ->view($this->root() . '.footer')
            ->select('');
    }

    public function actions(): Actions
    {
        return Theme::actions()
            ->tdBody('center-align')
            ->rowsBtn('btn-floating btn-small black white-text');
    }

    public function toggleable(): Toggleable
    {
        return Theme::toggleable()
            ->view($this->root() . '.toggleable');
    }

    public function editable(): Editable
    {
        return Theme::editable()
            ->view($this->root() . '.editable')
            ->span('d-flex justify-content-between')
            ->button('width: 100%;text-align: left;border: 0;padding: 4px;background: none')
            ->input('validate');
    }

    public function checkbox(): Checkbox
    {
        return Theme::checkbox()
            ->th('', 'font-size: 1rem !important;text-align:center')
            ->div('checkbox')
            ->label('')
            ->input('filled-in');
    }

    public function radio(): Radio
    {
        return Theme::radio()
            ->th('')
            ->label('')
            ->input('with-gap');
    }

    public function filterBoolean(): FilterBoolean
    {
        return Theme::filterBoolean()
            ->view($this->root() . '.filters.boolean')
            ->select('browser-default');
    }

    public function filterDatePicker(): FilterDatePicker
    {
        return Theme::filterDatePicker()
            ->view($this->root() . '.filters.date-picker')
            ->input('datepicker');
    }

    public function filterMultiSelect(): FilterMultiSelect
    {
        return Theme::filterMultiSelect()
            ->view($this->root() . '.filters.multi-select');
    }

    public function filterNumber(): FilterNumber
    {
        return Theme::filterNumber()
            ->base(attrStyle: 'min-width: 85px !important')
            ->view($this->root() . '.filters.number')
            ->input('validate');
    }

    public function filterSelect(): FilterSelect
    {
        return Theme::filterSelect()
            ->view($this->root() . '.filters.select')
            ->select('browser-default');
    }

    public function filterInputText(): FilterInputText
    {
        return Theme::filterInputText()
            ->base(attrStyle: 'min-width: 165px !important')
            ->view($this->root() . '.filters.input-text')
            ->select('browser-default')
            ->input('validate');
    }

    public function searchBox(): SearchBox
    {
        return Theme::searchBox()
            ->input('');
        //->iconSearch('bi bi-search')
        // ->iconClose('');
    }
}
