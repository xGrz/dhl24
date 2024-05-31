# Content suggestion

DHL API requires to provide shipment contents.
This package has helper for content suggestions.

## Add content suggestion
If you want to add content suggestion use facade method:
```php
xGrz\Dhl24\Facades\DHL24::addContentSuggestion($name);
```
There is no limit for suggestions.

> This method can throw DHL24Exception when name is not unique!

## Suggestions listing
```php
xGrz\Dhl24\Facades\DHL24::contentSuggestions(): array;
```
This method return array of `id`, `name` and `is_default` content suggestions. Array is ordered by `is_default`, then alphabetically `name`. 

Only one suggestion can be default. Default suggestion will be always first row of array. 

## Rename suggestion
If you want to modify suggestion `name` you can use:
```php
xGrz\Dhl24\Facades\DHL24::renameContentSuggestion($suggestion, $name);
```
First parameter `suggestion` can be eider `xGrz\Dhl24\Models\DHLContentSuggestion` or `id` of suggestion.
Second parameter `name` should be new name of suggestions.

> This method can throw DHL24Exception when name is not unique!

## Delete suggestion
```php
xGrz\Dhl24\Facades\DHL24::deleteContentSuggestion($suggestion)
```
Parameter `suggestion` can be eider `xGrz\Dhl24\Models\DHLContentSuggestion` or `id` of suggestion to delete.
This model deletes permanently database row. Soft delete is not supported here.
___

## How to use it?
You can provide suggestions list as select if you don't allow users providing own contents.
```bladehtml
<select>
    @foreach(xGrz\Dhl24\Facades\DHL24::contentSuggestions() as $suggestion)
        <option>{{$suggestion->name}}</option>
    @endforeach
</select>
```

If user can provide own content the best way is to use datalist related to input:
```bladehtml
<input list="contents"/>
<datalist id="contents">
    @foreach(xGrz\Dhl24\Facades\DHL24::contentSuggestions() as $suggestion)
        <option value="{{$suggestion->name}}"/>
    @endforeach
</datalist>

```

