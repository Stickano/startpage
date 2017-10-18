# Startpage
A practical startpage for your browser.

Ever wanted a startpage that served an actual useful purpose? Well, here's the beginning of one such at least. With this startpage you have what seems most logic to have nearby in your browser environment. It serves as a Bookmark keeper, a note taker and a nice way to keep track of current and comming weather. 

<p align="center"><a href="https://www.youtube.com/watch?v=bYsIQXgQAA4"><img src="https://github.com/Stickano/startpage/blob/master/preview/bookmarks.png" /></a></p>

<br><br>
## Table of Content
* [Overview](#overview)
* [Installation](#installation)
* [Settings](#settings)
* [Bookmarks](#bookmarks)
* [Notes](#notes)
* [Weather](#weather)

<br><br>
### Overview
This project runs on a database - any database will do, be it local or some online host. The project will install itself - or the database at least - once you provide it with some valid server credentials.

The project has the ability to encrypt your notes. For this to take effect you first has to provide a password in the settings panel. The encryption of your notes happens with a AES-256-ctr encryption which should be sufficient for this usage. 

Error messages will be shown and several conditions are checked upon. More is to come of course. 

In order for the weather API to work you must first create an account at [OpenWeatherMap](https://openweathermap.org/) (OWM), which is the service this project uses. 

The page is responsive and quite some dynamic features has been added through Jquery and appropriate steps has been taken to make it as user-friendly as possible - such as autofocus where focus is needed. 

Throughout the pages you'll have a [DuckDuckGo](https://duckduckgo.com) search field available at hand - this field will be autofocused from start. One of the nice features in most browser, is the possibility to start typing as soon as the browser window opens which will lead you to a search engine of choice. This feature is kept by focusing the DDG search field, so you just start searching as you'd normally would.

You'll find the menu below the DDG search field. It will dynamically change depending on what page you're on and what setup steps has been completed. <br>
<p align="center">
  <img src="https://github.com/Stickano/startpage/blob/master/preview/menu.png"/>
  <img src="https://github.com/Stickano/startpage/blob/master/preview/menuDelUrls.png"/>
  <img src="https://github.com/Stickano/startpage/blob/master/preview/menuNotes.png"/>
</p>
<br>

It's a low resource project - though, a couple of thanks is in order. <br>
[**JQuery**](https://jquery.com/), A lovely little JavaScript framework. <br>
[**Font-Awesome**](http://fontawesome.io), Icon pack. <br>
[**Weather Icons**](https://erikflowers.github.io/), A extension to the Font-Awesome package. <br>

Besides that, quite a few models from my own [Web Helpers](https://github.com/Stickano/WebHelpers/) package is being used.
<br><br>

### Installation
In order for this project to work its full potential, one must first provide a database for it to store data. PHP is the language of choice for this project, so this has to be installed as well. 

If you already have a live host, just point this site to that host along with the credentials. 

If you want this locally (on you machine), this can also easily be done with the [XAMP](https://www.apachefriends.org/index.html) package. Installation and configuration for XAMP is beyong the scope of this guide.

Once you have your Apache environment up and running, download and place this project inside you `<Document Root>` and open the `index.php` document where you can navigate to the Settings page. From here you are able to provide the connection to your database. 

You now have access to Bookmarks and Notes. If you want the Weather API, first create an account at [OWM](https://openweathermap.org/). It is a free service of course and your account/usage will only be used for statistical purposes. It will leave you with an API-key, though, which you can paste into the Settings page to get the weather forecast up and running. You will also have to provide a City ID, which can be found [at their site](http://openweathermap.org/help/city_list.txt) (ctrl-f is your friend).

One last step, also optional of course, would be to provide a password for encrypt/decrypt of notes. This sweet little feature allows you to keep you private notes just that - private. Once a password is set, all the notes in the database will be encrypted (along with future notes of course) and only your password will be able to unlock em' again. 

A manual way of installing the database is also available through the included `startpage.sql` file. 
 If you do not set up a database, none of the Bookmark, Weather or Note page will be available.
 <br><br>
 
 ### Settings
 This will be the only page available when the database is yet to be installed. 
 Once the database has been installed another two settings becomes available - A password option and the Weather API details from OWM. Once these steps has been done, this page become of no use unless you want to change these values at a later point. 
 <br><br>
 
 ### Bookmarks
 The landing page is your stored URLs for quick access to your favourite sites. Your Bookmarks will be put into categories, by your choice, and can be easily created from the menu.
 <p align="center"><img src="https://github.com/Stickano/startpage/blob/master/preview/addUrl.png"/></p><br>
 
 When you are creating new bookmarks it is easy to pick an already created category, or click the little `+` next to the input to add a new category. The only required field is the URL itself, though it does look better when you also provide a `Headline`. A little description attached to the URL is also available and will be displayed below the URL/Headline. 
 
 It is also easy to clean out in your bookmarks. Select multiple targets for deletion as a breeze.
 <p align="center"><img src="https://github.com/Stickano/startpage/blob/master/preview/urlSelect.png"/></p>
 <br><br>
 
 ### Notes
 As soon as you provide a password in the settings panel, the notes will be encrypted with an AES-256-ctr encryption, and the backend logic will handle the decryption when you want it to be available. There is currently no autolock function, so once you unlock your notes, they will have to be locked again before not being available in readable text on the page.<br>
 <p align="center"><img src="https://github.com/Stickano/startpage/blob/master/preview/lock.gif"></p>
 <br>
 
 You can change your password on the Settings page at any time - The logic will handle the encryption actions between passwords. 
 
 When a password is set, adding and deleting notes only becomes available when the panel is unlocked.
 <br><br>
 
 ### Weather
 Once the API key and City ID is provided in the settings menu ([OWM](http://openweathermap.org)) the weather page will be available. Here you can keep track of current and tomorrows weather in a nice, little presented way. <br>
<p align="center"><img src="https://github.com/Stickano/startpage/blob/master/preview/weather.png"/></p>
<br><br>
The Weather API presents a few key informations, with a nice little followed icon which also present some value. You'll quickly be able to estimate if you should bring that umbrella with the current weather information/icon. The temperature will let you know to put on that sweater and the wind direction/speed is just nice-to-haves. The icon for the wind speed will change accordingly to the wind degree. 
