<?php


use Illuminate\Support\Facades\Route;
use HeliosLive\PhpNovaSpatieTagsFilter\AutocompleteController;

Route::post('/tags', [AutocompleteController::class, 'tags']);
