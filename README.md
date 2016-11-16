# Dice WP REST API for Headlines

Take advantage of WordPress' ability to manage content. Use the WP API to serve data to other platforms for their own use. Users will upload images which need to be served via the WordPress REST API. Ensure that proper images sizes are adhered to and that proper categories are assigned to each image.

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.


To install this plugin, use .git. Go to the repository located [GitHub](git@github.com:DiceHoldingsInc/Dice-WP-REST-API-For-Headlines.git).
Open up your command line and enter: 

```
cd sites/wordpress/wp-content/plugins
```
Once inside WordPress' plugins directory use .git to clone the repository to your machine. 


```
git clone git@github.com:DiceHoldingsInc/Dice-WP-REST-API-For-Headlines.git
```

Alternatively, if you don't have your SSH keys set up yet, you can use HTTPS:

```
git clone https://github.com/DiceHoldingsInc/Dice-WP-REST-API-For-Headlines.git
```

Make sure to switch to a development branch before you start work. It is Dice WordPress standard to prepend your branch name with the title of the associated Jira Task, i.e. `wp-123_my-important-branch`



## Pushing changes back to the repository

Once you have completed making your changes to plugin you have a few options for sharing your work. First is to push the development branch to the repository for a code review. 

Use git again:

```
git push -u origin <branch>
```

From this point forward, always do a `git pull` on this branch to ensure that you get the latest changes that may be present on the branch. 

There are some Git Hooks in some of the repositories for Dice.com, but this is not one of them, **yet**! You will have to manually notify your team of new changes to this repository. The best place to do so is in the HipChat room, [WordPress Development](https://dhi.hipchat.com/chat/room/198562). 

## Dice Documentation
[Dice WP REST API for Mobile Apps](https://confluence.dice.com/display/WP/Dice+WP+REST+API+For+Headlines)

### WP REST API Development
* [WP REST API on GitHub](https://github.com/WP-API/WP-API)
* [WP REST API Homepage](http://v2.wp-api.org/)
* [WP REST API Reference](http://v2.wp-api.org/reference/)
* [WP REST API Media Objects](http://v2.wp-api.org/reference/media/)

## Versioning
We [Date of Release Versioning ](https://en.wikipedia.org/wiki/Software_versioning#Date_of_release) for versioning. Once your code has been accepted, the version shall be changed to the date of the release. 

* **Josh Smith** - *Initial Development* - [GitHub - joshsmith01](https://github.com/joshsmith01)

See also the list of [contributors](https://github.com/DiceHoldingsInc/Dice-WP-REST-API-For-Headlines/graphs/contributors) who participated in this project.

