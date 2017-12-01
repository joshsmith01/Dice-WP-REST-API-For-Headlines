### [unreleased]
#### Update
* Move the posts per day to the Settings section and away from the Ajax call

### 1.4.5
#### Added
* New settings field to leverage an override URL in case the correct URL is not being used. 
** Only use this override URL if the Reverse-Proxy or rewrite rules on the server are not changing the URL appropriately. 

### 1.4.4
#### Added
* Display Ideal Employer posts with category of Headline in the Dashboard Widget
* Add ability to remove Headline category from Ideal Employer posts from Dashboard widget

### 1.4.3
#### Added
* Ability to call in Ideal Employer posts
* Display the Headlines Post Meta box on the Ideal Employer page also
#### Updated
* Refactor Query arguments to use Taxonomy query instead of category name

### 1.4.2
#### Added
* Use Ideal Employers CPT for Headlines


### 1.4.1
Update database with a default Headlines per Day 

### 1.4.0
#### Update
* Hiding the widget instructions behind a button. Expansion of the button is up to the user and not automatically open all the time, saving valuable real estate on the Dashboard. 
* Adding a link for users to view the response that is produced by the API for quick reference

#### Added
* Users can set the number of posts with the category of Headline to display per day.
 
### 1.2.5
#### Added
* Found and fixed bug that would correctly find the Headline category name, even though the headline name was not a parameter of `get_posts()`.
 
### 1.3.2
#### Added
Added the ability for users to add specific tracking codes to individual posts. 
   
### 1.3.1
#### Removed
Remove the option to select images from a dropdown in the Headlines Meta box.

### 1.3.0
#### Added
Add feature to lock sortable list item into place...with a caveat. While the individual list item can't be selected for sorting, adjacent list items can be. Adjacent list items can *steal* the locked items position. 
 
Also added in this version, are UI enhancements to indicate that a post is actually locked.

