<?php
/**
 * For more information, please refer to the online documentation.
 *
 * @see https://whmcs.cyberhale.com/
 *
 */


use WHMCS\Database\Capsule;
use WHMCS\Module\Addon\client_recurring_price_update\Admin\AdminDispatcher;
use WHMCS\Module\Addon\client_recurring_price_update\Client\ClientDispatcher;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}


function client_recurring_price_update_config()
{
    return [
        // Display name for your module
        'name' => 'Client Recurring Price Update',
        // Description displayed within the admin interface
        'description' => 'Updates the Recurring Price of all Products for all clients'
            . ' With the click of one button.',
        'author' => 'Akash Kothari - cyberhale.com',
        'language' => 'english',
        'version' => '1.0',
    ];
}

function client_recurring_price_update_activate()
{
    try {
        return [
            'status' => 'success',
            'description' => 'Addon Module is Activated. '
                . 'Access the Module From Addons->Client Recurring Price Update',
        ];
    } catch (\Exception $e) {
        return [
            'status' => "error",
            'description' => 'Unable to create mod_addonexample: ' . $e->getMessage(),
        ];
    }
}


function client_recurring_price_update_deactivate()
{
    try {
        Capsule::schema()
            ->dropIfExists('mod_addonexample');

        return [
            'status' => 'success',
            'description' => 'Addon Module is Deactivated. ',
        ];
    } catch (\Exception $e) {
        return [
            "status" => "error",
            "description" => "Unable to drop mod_addonexample: {$e->getMessage()}",
        ];
    }
}


function client_recurring_price_update_output($vars)
{
    $modulelink = $vars['modulelink']; 
    $version = $vars['version']; 
    $_lang = $vars['_lang'];
    $dt = "";
    
    $action = isset($_POST['action']) ? $_POST['action'] : 'index';

    $dispatcher = new AdminDispatcher();
    $response = $dispatcher->dispatch($action,$_POST);
    echo $response;
}




