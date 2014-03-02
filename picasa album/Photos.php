<?php

require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_AuthSub');
Zend_Loader::loadClass('Zend_Gdata_Photos');
Zend_Loader::loadClass('Zend_Gdata_Photos_UserQuery');
Zend_Loader::loadClass('Zend_Gdata_Photos_AlbumQuery');
Zend_Loader::loadClass('Zend_Gdata_Photos_PhotoQuery');
Zend_Loader::loadClass('Zend_Gdata_App_Extension_Category');

session_start();

/**
 * Adds a new photo to the specified album
 * @param  Zend_Http_Client $client  The authenticated client
 * @param  string           $user    The user's account name
 * @param  integer          $albumId The album's id
 * @param  array            $photo   The uploaded photo
 * @return void
 */

/**
 * Adds a new album to the specified user's album
 */
function addAlbum($client, $user, $name) $album['name']
{
    $photos = new Zend_Gdata_Photos($client);

    $entry = new Zend_Gdata_Photos_AlbumEntry();
    $entry->setTitle($photos->newTitle($name));

    $result = $photos->insertAlbumEntry($entry);
    if ($result) {
        outputUserFeed($client, $user);
    } else {
        echo "There was an issue with the album creation.";
    }
}
/**
 * Returns the path to the current script, without any query params
 */
function getCurrentScript()
{
    global $_SERVER;
    return $_SERVER["PHP_SELF"];
}

/**
 * Returns the full URL of the current page, based upon env variables
 */
function getCurrentUrl()
{
    global $_SERVER;

    /**
     * Filter php_self to avoid a security vulnerability.
     */
    $php_request_uri = htmlentities(substr($_SERVER['REQUEST_URI'], 0,
    strcspn($_SERVER['REQUEST_URI'], "\n\r")), ENT_QUOTES);

    if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }
    $host = $_SERVER['HTTP_HOST'];
    if ($_SERVER['SERVER_PORT'] != '' &&
        (($protocol == 'http://' && $_SERVER['SERVER_PORT'] != '80') ||
        ($protocol == 'https://' && $_SERVER['SERVER_PORT'] != '443'))) {
            $port = ':' . $_SERVER['SERVER_PORT'];
    } else {
        $port = '';
    }
    return $protocol . $host . $port . $php_request_uri;
}

/**
 * Returns the AuthSub URL which the user must visit to authenticate requests
 */
function getAuthSubUrl()
{
    $next = getCurrentUrl();
    $scope = 'http://picasaweb.google.com/data';
    $secure = false;
    $session = true;
    return Zend_Gdata_AuthSub::getAuthSubTokenUri($next, $scope, $secure,
        $session);
}

/**
 * Outputs a request to the user to login to their Google account, including
 * a link to the AuthSub URL.
 */
function requestUserLogin($linkText)
{
    $authSubUrl = getAuthSubUrl();
    echo "<a href=\"{$authSubUrl}\">{$linkText}</a>";
}

/**
 * Returns a HTTP client object with the appropriate headers for communicating
 * with Google using AuthSub authentication.
 */
function getAuthSubHttpClient()
{
    global $_SESSION, $_GET;
    if (!isset($_SESSION['sessionToken']) && isset($_GET['token'])) {
        $_SESSION['sessionToken'] =
            Zend_Gdata_AuthSub::getAuthSubSessionToken($_GET['token']);
    }
    $client = Zend_Gdata_AuthSub::getHttpClient($_SESSION['sessionToken']);
    return $client;
}

/**
 * Processes loading of this sample code through a web browser.  Uses AuthSub
 * authentication and outputs a list of a user's albums if succesfully
 * authenticated.
 */
function processPageLoad()
{
    global $_SESSION, $_GET;
    if (!isset($_SESSION['sessionToken']) && !isset($_GET['token'])) {
        requestUserLogin('Please login to your Google Account.');
    } else {
        $client = getAuthSubHttpClient();
        if (!empty($_REQUEST['command'])) {
            switch ($_REQUEST['command']) {
                case 'retrieveSelf':
                    outputUserFeed($client, "default");
                    break;
                case 'retrieveUser':
                outputUserFeed($client, $_REQUEST['user']);
                    break;
                case 'retrieveAlbumFeed':
                    outputAlbumFeed($client, $_REQUEST['user'], $_REQUEST['album']);
                    break;
            }
        }

        // Now we handle the potentially destructive commands, which have to
        // be submitted by POST only.
        if (!empty($_POST['command'])) {
            switch ($_POST['command']) {
   
                case 'addAlbum':
                    addAlbum($client, $_POST['user'], $_POST['name']);
                    break;
              default:
                    break;
          }
        }

        // If a menu parameter is available, display a submenu.
        if (!empty($_REQUEST['menu'])) {
            switch ($_REQUEST['menu']) {
              case 'user':
                displayUserMenu();
                    break;
                case 'photo':
                    displayPhotoMenu();
                    break;
            case 'album':
              displayAlbumMenu();
                    break;
            case 'logout':
              logout();
                    break;
            default:
                header('HTTP/1.1 400 Bad Request');
                echo "<h2>Invalid menu selection.</h2>\n";
                echo "<p>Please check your request and try again.</p>";
          }
        }

        if (empty($_REQUEST['menu']) && empty($_REQUEST['command'])) {
            displayMenu();
        }
    }
}

/**
 * Displays the main menu, allowing the user to select from a list of actions.
 */
function displayMenu()
{
?>
    <ul class='ul'>
        <li><a href="?command=retrieveSelf">Click here to View Your Albums</a></li>
    </ul>
<?php
}

/**
 * Outputs an HTML link to return to the previous page.
 */
function displayBackLink()
{
    echo "<br><br>";
    echo "<a href='javascript: history.go(-1);'><< Back</a>";
}


/**
 * Outputs an HTML unordered list (ul), with each list item representing an
 * album in the user's feed.
 */
function outputUserFeed($client, $user)
{
    $photos = new Zend_Gdata_Photos($client);

    $query = new Zend_Gdata_Photos_UserQuery();
    $query->setUser($user);

    $userFeed = $photos->getUserFeed(null, $query);
    echo "<h2 class='h2'>User Feed for: " . $userFeed->getTitle() . "</h2>";
    echo "<ul class='user'>\n";
    foreach ($userFeed as $entry) {
        if ($entry instanceof Zend_Gdata_Photos_AlbumEntry) {
            echo "\t<li class='user'>";
            echo "<a href='?command=retrieveAlbumFeed&user=";
            echo $userFeed->getTitle() . "&album=" . $entry->getGphotoId();
            echo "'>";
            $thumb = $entry->getMediaGroup()->getThumbnail();
            echo "<img class='thumb' src='" . $thumb[0]->getUrl() . "' /><br />";
            echo $entry->getTitle() . "</a>";
            echo "</li>\n";
        }
    }
    echo "</ul><br />\n";
    echo "<h3>Add an Album</h3>";
?>
    <form method="POST" action="<?php echo getCurrentScript(); ?>">
        <input type="hidden" name="command" value="addAlbum" />
        <input type="hidden" name="user" value="<?php echo $user; ?>" />
        <input type="text" name="name" />
        <input type="submit" name="Add Album" />
    </form>
<?php

    displayBackLink();
}
?>
<?php

/**
 * Calls the main processing function for running in a browser
 */

processPageLoad();
