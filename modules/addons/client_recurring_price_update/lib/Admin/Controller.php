<?php

namespace WHMCS\Module\Addon\client_recurring_price_update\Admin;
use WHMCS\Database\Capsule;
class Controller {

    public function index($vars)
    {
        $modulelink = $vars['modulelink']; 
        $version = $vars['version']; 
        $LANG = $vars['_lang'];
        $dt='';
        $dt .= '<form action="'.$modulelink.'" method="POST" style="padding:25px;box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;width:fit-content">';
         $dt.='<label>Product</label><br/><select id="product" value="all" class="custom-select" style="width: 250px;
  min-width: 15ch;
  max-width: 30ch;
  border: 1px solid;
  border-radius: 0.25em;
  padding: 0.25em 0.5em;
  font-size: 1.25rem;
  cursor: pointer;
  line-height: 1.1;
  background-color: #fff;
  background-image: linear-gradient(to top, #f9f9f9, #fff 33%);">
         <option value="all">All</option>
         <option value="hosting">Hosting</option>
         <option value="addon">Addons</option>
         <option value="domain">Domain</option>
         </select><br/>
         ';
        $dt.='<label>Currency</label><br/><select id="currencyId" value="0" class="custom-select" style="width: 250px;
  min-width: 15ch;
  max-width: 30ch;
  border: 1px solid;
  border-radius: 0.25em;
  padding: 0.25em 0.5em;
  font-size: 1.25rem;
  cursor: pointer;
  line-height: 1.1;
  background-color: #fff;
  background-image: linear-gradient(to top, #f9f9f9, #fff 33%);">';
foreach(Capsule::table('tblcurrencies')->get() as $currencies){
        $dt.='<option value="'.$currencies->id.'">'.$currencies->code.'</option>';
        
     }
     $dt.='<option value="0">All Currency</option>';
     
      $dt .= '</select><br/><hr/>';
      $dt .= '<input type="hidden" name="action" value="submit"/>
      <button type="submit" style="text-decoration: none;display: inline-block;padding: 8px 16px;background-color: #f1f1f1;color: black;">Update User Price &raquo;</button>
      </form><br/>';
        return <<<EOF

<h2>Client Recurring Price Update - Module by <a href="https://cyberhale.com" target="_blank">CyberHale.com</a></h2>

<p>Updates recurring price for all products using WHMCS Auto Recalculate feature</p>

<p><strong>Steps:</strong></p>
<ol>
<li>Select Product</li>
<li>Select Currency For which you want to Update Or select "All Currency" option To update All Users</li>
<li>Click Button And Wait for process to Complete</li>
</ol>


<p>
    {$dt}
</p>

EOF;
    }


    public function submit($post)
    {
        $currId=$post['currencyId'];
        $product=$post['product'];
        $modulelink = $vars['modulelink'];
        $version = $vars['version']; 
        $LANG = $vars['_lang']; 
         $adminUsername = '';
         $dt="";
        if($currId > 0){
            foreach(Capsule::table('tblclients')->where('currency', '=', $currId)->pluck('id') as $userid){
                if($product && ($product == "All" || $product == "hosting")){
                    foreach (Capsule::table('tblhosting')->where('userid', '=', $userid)->pluck('id') as $serviceId) {
                        localAPI('UpdateClientProduct', array('serviceid' => $serviceId, 'autorecalc' => true), $adminUsername);
                    }
                }
                if($product && ($product == "All" || $product == "addon")){
                    foreach (Capsule::table('tblhostingaddons')->where('userid', '=', $userid)->pluck('id') as $serviceAddonId) {
                        localAPI('UpdateClientAddon', array('id' => $serviceAddonId, 'autorecalc' => true), $adminUsername);
                    }
                }
                if($product && ($product == "All" || $product == "domain")){
                    foreach (Capsule::table('tbldomains')->where('userid', '=', $userid)->pluck('id') as $domainId) {
                        localAPI('UpdateClientDomain', array('domainid' => $domainId, 'autorecalc' => true), $adminUsername);
                    }
                }
            }
            $dt .= '<h4> Update Completed for users with '.$_POST['currencyCode'].' currency</h4><br/>';
        }
        else{
            foreach(Capsule::table('tblclients')->pluck('id') as $userid){
                if($product && ($product == "All" || $product == "hosting")){
                    foreach (Capsule::table('tblhosting')->where('userid', '=', $userid)->pluck('id') as $serviceId) {
                        localAPI('UpdateClientProduct', array('serviceid' => $serviceId, 'autorecalc' => true), $adminUsername);
                    }
                }
                if($product && ($product == "All" || $product == "addon")){
                    foreach (Capsule::table('tblhostingaddons')->where('userid', '=', $userid)->pluck('id') as $serviceAddonId) {
                        localAPI('UpdateClientAddon', array('id' => $serviceAddonId, 'autorecalc' => true), $adminUsername);
                    }
                }
                if($product && ($product == "All" || $product == "domain")){
                    foreach (Capsule::table('tbldomains')->where('userid', '=', $userid)->pluck('id') as $domainId) {
                        localAPI('UpdateClientDomain', array('domainid' => $domainId, 'autorecalc' => true), $adminUsername);
                    }
                }        
            }
            $dt .= '<h4> Update Completed for All users</h4>';
        }
   
   $dt.='<br/><br/><a href="'.$modulelink.'" style="text-decoration: none;display: inline-block;padding: 8px 16px;background-color: #f1f1f1;color: black;border-radius: 20%;">&laquo; Go Back </a>';
    return $dt;
    }
}
