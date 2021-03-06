# publish-unpublish-menu-options

 ## Description

A simple WordPress plugin that displays a publish (or unpublish) menu option (or a link) and updates the post status accordingly. It can be limited to function for specific post_types.

A simple plugin that toggles publish status for the current post.
This plugins only works in conjunction with pages with "Publish" and "Unpublish" 
links, as shown below:
  
    Publish URL: [current-url]publish=yes
    Publish URL Container: class="publish-link"
    Unpublish URL: [current-url]publish=no
    Unpublish URL Container: class="unpublish-link"
  
  Note: The [current-url] shortocdes is provided by this plugin.
 
  The complete code can be represented as html (with shortcodes) as follows:

    <div class="publish-link"><a href="[current-url]publish=yes">Publish</a></div>
    <div class="unpublish-link"><a href="[current-url]publish=no">Unpublish</a></div>
    [display-modal-confirmation]

  The plugin works with posts and pages by default. This can be controlled in the 
  wp-config.php file, by defining an array of post types to the as_post_types 
  variable. For example:

    $as_post_types = array('book', 'movie');

  Additionally, the plugin restricts it's operation only to the administrator role. If you want to enable other roles, define these in wp-config.php, for example:

    $as_allowed_roles = array('editor', 'administrator', 'author');

  This plugin optionally can include a confirmation dialog. To use it place the 
  following shortcode on the page:

    [display-modal-confirmation] 

## How to install

Download zip file. Install as WordPress plugin.

## Configuration

Once the plugin is installed, configure your system as follows:

1. Update wpconfig.php with post types to manage:

    ``` 
    $as_post_types = array('book', 'movie');
    ```

2. Be sure that 2 links appear on the page/post for the post types you want to manage.

    ```
    <div class="publish-link"><a href="[current-url]publish=yes">Publish</a></div>
    <div class="unpublish-link"><a href="[current-url]publish=no">Unpublish</a></div>
    ```

    Note: The [current-url] shortcode is provided by this plugin.


3. Add this shortcode to every page/post you want to manage.

    ```    
    [display-modal-confirmation] 
    ```
 
    Notes: 
    The [display-modal-confirmation] shortcode is provided by this plugin.
    The plugin will generate html for a modal confirmation window - it will be initially hidden.

## Demo
    
This plugin can be seen in action by following the steps below:
  
1. Login at https://studiovids.com/wp-login.php (Username: demo Password:demo8675309!!!)

2. Open the page: https://studiovids.com/sample-page/ (look for the Publish/Unpublish link.)
