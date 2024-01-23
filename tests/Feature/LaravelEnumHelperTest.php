<?php

declare(strict_types=1);

use Suleymanozev\LaravelEnumHelper\Exceptions\TranslationMissing;
use Suleymanozev\LaravelEnumHelper\Tests\Support\Enums\Status;
use Suleymanozev\LaravelEnumHelper\Tests\Support\Enums\StatusFallbackLocale;
use Suleymanozev\LaravelEnumHelper\Tests\Support\Enums\StatusInt;
use Suleymanozev\LaravelEnumHelper\Tests\Support\Enums\StatusPascalCase;
use Suleymanozev\LaravelEnumHelper\Tests\Support\Enums\StatusPascalCaseWithoutTranslation;
use Suleymanozev\LaravelEnumHelper\Tests\Support\Enums\StatusString;
use Suleymanozev\LaravelEnumHelper\Tests\Support\Enums\StatusWithoutTranslations;

it('can be used as a static method to get value', function ($enumClass, $enumMethod, $result) {
    expect($enumClass::$enumMethod())->toBe($result);
})->with([
    'Pure Enum Camel Case' => [Status::class, 'pending', 'PENDING'],
    'Pure Enum Upper Case' => [Status::class, 'PENDING', 'PENDING'],
    'Pure Enum Pascal Case' => [Status::class, 'Pending', 'PENDING'],
    'Pure Enum Camel Case multiword' => [Status::class, 'noResponse', 'NO_RESPONSE'],
    'Pure Enum Upper Case multiword' => [Status::class, 'NO_RESPONSE', 'NO_RESPONSE'],
    'Pure Enum Pascal Case multiword' => [Status::class, 'NoResponse', 'NO_RESPONSE'],
    'Pure Pascal Enum Camel Case' => [StatusPascalCase::class, 'pending', 'Pending'],
    'Pure Pascal Enum Upper Case' => [StatusPascalCase::class, 'PENDING', 'Pending'],
    'Pure Pascal Enum Pascal Case' => [StatusPascalCase::class, 'Pending', 'Pending'],
    'Pure Pascal Enum Camel Case multiword' => [StatusPascalCase::class, 'noResponse', 'NoResponse'],
    'Pure Pascal Enum Upper Case multiword' => [StatusPascalCase::class, 'NO_RESPONSE', 'NoResponse'],
    'Pure Pascal Enum Pascal Case multiword' => [StatusPascalCase::class, 'NoResponse', 'NoResponse'],
    'String Backed Enum Camel Case' => [StatusString::class, 'pending', 'P'],
    'String Backed Enum Upper Case' => [StatusString::class, 'PENDING', 'P'],
    'String Backed Enum Pascal Case' => [StatusString::class, 'Pending', 'P'],
    'String Backed Enum Camel Case multiword' => [StatusString::class, 'noResponse', 'N'],
    'String Backed Enum Upper Case multiword' => [StatusString::class, 'NO_RESPONSE', 'N'],
    'String Backed Enum Pascal Case multiword' => [StatusString::class, 'NoResponse', 'N'],
    'Int Backed Enum Camel Case' => [StatusInt::class, 'pending', 0],
    'Int Backed Enum Upper Case' => [StatusInt::class, 'PENDING', 0],
    'Int Backed Enum Pascal Case' => [StatusInt::class, 'Pending', 0],
    'Int Backed Enum Camel Case multiword' => [StatusInt::class, 'noResponse', 3],
    'Int Backed Enum Upper Case multiword' => [StatusInt::class, 'NO_RESPONSE', 3],
    'Int Backed Enum Pascal Case multiword' => [StatusInt::class, 'NoResponse', 3],
]);

it('can has correct translation text', function ($enum, $result) {
    expect($enum->description())->toBe($result);
})->with([
    'translation' => [StatusString::NO_RESPONSE, 'No response'],
    'fallback translation' => [StatusFallbackLocale::PENDING, 'FB Await decision'],
    'method definition' => [StatusPascalCase::NoResponse, 'PC No response'],
]);

it('has correct translation text passing lang', function ($enum, $lang, $result) {
    expect($enum->description($lang))->toBe($result);
})->with([
    'eng translation string backed enum' => [StatusString::NO_RESPONSE, 'en', 'No response'],
    'ita translation string backed enum' => [StatusString::NO_RESPONSE, 'it', 'Nessuna Risposta'],
    'eng translation int backed enum' => [StatusInt::PENDING, 'en', 'Await decision'],
    'ita translation int backed enum' => [StatusInt::NO_RESPONSE, 'it', 'Nessuna Risposta'],
    'ita fallback locale translation' => [StatusFallbackLocale::NO_RESPONSE, 'it', 'FB No response'],
    'eng fallback translation' => [Status::PENDING, 'en', 'Await decision'],
    'ita fallback translation' => [Status::PENDING, 'it', 'In attesa'],
    'eng method' => [StatusPascalCase::Pending, 'en', 'PC Await decision'],
    'ita method' => [StatusPascalCase::Pending, 'it', 'PC Await decision'],
]);

it('can has correct translation with method name both singular and plural', function ($enum, $lang, $result) {
    expect($enum->news($lang))->toBe($result);
})->with([
    'enum with translations' => [Status::PENDING, null, 'news PENDING'],
    'enum with translations with lang param' => [Status::NO_RESPONSE, 'it', 'ITA news NO_RESPONSE'],
    'enum with method' => [StatusString::PENDING, null, 'news P'],
    'enum with method with lang param' => [StatusString::NO_RESPONSE, 'it', 'news N'],
]);

it('can return a translation with magic method', function ($enum, $result) {
    expect($enum->excerpt())->toBe($result);
})->with([
    [Status::PENDING, 'asdkj dskjdsa dkjsa ksjd sadask'],
    [Status::NO_RESPONSE, 'as dklasd asldldlasd'],
]);

it(' throw an exception if try to call fallback with no descriprion property', function ($enum, $lang) {
    expect(fn () => $enum->property($lang))->toThrow(TranslationMissing::class);
})->with([
    [Status::PENDING, 'en'],
    [StatusPascalCase::NoResponse, 'it'],
]);

it('can return a property with magic method', function ($enum, $result) {
    expect($enum->color())->toBe($result);
})->with([
    [Status::PENDING, '#000000'],
    [Status::NO_RESPONSE, '#FFFFFF'],
]);

it('can return a different translation with excerpt magic method passing lang', function ($enum, $lang, $result) {
    expect($enum->excerpt($lang))->toBe($result);
})->with([
    'eng' => [Status::PENDING, 'en', 'asdkj dskjdsa dkjsa ksjd sadask'],
    'ita' => [Status::PENDING, 'it', 'ITA asdkj dskjdsa dkjsa ksjd sadask'],
]);

it('can return an array of translations', function ($enumClass, $result) {
    expect($enumClass::descriptions())->toBe($result);
})->with([
    [Status::class, [
        'Await decision',
        'Recognized valid',
        'No longer useful',
        'No response',
    ]],
    [StatusString::class, [
        'Await decision',
        'Recognized valid',
        'No longer useful',
        'No response',
    ]],
]);

it('can return an array of translations with cases and lang param', function ($enumClass, $cases, $lang, $result) {
    expect($enumClass::descriptions($cases, $lang))->toBe($result);
})->with([
    'All Enum cases into it ' => [Status::class, null, 'it', [
        'In attesa',
        'Accettato',
        'Rifiutato',
        'Nessuna Risposta',
    ]],
    'Some Enum cases into en ' => [StatusString::class, [StatusString::NO_RESPONSE, StatusString::DISCARDED], 'en', [
        'No response',
        'No longer useful',
    ]],
]);

it('can return an array of translations with method name both singular and plural', function ($enumClass, $cases, $lang, $result) {
    expect($enumClass::news($cases, $lang))->toBe($result);
})->with([
    'translations' => [Status::class, null, null, ['news PENDING', 'news ACCEPTED', 'news DISCARDED', 'news NO_RESPONSE']],
    'translations with cases param' => [Status::class, [Status::ACCEPTED, Status::DISCARDED], null, ['news ACCEPTED', 'news DISCARDED']],
    'translations with lang and cases param' => [Status::class, [Status::ACCEPTED, Status::NO_RESPONSE], 'it', ['ITA news ACCEPTED', 'ITA news NO_RESPONSE']],
]);

//it('can\'t return an array of method results with method name both singular and plural', function ($enumClass, $cases, $lang) {
//    expect(fn() => $enumClass::news($cases, $lang))->toRuntimeError();
//})->with([
//    'enum with method' => [StatusString::class, null, null],
//    'enum with method with cases param' => [StatusString::class, [StatusString::DISCARDED, StatusString::ACCEPTED], null],
//    'enum with method with lang and cases param' => [StatusString::class, [StatusString::NO_RESPONSE, StatusString::ACCEPTED], 'it'],
//]);

it('can return an array of translations with magic method', function ($enumClass, $cases, $lang, $result) {
    expect($enumClass::excerpts($cases, $lang))->toBe($result);
})->with([
    'without cases' => [Status::class, null, 'en', [
        'asdkj dskjdsa dkjsa ksjd sadask',
        'ls klsa dlksa dlksa salk salk',
        'ksalsa ld djks jjdk skjd j',
        'as dklasd asldldlasd',
    ]],
    'with cases' => [Status::class, [Status::NO_RESPONSE, Status::DISCARDED], 'en', [
        'as dklasd asldldlasd',
        'ksalsa ld djks jjdk skjd j',
    ]],
]);

it('can return an array of properties with magic method', function ($enumClass, $cases, $lang, $result) {
    expect($enumClass::colors($cases, $lang))->toBe($result);
})->with([
    [Status::class, null, null, ['#000000', '#0000FF', '#FF0000', '#FFFFFF']],
    [Status::class, [Status::ACCEPTED, Status::NO_RESPONSE], 'it', ['#0000FF', '#FFFFFF']],
]);

it('can return an array of properties with magic method with 2 words', function ($enumClass, $cases, $lang, $result) {
    expect($enumClass::multipleColors($cases, $lang))->toBe($result);
})->with([
    [Status::class, null, null, [['#000000', '#000001'], ['#0000FF', '#0000F1'], ['#FF0000', '#FF0001'], ['#FFFFFF', '#FFFFF1']]],
]);

it('can return an array of translations with magic method with multiple worlds', function ($enumClass, $cases, $lang, $result) {
    expect($enumClass::multipleWordsExcerpts($cases, $lang))->toBe($result);
})->with([
    'without cases' => [Status::class, null, 'en', [
        'asdkj dskjdsa dkjsa ksjd sadask',
        'ls klsa dlksa dlksa salk salk',
        'ksalsa ld djks jjdk skjd j',
        'as dklasd asldldlasd',
    ]],
    'with cases' => [Status::class, [Status::NO_RESPONSE, Status::DISCARDED], 'en', [
        'as dklasd asldldlasd',
        'ksalsa ld djks jjdk skjd j',
    ]],
]);

it('can return an array of translations with magic method with multiple worlds _', function ($enumClass, $cases, $lang, $result) {
    expect($enumClass::multiple_words_excerpts($cases, $lang))->toBe($result);
})->with([
    'without cases' => [Status::class, null, 'en', [
        'asdkj dskjdsa dkjsa ksjd sadask',
        'ls klsa dlksa dlksa salk salk',
        'ksalsa ld djks jjdk skjd j',
        'as dklasd asldldlasd',
    ]],
    'with cases' => [Status::class, [Status::NO_RESPONSE, Status::DISCARDED], 'en', [
        'as dklasd asldldlasd',
        'ksalsa ld djks jjdk skjd j',
    ]],
]);

it('can return an associative array of translations [name => translations]', function ($enumClass, $result) {
    expect($enumClass::descriptionsByName())->toBe($result);
})->with([
    'Pure Enum' => [Status::class, [
        'PENDING' => 'Await decision',
        'ACCEPTED' => 'Recognized valid',
        'DISCARDED' => 'No longer useful',
        'NO_RESPONSE' => 'No response',
    ]],
    'Backed Enum' => [StatusString::class, [
        'PENDING' => 'Await decision',
        'ACCEPTED' => 'Recognized valid',
        'DISCARDED' => 'No longer useful',
        'NO_RESPONSE' => 'No response',
    ]],
]);

it('can return an associative array of translations [value => translations]', function ($enumClass, $result) {
    expect($enumClass::descriptionsByValue())->toBe($result);
})->with([
    'Backed Enum' => [StatusString::class, [
        'P' => 'Await decision',
        'A' => 'Recognized valid',
        'D' => 'No longer useful',
        'N' => 'No response',
    ]],
]);

it('can return an associative array of translations [name => translations] with params', function ($enumClass, $cases, $lang, $result) {
    expect($enumClass::descriptionsByName($cases, $lang))->toBe($result);
})->with([
    'All Enum cases into en ' => [Status::class, null, 'en', [
        'PENDING' => 'Await decision',
        'ACCEPTED' => 'Recognized valid',
        'DISCARDED' => 'No longer useful',
        'NO_RESPONSE' => 'No response',
    ]],
    'Some cases into it ' => [Status::class, [Status::DISCARDED, Status::NO_RESPONSE], 'it', [
        'DISCARDED' => 'Rifiutato',
        'NO_RESPONSE' => 'Nessuna Risposta',
    ]],
    'All string backed Enum cases into it ' => [StatusString::class, null, 'it', [
        'PENDING' => 'In attesa',
        'ACCEPTED' => 'Accettato',
        'DISCARDED' => 'Rifiutato',
        'NO_RESPONSE' => 'Nessuna Risposta',
    ]],
    'Some string backed Enum cases into it ' => [StatusString::class, [StatusString::NO_RESPONSE, StatusString::NO_RESPONSE], 'it', [
        'NO_RESPONSE' => 'Nessuna Risposta',
    ]],
]);

it('can return an associative array of translations [value => translations] with params', function ($enumClass, $cases, $lang, $result) {
    expect($enumClass::descriptionsByValue($cases, $lang))->toBe($result);
})->with([
    'Pure Enum' => [Status::class, null, null, [
        'PENDING' => 'Await decision',
        'ACCEPTED' => 'Recognized valid',
        'DISCARDED' => 'No longer useful',
        'NO_RESPONSE' => 'No response',
    ]],
    'Pure Enum subset' => [Status::class, [Status::PENDING, Status::DISCARDED], 'it', [
        'PENDING' => 'In attesa',
        'DISCARDED' => 'Rifiutato',
    ]],
    'Backed Enum' => [StatusString::class, null, null, [
        'P' => 'Await decision',
        'A' => 'Recognized valid',
        'D' => 'No longer useful',
        'N' => 'No response',
    ]],
    'Backed Enum subset' => [StatusInt::class,
        [StatusInt::DISCARDED, StatusInt::ACCEPTED], 'it', [
            2 => 'Rifiutato',
            1 => 'Accettato',
        ], ],
]);

it('can return an associative array of magic translations [value => translations] with method name both singular and plural', function ($enumClass, $cases, $lang, $result) {
    expect($enumClass::newsByName($cases, $lang))->toBe($result);
})->with([
    'translations' => [Status::class, null, null, [
        'PENDING' => 'news PENDING',
        'ACCEPTED' => 'news ACCEPTED',
        'DISCARDED' => 'news DISCARDED',
        'NO_RESPONSE' => 'news NO_RESPONSE',
    ]],
    'translations with cases param' => [Status::class, [Status::ACCEPTED, Status::DISCARDED], null,
        ['ACCEPTED' => 'news ACCEPTED', 'DISCARDED' => 'news DISCARDED'],
    ],
    'translations with lang and cases param' => [Status::class, [Status::ACCEPTED, Status::NO_RESPONSE], 'it',
        ['ACCEPTED' => 'ITA news ACCEPTED', 'NO_RESPONSE' => 'ITA news NO_RESPONSE'],
    ],
]);

it('can return an associative array of translations [name => translations] with excerpt magic method', function ($enumClass, $result) {
    expect($enumClass::excerptsByName())->toBe($result);
})->with([
    [Status::class, [
        'PENDING' => 'asdkj dskjdsa dkjsa ksjd sadask',
        'ACCEPTED' => 'ls klsa dlksa dlksa salk salk',
        'DISCARDED' => 'ksalsa ld djks jjdk skjd j',
        'NO_RESPONSE' => 'as dklasd asldldlasd',
    ]],
]);

it('can return an associative array of properties [name => translations] with excerpt magic method', function ($enumClass, $result) {
    expect($enumClass::colorsByName())->toBe($result);
})->with([
    [Status::class, [
        'PENDING' => '#000000',
        'ACCEPTED' => '#0000FF',
        'DISCARDED' => '#FF0000',
        'NO_RESPONSE' => '#FFFFFF',
    ]],
]);

it('can return an associative array of translations [value => translations] with excerpt magic method', function ($enumClass, $cases, $lang, $result) {
    expect($enumClass::excerptsByValue($cases, $lang))->toBe($result);
})->with([
    [StatusString::class, null, null, [
        'P' => 'asdkj dskjdsa dkjsa ksjd sadask',
        'A' => 'ls klsa dlksa dlksa salk salk',
        'D' => 'ksalsa ld djks jjdk skjd j',
        'N' => 'as dklasd asldldlasd',
    ]],
    [StatusString::class, [StatusString::DISCARDED, StatusString::ACCEPTED], 'it', [
        'D' => 'ITA ksalsa ld djks jjdk skjd j',
        'A' => 'ITA ls klsa dlksa dlksa salk salk',
    ]],
]);

it('can return an associative array of properties [value => translations] with excerpt magic method', function ($enumClass, $cases, $lang, $result) {
    expect($enumClass::colorsByValue($cases, $lang))->toBe($result);
})->with([
    [StatusString::class, null, null, [
        'P' => '#000000',
        'A' => '#0000FF',
        'D' => '#FF0000',
        'N' => '#FFFFFF',
    ]],
    [StatusString::class, [StatusString::DISCARDED, StatusString::ACCEPTED], 'it', [
        'D' => '#FF0000',
        'A' => '#0000FF',
    ]],
]);

it('can return an associative array of magic translations [value/name => translations] with method name both singular and plural', function ($enumClass, $cases, $lang, $result) {
    expect($enumClass::newsByValue($cases, $lang))->toBe($result);
})->with([
    'translations' => [Status::class, null, null, [
        'PENDING' => 'news PENDING',
        'ACCEPTED' => 'news ACCEPTED',
        'DISCARDED' => 'news DISCARDED',
        'NO_RESPONSE' => 'news NO_RESPONSE',
    ]],
    'translations with cases param' => [Status::class, [Status::ACCEPTED, Status::DISCARDED], null,
        ['ACCEPTED' => 'news ACCEPTED', 'DISCARDED' => 'news DISCARDED'],
    ],
    'translations with lang and cases param' => [Status::class, [Status::ACCEPTED, Status::NO_RESPONSE], 'it',
        ['ACCEPTED' => 'ITA news ACCEPTED', 'NO_RESPONSE' => 'ITA news NO_RESPONSE'],
    ],
]);

it('throw an TranslationMissing exception', function () {
    expect(fn () => StatusString::NO_RESPONSE->notExist())->toThrow(TranslationMissing::class);
    expect(fn () => StatusString::notExists(null, 'it'))->toThrow(TranslationMissing::class);
    expect(fn () => Status::notExists(null))->toThrow(TranslationMissing::class);
});

it('use a fallback case name if a description translation missing without exception', function () {
    expect(StatusPascalCaseWithoutTranslation::NoResponse->description())->toBe(
        'No Response' // NoResponse humanized
    )->not->toThrow(TranslationMissing::class);

    expect(StatusPascalCaseWithoutTranslation::descriptions())->toBe([
        'Await decision',
        'Recognized valid',
        'No longer useful',
        'No Response', // NoResponse humanized
    ])->not->toThrow(TranslationMissing::class);
});

it('use all fallbacks case names for description method if all translations missing without exception', function () {
    expect(StatusWithoutTranslations::descriptions())->toBe([
        'Pending', // PENDING humanized
        'Accepted', // ACCEPTED humanized
        'Discarded', // DISCARDED humanized
        'No Response', // NO_RESPONSE humanized
    ])->not->toThrow(TranslationMissing::class);
});
