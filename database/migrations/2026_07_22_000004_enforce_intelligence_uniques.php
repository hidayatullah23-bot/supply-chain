<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('economic_indicators')->select('country_id','indicator_code','recorded_year',DB::raw('MIN(id) keep_id'))->groupBy('country_id','indicator_code','recorded_year')->havingRaw('COUNT(*) > 1')->orderBy('keep_id')->each(function($row){ DB::table('economic_indicators')->where('country_id',$row->country_id)->where('indicator_code',$row->indicator_code)->where('recorded_year',$row->recorded_year)->where('id','<>',$row->keep_id)->delete(); });
        $hasUnique=collect(Schema::getIndexes('economic_indicators'))->contains(fn($index)=>$index['unique']&&$index['columns']===['country_id','indicator_code','recorded_year']);
        if(!$hasUnique) Schema::table('economic_indicators',fn(Blueprint $table)=>$table->unique(['country_id','indicator_code','recorded_year'],'economic_country_indicator_year_unique'));

        $hasCurrencyUnique=collect(Schema::getIndexes('currency_exchange_rates'))->contains(fn($index)=>$index['unique']&&$index['columns']===['country_id','currency_code','recorded_date']);
        if(!$hasCurrencyUnique) Schema::table('currency_exchange_rates',fn(Blueprint $table)=>$table->unique(['country_id','currency_code','recorded_date'],'currency_country_code_date_unique'));
    }
    public function down():void{}
};
