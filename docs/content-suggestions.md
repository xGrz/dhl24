# Content suggestion

DHL API requires to provide shipment contents.
This package has helper for content suggestions.

## Add content suggestion
If you want to add content suggestion use facade method:
```php
xGrz\Dhl24\Facades\DHL24::contentSuggestions()->add($name);
```
There is no limit for suggestions.

> This method can throw DHL24Exception when name is not unique!
___

## Suggestions listing (query)
```php
xGrz\Dhl24\Facades\DHL24::contentSuggestions()->query(): Builder;
```
This method return query builder for content suggestions. You can use all eloquent query builder methods. It allows to add pagination or filtering results.
This method has already assigned scope for sorting by name. If you have marked suggestion as default it will be always first on list. 
___

## Rename suggestion
If you want to modify suggestion `name` you can use:
```php
xGrz\Dhl24\Facades\DHL24::contentSuggestions($suggestion)->rename($name);
```
First parameter `suggestion` can be eider `xGrz\Dhl24\Models\DHLContentSuggestion` or `id` of suggestion.
Second parameter `name` should be new name of suggestions.

> This method can throw DHL24Exception when name is not unique!
___

## Delete suggestion
```php
xGrz\Dhl24\Facades\DHL24::contentSuggestions($suggestion)->delete();
```
Parameter `suggestion` can be eider `xGrz\Dhl24\Models\DHLContentSuggestion` or `id` of suggestion to delete.
This model deletes permanently database row. Soft delete is not implemented here.
___

## How to use it?
You can provide suggestions list as select if you don't allow users providing own contents.
```bladehtml
<select>
    @foreach(\xGrz\Dhl24\Facades\DHL24::contentSuggestions()->query()->get() as $suggestion)
        <option>{{$suggestion->name}}</option>
    @endforeach
</select>
```

If user can provide own content the best way is to use datalist related to input:
```bladehtml
<input list="contents"/>
<datalist id="contents">
    @foreach(\xGrz\Dhl24\Facades\DHL24::contentSuggestions()->query()->get() as $suggestion)
        <option value="{{$suggestion->name}}"/>
    @endforeach
</datalist>

```

