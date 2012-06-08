ZenJoinMe - an integration between Zendesk and join.Me
---

Written by Adam Panzer

Installation
---

1.) Update the define statements in the join.me.php file to include the following: join.me Username, join.me Password (You can get these by signing up for a trial. Even after the trial expires, they are still good), Zendesk URL (no trailing slash but you need /api/v2 on the end), Zendesk Username, and Zendesk API token. 

2.) Once you update the define statements put the PHP on you server in an accessible location.

3.) Inside your Zendesk create a new Custom Widget and add the following:

	<iframe id="ticketID" title={{ticket.id}} src="http://example.com/join.me.php?ticketid={{ticket.id}}"></iframe>

(obviously replace example.com with your site URL.

4.) Save the widget and browse to a ticket. Add the widget to the individual ticket page.

5.) Once this is done you should be good to go.

How does this work?
---

When you realize you want to do a screenshare with a customer you just click the links. It adds a public commment to the ticket with a download link for the customer. In addition, it adds a private comment with the view link for the agent. 