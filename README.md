In version 1.1 I focused on a way to optimize the login process, because in version 1.0 once a user had 100+ 
locations the login process took around 5-7 minutes because the system had get all of the user's information.

So instead of storing the user's data in a session variable I had the system create and update XML files 
specific to each user, and then use the XML to display data to the browser. This reduced login times to 1-2 
minutes since it didn't have to get all of the information from the database.

The difficulty was in keeping the database and XML insync.
