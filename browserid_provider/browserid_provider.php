<?php
/**
 * Name: BrowserID/Persona Provider
 * Description: Allow users to login to BrowserID/Persona -enabled sites using Friendica acct
 * Version: 0.1
 * Author: Fabio Comuni <http://kirgroup.com/profile/fabrixxm>
 * 
 * 
 * 
 *
 * Addons are registered with the system through the admin
 * panel.
 *
 * When registration is detected, the system calls the plugin
 * name_install() function, located in 'addon/name/name.php',
 * where 'name' is the name of the addon.
 * If the addon is removed from the configuration list, the 
 * system will call the name_uninstall() function.
 *
 */


/*
* install hooks and create private key
*/
function browserid_provider_install() {
    register_hook("_well_known_mod_init", THISPLUGIN, "browserid_provider_well_known");
    
    $sitekeys = get_config('browserid', 'keys');
    if ($sitekeys===False) browserid_provider_createkey();
    
}

/*
* uninstall hooks
*/
function browserid_provider_uninstall() {
    unregister_hook("_well_known_mod_init", THISPLUGIN, "browserid_provider_well_known");
}


/*
* create key
*/
function browserid_provider_createkey(){
   // Create the keypair
    $res=openssl_pkey_new();

    // Get private key
    openssl_pkey_export($res, $privkey);

    // Get public key
    $kinfo=openssl_pkey_get_details($res);
    $pubkey=$pubkey["key"]; 
    
   $keys = array(
        'private' => $privkey,
        'public' => $pubkey,
        'details' => $kinfo,
    ); 
    
    set_config('browserid', 'keys', $keys);
    return $keys;
}

/**
 * return host browserid info
 */
function browserid_provider_well_known(&$a, $b) {

    $sitekeys = get_config('browserid', 'keys');
    if ($sitekeys===False) $sitekeys = browserid_provider_createkey();
    $rsa = $sitekeys['details']['rsa'];
 
    

    
 #   echo "<pre>"; var_dump($privkey, $kinfo, base64_encode($kinfo['rsa']['n']));
    
    

    if ($a->argc > 1 && $a->argv[1]==="browserid") {
        echo json_encode(array(
            "public-key" => array(
                "algorithm" => "RS",
                "n" => base64_encode($rsa['n']),
                "e" => base64_encode($rsa['e'])
            ),
            "authentication" => "/browserid_provider/sign_in.html",
            "provisioning" => "/browserid_provider/provision.html"
        ));
        killme();
    }
}