<?php

use Carbon\Carbon;
use RoniEstein\Press\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class)->in('Feature', 'Unit');
uses(RefreshDatabase::class)->in('Feature');


function ddf($args){
    dd($args);
}


expect()->extend('toBeSameModel', function ($model) {
    
    return $this
        ->table->toEqual($model->table)
        ->id->toEqual($model->id);
});


expect()->extend('toHaveDate', function ($date) {
    
    return $this->toDateString()->toEqual(Carbon::parse($date)->toDateString());
    
});

