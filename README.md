# Shopware ToolKit

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Join the chat at https://gitter.im/NetInventors/sw.ext.neti_tool_kit](https://badges.gitter.im/NetInventors/sw.ext.neti_tool_kit.svg)](https://gitter.im/NetInventors/sw.ext.neti_tool_kit?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

## About ToolKit

This plugin for Shopware  provides some usefull functions and tools wich every developer needs sometimes.

## Requirements:
* Shopware version >= 5.1.0.
* NetiFoundation >= 1.9.10

## Install:
1. If you haven't already, download and install our free plugin "[NetiFoundation](http://store.shopware.com/detail/index/sArticle/162025)" from the Shopware Community Store
2. Download this plugin via [Shopware Store](http://store.shopware.com/detail/index/sArticle/163077) or clone this repository to the
*"engine/Shopware/Plugins/Core"* folder, **remember to name the plugin folder "NetiToolKit"** and install through the Plugin Manager

## Configuration:
> Because some of these Features were moved from the Net Inventors Foundation Plugin the features **UserData**
and **UserLoggedIn** are enabled by default in the Plugin configuration. All other configurations could be enabled in the plugin settings.

## Tools and usage:
So far we added following tools:

### article properties in article listing
You can just access them like in the article details
* e.g. `{$sArticle.sProperties[1].value}`

### UserData
If the user is logged in you can access the user infos on every page
* Accessed in Smarty via `{$netiUserData}`

### UserLoggedIn
Here you can check globally if the user is logged in
* Accessed in Smarty via `{$sUserLoggedIn}`

## VCS
https://github.com/NetInventors/sw.ext.neti_tool_kit/

## Changelog
The changelog and all available commits are located [here](https://github.com/NetInventors/sw.ext.neti_tool_kit/commits).

## Get involved and let's get better together
We highly appreciate if you want to add further functions and nifty helper tools. Just fork our plugin and create a pull request.

## How to report bugs / request features?

 - www.shopinventors.de
 - www.netinventors.de
 - the issues "[issues](https://github.com/NetInventors/sw.ext.neti_tool_kit/issues)" section of this repo

## License
Please see [License File](LICENSE) for more information.

## Contact
**Net Inventors GmbH**  
Stahltwiete 23  
22761 Hamburg  
Germany  

T. 040 42934714-0 // F. 040 42934714-9  
www.netinventors.de // info@netinventors.de

