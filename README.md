**NOTE**: If you are viewing this on GitHub, please be advised that the repo has been moved to [GitLab](https://gitlab.netinventors.de/-/ide/project/shopware/labs/NetiToolKit) and we will no longer respond to Pull Requests on this repo, as it is only a mirror of the GitLab repository.


# Shopware ToolKit

## About ToolKit

This plugin for Shopware  provides some usefull functions and tools wich every developer needs sometimes.

## Requirements:
* Shopware version >= 5.2.6
* NetiFoundation >= 2.0.0

## Install:
1. If you haven't already, download and install our free plugin "[NetiFoundation](http://store.shopware.com/detail/index/sArticle/162025)" from the Shopware Community Store
2. Download this plugin via [Shopware Store](http://store.shopware.com/detail/index/sArticle/163077) or clone this repository to the
*"engine/Shopware/custom/plugins/"* folder, **remember to name the plugin folder "NetiToolKit"** and install through the Plugin Manager

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

### Custom HTML / JS Code Emotion component (SW >= 5.2.10)
An emotion (Shopping worlds) component that enables you to add arbitrary HTML / JS code to shop pages with no 
filtering or alteration. This differs from the default custom code emotion component in that you can mix JS and HTML code.
As this code is relayed to the page with no alteration, we take no responsibility for any problems resulting from this. 
Use at your own risk!

## Get involved and let's get better together
We highly appreciate if you want to add further functions and nifty helper tools. Just fork our plugin and create a pull request.

## How to report bugs / request features?

 - www.shopinventors.de
 - www.netinventors.de

## License
Please see [License File](LICENSE) for more information.

## Contact
**Net Inventors GmbH**  
Stahltwiete 23  
22761 Hamburg  
Germany  

T. 040 42934714-0 // F. 040 42934714-9  
www.netinventors.de // info@netinventors.de

