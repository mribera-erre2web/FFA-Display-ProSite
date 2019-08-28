# FFA Display ProSite
 
Setup in the wp-admin page:

Set in the input the leagueId of the Tournament and press the button, this will populate the DB with the data that we need. Will take a while if the partecipant are a lot due a N number of request to ESL API
 
Usage as shortcode in a page:

[ffa-display all=false] <-- This will display the single standings

[ffa-display all=true] <-- This will display the general standings with the sum of all match

## What's Next

I will implement Groups to the view and trying to find a way to speedup the 
synchronization of the data
