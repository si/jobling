=== Twitter Digest  ===
Tags: twitter, tweet, post digest
Contributors: tbeck
Requires at least: 2.7
Tested up to: 3.8
Stable Tag: 2.9

Creates a daily or weekly post containing tweets from a twitter account. 

== Description ==

This plugin uses the pseudo-cron facility available in Wordpress to publish a
daily or weekly post of tweets from the previous day.  

Notes:

- Versions >= 2.0 have contributions from Paul Wlodarczyk
(http://thecontentguy.net/). Much thanks goes out to him.
- This plugin has not been tested on anything other than Wordpress 2.7 and
above  (If anyone wants to try an old version, let me know.)

== Installation ==

1. Download the plugin archive and expand it (you've likely already done this).
2. Put the 'twitter-digest.php' file into your wp-content/plugins/ directory.
3. Go to the Plugins page in your WordPress Administration area and click 'Activate' for Twitter Digest.
4. Go to the Twitter Digest Options page (Options > Twitter Digest) to set your Twitter account information and preferences.


== Configuration ==

There are a number of configuration options for Twitter Digest. You can find these in Options > Twitter Digest.

== ChangeLog ==

v2.9

- Updated to use https for all twitter.com links
- Fixed hashtag link bug
- Fixed PHP bugs pointed out by Eric Celeste

v2.8

- Updated to use Twitter OAuth and Twitter API v1.1.  See
  http://www.webdevdoor.com/php/authenticating-twitter-feed-timeline-oauth/
  for how to create a Twitter App and the four tokens of the apocalypse.

v2.7

- Updated to the latest version of the Twitter API
- Added option to create the digest post in draft mode
- Added option to include retweets in digest post
- Fixed some bugs with publishing time

v2.6

- Fixed bug with Twitter's tweet ids

v2.5

- Changed binary options to checkboxes
- Separated tweet date and time display options

v2.4

- Removed password requirement. Can no longer access private Twitter accounts
  via TD.  This was less work than going to oAuth.

v2.3

- Fixed some stupid errors.

v2.2

- Fixed date visibility option

v2.1

- Fixed bug in default date format

v2.0

- Added support for weekly digest. This is a <b>beta</b> feature.  Any
  feedback is welcome.
- Post limit is now 200
- Added ability to specify an excerpt for each post
- Added ability to format the date in the post title/excerpt

v1.8.3

- Fixed timezone issues
- Added button to reset plugin database

v1.8.2

- Fixed bug that caused HTML entities to be broken links
- Fixed issue with publishing in timezone that is not the same as the server timezone

v1.8.1

- Fixed bug that caused broken status link if @ message was not in reply to
  another message.

v1.8

- Added check for username/password on installation
- Added ability to ping the service from the options page.
- Added ability to specify the time of day the tweet post will be published

v1.7.2

- Added support for custom title

v1.7.1

- Fixed possible race condition issue
- Tweaked code layout
- Made min tweets always be at least 1
- Fixed bug with url in brackets not being made clickable.
- Fixed bug with 'in reply to' links not working 

v1.7

- Fixed bug that caused badness if minimum tweets was set to 0
- Added option to reverse the order of tweet display

v1.5

- Fixed bug with min tweets restriction
- Fixed bug with hashtag linkification

v1.4

- Fixed bug showing options error

v1.3

- Added options page
- Added minimum tweets required to post
- Fixed @twitter detection

v1.2

- Added ability to ignore @replies

v1.1

- Tweaked posting procedure so the post goes from the draft state to
published state, in order to trigger any hooks that would get triggered on a
'normal' post.
- Cleaned up post output

   
v1.0
- Basic tweet polling and posting.

== Contact ==

If you have questions or comments, please visit http://whalespine.org.

Thanks
Tim Beck
