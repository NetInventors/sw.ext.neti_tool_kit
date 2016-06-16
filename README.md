# Shopware ToolKit
This little Shopware5 extension prodvides some usefull functions and tools wich every developer needs sometimes

## Install:
1. Download and install via Shopware Store our free plugin "[NetiFoundation](http://store.shopware.com/detail/index/sArticle/162025)"
2. Download via Shopware Store and install through the Plugin Manager or just clone this repository to the
*"engine/Shopware/Plugins/Core"* folder, **remember to name the plugin folder "NetiToolKit"**

## Use:
We added the following helpers:

### article properties in listing view
You can just accsses them like in the article details
* e.g. `{$sArticle.sProperties[20].value}`

### UserData
If the user is logged in you can access the user infos on every page
* Accessed in Smarty via `{$netiUserData}`

### UserLoggedIn
Here you can check globally if the user is logged in
* Accessed in Smarty via `{$sUserLoggedIn}`


## VCS
https://github.com/NetInventors/sw.ext.neti_tool_kit/

## Changelog
The changelog and all available commits are located under <https://github.com/NetInventors/sw.ext.neti_tool_kit/commits>.

## Get involved
We highly appreciate if you want to add further functions and nifty helper tools. Just fork our plugin and create a pull request.

## How to report bugs / request features?

 - www.shopinventors.de
 - www.netinventors.de
 - The Issues section of this Repo

## Contact
Net Inventors GmbH
Stahltwiete 23 // 22761 Hamburg
T 040 42934714-0  // F 040 42934714-9
www.netinventors.de // info@netinventors.de

