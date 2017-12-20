# Honeypot: Spam Prevention
A simple spam prevention package for Laravel 5.5.

# Installation:
### Composer
Run this command inside your terminal to add the package into your project.
```
composer require appel/honeypot
```

### Publish Config File and Translations
Publish the package config file and translations to your application. Run this command inside your terminal.
```
php artisan vendor:publish --provider="Appel\Honeypot\Providers\HoneypotServiceProvider"
```
Or, you may want to publish the file individually.
```
php artisan vendor:publish --provider="Appel\Honeypot\Providers\HoneypotServiceProvider" --tag="config"
php artisan vendor:publish --provider="Appel\Honeypot\Providers\HoneypotServiceProvider" --tag="lang"
```

# Usage
### Facade
Add the honeypot hidden input into your form by inserting `Honeypot::make(...)` like this:
```html
<form action="..." method="...">
    {!! Honeypot::make('honeypot_name', 'honeypot_time') !!}
</form>
```
### Helper Function
```html
<form action="..." method="...">
    {!! honeypot('honeypot_name', 'honeypot_time') !!}
</form>
```
### Blade Directive
```html
<form action="..." method="...">
    @honeypot('honeypot_name', 'honeypot_time')
</form>
```
**Note:** If you are using the Blade directive, you may need to run `php artisan view:clear` for it to work.

The `make()` method will output the following HTML input. (The `honeypot_time` field will generate an encrypted timestamp.
```html
<div id="honeypot_name_wrap" style="display: none;">
    <input type="text" name="honeypot_name" id="honeypot_name" value="" autocomplete="off">
    <input type="text" name="honeypot_time" id="honeypot_time" value="encrypted timestamp" autocomplete="off">
</div>
```
After adding the honeypot fields, add the validation rules in your controller for the honeypot and honeytime fields.
```php
$this->validate($request, [
    ...
    'honeypot_name' => 'honeypot',
    'honeypot_time' => 'required|honeytime:5'
]);
```
Please note that in the honeytime rule, you need to specify the number of seconds it should take for the user to fill 
out the form. If it takes less time than that, the form will be rejected.

# Settings
The honeypots are hidden on the page by using `display: hidden`. You can alternatively have them moved off the page by 
publishing the config file and changing this setting:
```php
return [
    ...
    /**
     * Hide the fields by setting the display property of the outer div to none,
     * or by moving it off-screen. Accepted values: 'hide', 'off-screen'
     */
    'hide_mode'     => 'off-screen'
];
```

# Credits
This project is based on an earlier project by [Ian Landsman](https://github.com/ianlandsman/Honeypot).
The following people contributed to this project: Maksim Surguy, Arjhay Delos Santos

This project is sponsored by [Bomshteyn Consulting](https://bomshteyn.com/).
